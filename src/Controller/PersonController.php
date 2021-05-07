<?php


namespace App\Controller;


use App\Entity\Person;
use App\Form\PersonType;
use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class PersonController extends AbstractController
{

    private Security $security;

    /**
     * PersonController constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    #[Route('/profile/create', name: 'create_profile')]
    public function createProfile(Request $request): Response
    {
        $user = $this->security->getUser();
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $person = $form->getData();
            $person->setUser($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($person);
            $entityManager->flush();
            return $this->redirectToRoute('wish_map');
        }

        return $this->render('person/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/profile/update', name: 'profile_update')]
    public function profileUpdate(Request $request, PersonRepository $personRepository): Response
    {
        $user = $this->security->getUser();
        $person = $personRepository->findOneBy(
            ['user' => $user->getId()]
        );
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('wish_map');
        }

        return $this->render('person/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/profile/check', name: 'check_profile')]
    public function checkProfileExistence(PersonRepository $personRepository): Response
    {
        $user = $this->security->getUser();
        $person = $personRepository->findOneBy(
            ['user' => $user->getId()]
        );
        if ($person === null) {
            return $this->redirectToRoute('create_profile');
        }
        return $this->redirectToRoute('wish_map');

    }
}