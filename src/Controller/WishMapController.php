<?php

namespace App\Controller;

use App\Entity\WishMap;
use App\Form\WishMapType;
use App\Repository\CategoryRepository;
use App\Repository\PersonRepository;
use App\Repository\WishMapRepository;
use App\Security\PersonAuthorization;
use App\Service\ImageUploader;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class WishMapController extends AbstractController
{

    private Security $security;

    /**
     * WishMapController constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    #[Route('/wishmap/create', name: 'create_wish_map')]
    public function wishMapCreate(Request $request, PersonRepository $personRepository, ImageUploader $imageUploader)
    {
        $personAuth = new PersonAuthorization($this->security);
        $person = $personAuth->getLoggedPerson($personRepository);
        $wishMap = new WishMap();

        $form = $this->createForm(WishMapType::class, $wishMap);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $wishMap = $form->getData();
            $wishMapImage = $form->get('image')->getData();
            if ($wishMapImage) {
                $imageName = $imageUploader->upload($wishMapImage);
                $wishMap->setImage($imageName);
            }
            $wishMap->setPerson($person);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($wishMap);
            $entityManager->flush();
            return $this->redirectToRoute('wish_map');
        }
        return $this->render('wish_map/new_wish_map.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/wishmap', name: 'wish_map', methods: ['get', 'post'])]
    public function index(WishMapRepository $wishMapRepository, PersonRepository $personRepository,
                          PaginatorInterface $paginator, Request $request): Response
    {
        $personAuth = new PersonAuthorization($this->security);
        $person = $personAuth->getLoggedPerson($personRepository);

        $wishMaps = $wishMapRepository->findByPerson($person);

        $pagination = $paginator->paginate(
            $wishMaps,
            $request->query->getInt('page', 1),
            4
        );


        return $this->render('wish_map/index.html.twig', [
            'wishmaps' => $pagination,
            'person' => $person
        ]);
    }

    #[ROUTE('/wishmap/all', name: 'wishmap_all')]
    public function showAllWishMapcards(WishMapRepository $wishMapRepository, CategoryRepository $categoryRepository,
                                        Request $request, PaginatorInterface $paginator)
    {
        $wishMaps = $wishMapRepository->findAll();
        $categories = $categoryRepository->findAll();

        $pagination = $paginator->paginate(
            $wishMaps,
            $request->query->getInt('page', 1),
            4
        );


        return $this->render('wish_map/allwm.html.twig', [
            'wishmaps' => $pagination,
            'categories' => $categories
        ]);
    }

    #[Route('/wishmap/category/{id}', name: 'wish_map_category', methods: ['get', 'post'])]
    public function selectCat(WishMapRepository $wishMapRepository,
                              PaginatorInterface $paginator, CategoryRepository $categoryRepository,
                              Request $request, $id): Response
    {

        $categories = $categoryRepository->findAll();
        $category = $categoryRepository->findOneCategoryByCategoryId($id);
        $wishMaps = $wishMapRepository->findByCategory($category);

        $pagination = $paginator->paginate(
            $wishMaps,
            $request->query->getInt('page', 1),
            4
        );


        return $this->render('wish_map/index.html.twig', [
            'wishmaps' => $pagination,
            'categories' => $categories
        ]);
    }

    #[Route('/wishmap/update/{id}', name: 'update_wishmap')]
    public function wishMapUpdate(ImageUploader $imageUploader,
                                  WishMapRepository $wishMapRepository, $id, Request $request)
    {
        $wishMap = $wishMapRepository->find($id);
        $form = $this->createForm(WishMapType::class, $wishMap);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $wishMapImage = $form->get('image')->getData();

            if ($wishMapImage) {
                $imageName = $imageUploader->upload($wishMapImage);
                $wishMap->setImage($imageName);
            }

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->flush();
            return $this->redirectToRoute('wish_map');
        }
        return $this->render('wish_map/new_wish_map.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[ROUTE('/wishmap/delete/{id}', name: 'wishmap_delete', methods: ['delete', 'get'])]
    public function deleteWishMap(int $id, WishMapRepository $wishMapRepository)
    {
        $wishMap = $wishMapRepository->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($wishMap);
        $entityManager->flush();
        $response = new Response();
        return $response->send();
    }



}