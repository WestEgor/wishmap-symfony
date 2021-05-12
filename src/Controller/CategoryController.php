<?php


namespace App\Controller;


use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\PersonRepository;
use App\Security\PersonAuthorization;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/wishmap/category/create', name: 'create_category')]
    public function categoryCreate(PersonAuthorization $personAuthorization,
                                   PersonRepository $personRepository, Request $request)
    {

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $category->setPerson($personAuthorization->getLoggedPerson($personRepository));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('wish_map');
        }

        return $this->render('category/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}