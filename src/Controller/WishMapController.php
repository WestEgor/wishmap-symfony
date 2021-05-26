<?php

namespace App\Controller;

use App\Entity\WishMap;
use App\Form\WishMapType;
use App\Repository\UserRepository;
use App\Repository\WishMapRepository;
use App\Service\ImageUploader;
use App\Service\UserActionValidation;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use DateTime;

class WishMapController extends AbstractController
{

    #[Route('/wishmap', name: 'wish_map', methods: ['get', 'post'])]
    public function index(WishMapRepository $wishMapRepository, UserRepository $userRepository,
                          PaginatorInterface $paginator, Request $request): Response
    {
        $nickname = $request->query->get('user');
        if ($nickname != null) {
            $user = $userRepository->findOneBy(['nickname' => $nickname,
                'isPrivate' => 0]);
            if ($user == null) {
                $this->addFlash('info',
                    'USER DOESNT EXIST OR IT`S PRIVATE ACCOUNT!');
                return $this->forward('App\Controller\WishMapController::index');
            }
            $wishMaps = $wishMapRepository->findBy(['user' => $user,
                'isArchived' => 0]);
        } else {
            $user = $this->getUser();
            $wishMaps = $wishMapRepository->findBy(['user' => $user,
                'isArchived' => 0]);
        }

        $test = [];

        foreach ($wishMaps as $wm) {
            $test[] = $wm->getComments()->count();
        }

        $pagination = $paginator->paginate(
            $wishMaps,
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('wish_map/index.html.twig', [
            'wishmaps' => $pagination,
            'user' => $user,
            'test' => $test
        ]);
    }

    #[Route('/wishmap/create', name: 'create_wish_map')]
    public function wishMapCreate(Request $request, ImageUploader $imageUploader): RedirectResponse|Response
    {
        $user = $this->getUser();
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
            $wishMap->setUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($wishMap);
            $entityManager->flush();

            $this->addFlash('info',
                'Wish map successfully created!');

            return $this->redirectToRoute('wish_map');
        }
        return $this->render('wish_map/new_wish_map.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/wishmap/update/{id}', name: 'update_wishmap')]
    public function wishMapUpdate(int $id, ImageUploader $imageUploader, UserActionValidation $validation,
                                  WishMapRepository $wishMapRepository, Request $request): RedirectResponse|Response
    {
        if (!$validation->checkUserWishMapCard($wishMapRepository, $id)) {
            $this->addFlash('info',
                'YOU ARE CANNOT MODIFY FOREIGN WISH MAP CARD!');
            return $this->forward('App\Controller\WishMapController::index');
        }

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

            $this->addFlash('info',
                'Wish map successfully updated!');
            return $this->redirectToRoute('wish_map');
        }
        return $this->render('wish_map/new_wish_map.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[ROUTE('/wishmap/delete/{id}', name: 'wishmap_delete', methods: ['delete', 'get'])]
    public function deleteWishMap(int $id, WishMapRepository $wishMapRepository, UserActionValidation $validation)
    {
        if (!$validation->checkUserWishMapCard($wishMapRepository, $id)) {
            $this->addFlash('info',
                'YOU ARE CANNOT DELETE FOREIGN WISH MAP CARD!');
            return $this->forward('App\Controller\WishMapController::index');
        }
        $wishMap = $wishMapRepository->find($id);
        $wishMap->getComments()->clear();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($wishMap);
        $entityManager->flush();

        $this->addFlash('info',
            'Wish map successfully deleted!');

        $response = new Response();
        return $response->send();
    }


    #[ROUTE('/wishmap/all', name: 'wishmap_all')]
    public function showAllWishMapcards(WishMapRepository $wishMapRepository,
                                        Request $request, PaginatorInterface $paginator)
    {
        $wishMaps = $wishMapRepository->wishMapsGetNotPrivateAccs();

        $categoryCounter = $wishMapRepository->wishMapsGetCategoryCount();

        $pagination = $paginator->paginate(
            $wishMaps,
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('wish_map/wish_maps_all.twig', [
            'wishmaps' => $pagination,
            'wmCategoryCounter' => $categoryCounter
        ]);
    }

    #[ROUTE('/wishmap/take-a-card/{id}', name: 'wishmap_take_card')]
    public function takeWishMapCard(int $id, WishMapRepository $wishMapRepository, UserActionValidation $validation)
    {
        if ($validation->checkUserWishMapCard($wishMapRepository, $id)) {
            $this->addFlash('info',
                'YOU ARE CANNOT TAKE YOUR OWN CARD!');
            return $this->forward('App\Controller\WishMapController::index');
        }
        $wishMap = clone $wishMapRepository->find($id);
        $oldStartDate = $wishMap->getStartDate();
        $oldFinishDate = $wishMap->getFinishDate();

        $wishMap->setUser($this->getUser());
        $wishMap->setProgress(0);
        $wishMap->setStartDate(new DateTime('now'));
        $wishMap->getComments()->clear();
        $wishMap->setFinishDate($wishMap->countDateDifference($oldStartDate, $oldFinishDate, new DateTime('now')));
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($wishMap);
        $entityManager->flush();
        $this->addFlash('info',
            'Successfully "stolen" :)');
        return $this->redirectToRoute('wish_map');
    }

    #[Route('/wishmap/all/category/{categoryName}', name: 'wish_map_category', methods: ['get', 'post'])]
    public function selectCat(WishMapRepository $wishMapRepository,
                              PaginatorInterface $paginator,
                              Request $request, string $categoryName): Response
    {

        $wishMaps = $wishMapRepository->wishMapsGetNotPrivateAccs_withCategoryName($categoryName);

        $categoryCounter = $wishMapRepository->wishMapsGetCategoryCount();

        $pagination = $paginator->paginate(
            $wishMaps,
            $request->query->getInt('page', 1),
            4
        );

        return $this->render('wish_map/wish_maps_all.twig', [
            'wishmaps' => $pagination,
            'wmCategoryCounter' => $categoryCounter
        ]);
    }


    #[Route('/wishmap/archive', name: 'wish_map_archive', methods: ['get', 'post'])]
    public function archivedWishMapCard(PaginatorInterface $paginator, Request $request,
                                        WishMapRepository $wishMapRepository, UserActionValidation $validation)
    {

        $wishMaps = $wishMapRepository->findBy(['isArchived' => 1,
            'user' => $this->getUser()]);

        $pagination = $paginator->paginate(
            $wishMaps,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('wish_map/archive.html.twig', [
            'wishmaps' => $pagination
        ]);
    }

    #[Route('/wishmap/archive/{id}', name: 'add_archive_wish_map', methods: ['get', 'post'])]
    public function addToArchive(int $id, UserActionValidation $validation,
                                 WishMapRepository $wishMapRepository)
    {
        if (!$validation->checkUserWishMapCard($wishMapRepository, $id)) {
            $this->addFlash('info',
                'YOU ARE CANNOT ADD TO ARCHIVE FOREIGN WISH MAP CARD!');
            return $this->forward('App\Controller\WishMapController::index');
        }

        $wishMap = $wishMapRepository->find($id);
        $wishMap->setIsArchived(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        $this->addFlash('info',
            'Wish map card has been successfully archived!');
        return $this->redirectToRoute('wish_map');

    }

    #[Route('/wishmap/archive/unzip/{id}', name: 'unzip_wish_map', methods: ['get', 'post'])]
    public function unzipWishMap(int $id,
                                 WishMapRepository $wishMapRepository)
    {
        $wishMap = $wishMapRepository->find($id);
        $wishMap->setIsArchived(false);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        $this->addFlash('info',
            'Wish map card has been successfully unzipped!');
        return $this->redirectToRoute('wish_map_archive');

    }

}