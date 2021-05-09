<?php

namespace App\Controller;

use App\Entity\WishMap;
use App\Form\WishMapType;
use App\Repository\PersonRepository;
use App\Repository\WishMapRepository;
use App\Security\PersonAuthorization;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function wishMapCreate(Request $request, PersonRepository $personRepository, SluggerInterface $slugger)
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
                $originalFilename = pathinfo($wishMapImage->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $wishMapImage->guessExtension();
                try {
                    $wishMapImage->move(
                        $this->getParameter('wishmaps_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new FileException($e);
                }
                $wishMap->setImage($newFilename);
                $wishMap->setPerson($person);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($wishMap);
                $entityManager->flush();
            }
            return $this->redirectToRoute('wish_map');
        }
        return $this->render('wish_map/new_wish_map.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/wishmap', name: 'wish_map', methods: ['get'])]
    public function index(WishMapRepository $wishMapRepository, PersonRepository $personRepository,
                          PaginatorInterface $paginator, Request $request): Response
    {
        $personAuth = new PersonAuthorization($this->security);
        $person = $personAuth->getLoggedPerson($personRepository);

        $categories = $wishMapRepository->findAllDistinctCategories();

        $wishMaps = $wishMapRepository->findByPerson($person);

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

}