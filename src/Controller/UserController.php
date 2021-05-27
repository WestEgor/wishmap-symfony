<?php

namespace App\Controller;

use App\Form\ProfileType;
use App\Repository\UserRepository;
use App\Service\ImageUploader;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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

    #[Route('/wishmap/user', name: 'find_user')]
    public function userSuggest(UserRepository $userRepository, Request $request)
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $nickname = $request->query->get('user');
        $user = $userRepository->findUserByNick($nickname);
        $userJson = $serializer->serialize($user, 'json');
        return new Response(
            $userJson,
            Response::HTTP_OK
        );
    }

    #[Route('/users', name: 'find_all_users')]
    public function findAllUsers(PaginatorInterface $paginator, Request $request, UserRepository $userRepository)
    {
        $users = $userRepository->findAllNoPrivate();

        $pagination = $paginator->paginate(
            $users,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('user/all_profiles.html.twig', [
            'users' => $pagination
        ]);
    }

}