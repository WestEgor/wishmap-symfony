<?php

namespace App\Controller;

use App\Form\ProfileType;
use App\Service\ImageUploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{

    #[Route('/user/update', name: 'profile_update')]
    public function profileUpdate(ImageUploader $imageUploader, Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userAvatar = $form->get('avatar')->getData();

            if ($userAvatar) {
                $imageName = $imageUploader->upload($userAvatar);
                $user->setAvatar($imageName);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('wish_map');
        }

        return $this->render('user/update_user_profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}