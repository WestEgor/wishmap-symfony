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
use function DeepCopy\deep_copy;

use DateTime;

/**
 * Class WishMapController
 * Controller to work with wish map cards
 * @package App\Controller
 */
class WishMapController extends AbstractController
{
    /**
     * Home page of users
     * @param WishMapRepository $wishMapRepository
     * @param UserRepository $userRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/wishmap', name: 'wish_map', methods: ['get', 'post'])]
    public function index(WishMapRepository $wishMapRepository, UserRepository $userRepository,
                          PaginatorInterface $paginator, Request $request): Response
    {
        //if request contain key user we need to find another wish map page
        $nickname = $request->query->get('user');
        if ($nickname != null) {
            $user = $userRepository->findOneBy(['nickname' => $nickname,
                'isPrivate' => 0]); //private account doesnt display
            if ($user == null) {
                $this->addFlash('info',
                    'USER DOESNT EXIST OR IT`S PRIVATE ACCOUNT!');
                return $this->forward('App\Controller\WishMapController::index');
            }
            /* $wishMaps = $wishMapRepository->findBy(['user' => $user,
                 'isArchived' => 0]); // archive cards doesnt display*/

            $wishMaps = $wishMapRepository->wishMapsGetAccByNickname($user->getNickname());
            $categoryCounter = $wishMapRepository->wishMapsGetUserCategoryCount($nickname);

            $pagination = $paginator->paginate(
                $wishMaps,
                $request->query->getInt('page', 1),
                4 //maximal value of wish map cards on page
            );

            return $this->render('wish_map/wish_maps_all.twig', [
                'wishmaps' => $pagination,
                'wmCategoryCounter' => $categoryCounter
            ]);

        }


        // if no requests we return wish map of current user
        $user = $this->getUser();
        $wishMaps = $wishMapRepository->findBy(['user' => $user,
            'isArchived' => 0]); //archived cards dont display


        $pagination = $paginator->paginate(
            $wishMaps,
            $request->query->getInt('page', 1),
            4 //maximal value of wish map cards on page
        );

        return $this->render('wish_map/index.html.twig', [
            'wishmaps' => $pagination,
            'user' => $user
        ]);
    }

    /**
     * Creating wish map card
     * @param Request $request
     * @param ImageUploader $imageUploader
     * @return RedirectResponse|Response
     */
    #[Route('/wishmap/create', name: 'wish_map_create')]
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

    /**
     * Updating wish map card
     * @param int $id
     * @param ImageUploader $imageUploader
     * @param UserActionValidation $validation check is this user wish map card or not
     * @param WishMapRepository $wishMapRepository
     * @param Request $request
     * @return RedirectResponse|Response
     */
    #[Route('/wishmap/update/{id}', name: 'wish_map_update')]
    public function wishMapUpdate(int $id, ImageUploader $imageUploader, UserActionValidation $validation,
                                  WishMapRepository $wishMapRepository, Request $request): RedirectResponse|Response
    {
        if (!$validation->checkUserWishMapCard($wishMapRepository, $id)) { //we cannot update foreign wish map card
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

    /**
     * Deleting wish map card
     * @param int $id
     * @param WishMapRepository $wishMapRepository
     * @param UserActionValidation $validation check is this user wish map card or not
     * @return Response
     */
    #[ROUTE('/wishmap/delete/{id}', name: 'wish_map_delete', methods: ['delete', 'get'])]
    public function deleteWishMap(int $id, WishMapRepository $wishMapRepository, UserActionValidation $validation)
    {
        if (!$validation->checkUserWishMapCard($wishMapRepository, $id)) { //we cannot delete foreign wish map card
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

    /**
     * Show all wish map cards
     * Users must be no private
     * Wish map cards must be no archived
     * @param WishMapRepository $wishMapRepository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    #[ROUTE('/wishmap/all', name: 'wish_map_all')]
    public function showAllWishMapcards(WishMapRepository $wishMapRepository,
                                        Request $request, PaginatorInterface $paginator)
    {
        $wishMaps = $wishMapRepository->wishMapsGetNotPrivateAccs();

        $categoryCounter = $wishMapRepository->wishMapsGetCategoryCount();

        $pagination = $paginator->paginate(
            $wishMaps,
            $request->query->getInt('page', 1),
            4 //maximal value of wish map cards on page
        );

        return $this->render('wish_map/wish_maps_all.twig', [
            'wishmaps' => $pagination,
            'wmCategoryCounter' => $categoryCounter
        ]);
    }

    /**
     * We can add foreign wish map card to us
     * We cannot add our wish map card to us
     * Progress will change to 0
     * "Stolen" card doesnt delete from user which one take in
     * @param int $id
     * @param WishMapRepository $wishMapRepository
     * @param UserActionValidation $validation
     * @return RedirectResponse|Response
     */
    #[ROUTE('/wishmap/take-a-card/{id}', name: 'wish_map_take_card')]
    public function takeWishMapCard(int $id, WishMapRepository $wishMapRepository, UserActionValidation $validation)
    {
        //cannot add our wish map card to us
        if ($validation->checkUserWishMapCard($wishMapRepository, $id)) {
            $this->addFlash('info',
                'YOU ARE CANNOT TAKE YOUR OWN CARD!');
            return $this->forward('App\Controller\WishMapController::index');
        }

        //we are adding to us, without deleting


        $wishMap = clone $wishMapRepository->find($id);
        //get date from this wish map cards, to change them further
        $oldStartDate = $wishMap->getStartDate();
        $oldFinishDate = $wishMap->getFinishDate();

        $wishMap->setUser($this->getUser());
        $wishMap->setProgress(0);
        $wishMap->setStartDate(new DateTime('now'));
        //to set finish date we need first to get difference of old wish map cards
        //then add to new start date this difference
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($wishMap);
        $entityManager->flush();
        $this->addFlash('info',
            'Successfully "stolen" :)');
        return $this->redirectToRoute('wish_map');
    }

    /**
     * Returning all wish map cards by chosen category
     * @param WishMapRepository $wishMapRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @param string $categoryName
     * @return Response
     */
    #[Route('/wishmap/all/category/{categoryName}', name: 'wish_map_category', methods: ['get', 'post'])]
    public function selectCat(WishMapRepository $wishMapRepository,
                              PaginatorInterface $paginator,
                              Request $request, string $categoryName): Response
    {

        //get wish map by category name
        $wishMaps = $wishMapRepository->wishMapsGetNotPrivateAccs_withCategoryName($categoryName);

        $categoryCounter = $wishMapRepository->wishMapsGetCategoryCount();

        $pagination = $paginator->paginate(
            $wishMaps,
            $request->query->getInt('page', 1),
            4 //maximal value wish map per page
        );

        return $this->render('wish_map/wish_maps_all.twig', [
            'wishmaps' => $pagination,
            'wmCategoryCounter' => $categoryCounter
        ]);
    }

    /**
     * Display archive of wish map cards of current user
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @param WishMapRepository $wishMapRepository
     * @param UserActionValidation $validation
     * @return Response
     */
    #[Route('/wishmap/archive', name: 'wish_map_archive', methods: ['get', 'post'])]
    public function archivedWishMapCard(PaginatorInterface $paginator, Request $request,
                                        WishMapRepository $wishMapRepository, UserActionValidation $validation)
    {

        $wishMaps = $wishMapRepository->findBy(['isArchived' => 1,
            'user' => $this->getUser()]); // only archived cards

        $pagination = $paginator->paginate(
            $wishMaps,
            $request->query->getInt('page', 1),
            6 //maximal value of wish map cards per page
        );

        return $this->render('wish_map/archive.html.twig', [
            'wishmaps' => $pagination
        ]);
    }

    /**
     * Adding wish map card to archive
     * @param int $id
     * @param UserActionValidation $validation
     * @param WishMapRepository $wishMapRepository
     * @return RedirectResponse|Response
     */
    #[Route('/wishmap/archive/{id}', name: 'wish_map_add_archive', methods: ['get', 'post'])]
    public function addToArchive(int $id, UserActionValidation $validation,
                                 WishMapRepository $wishMapRepository)
    {
        //we cannot archive foreign user wish map card
        if (!$validation->checkUserWishMapCard($wishMapRepository, $id)) {
            $this->addFlash('info',
                'YOU ARE CANNOT ADD TO ARCHIVE FOREIGN WISH MAP CARD!');
            return $this->forward('App\Controller\WishMapController::index');
        }
        $wishMap = $wishMapRepository->find($id);

        //if wish map card is already archived we cannot archive it one more
        if ($wishMap->isArchived() === true) {
            $this->addFlash('info',
                'YOU ARE CANNOT ADD TO ARCHIVE ARCHIVED WISH MAP CARD!');
            return $this->forward('App\Controller\WishMapController::index');
        }

        $wishMap->setIsArchived(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        $this->addFlash('info',
            'Wish map card has been successfully archived!');
        return $this->redirectToRoute('wish_map');

    }

    /**
     * Unzip of wish map card
     * @param int $id
     * @param UserActionValidation $validation
     * @param WishMapRepository $wishMapRepository
     * @return RedirectResponse|Response
     */
    #[Route('/wishmap/archive/unzip/{id}', name: 'wish_map_unzip', methods: ['get', 'post'])]
    public function unzipWishMap(int $id, UserActionValidation $validation,
                                 WishMapRepository $wishMapRepository)
    {
        //we cannot unzip foreign user wish map card
        if (!$validation->checkUserWishMapCard($wishMapRepository, $id)) {
            $this->addFlash('info',
                'YOU ARE CANNOT UNZIP FOREIGN WISH MAP CARD!');
            return $this->forward('App\Controller\archivedWishMapCard::index');
        }

        $wishMap = $wishMapRepository->find($id);

        //if wish map card isn`t archived we cannot unzip it one more
        if ($wishMap->isArchived() === false) {
            $this->addFlash('info',
                'YOU ARE CANNOT UNZIP VALID WISH MAP CARD!');
            return $this->forward('App\Controller\WishMapController::index');
        }

        $wishMap->setIsArchived(false);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        $this->addFlash('info',
            'Wish map card has been successfully unzipped!');
        return $this->redirectToRoute('wish_map_archive');

    }

}