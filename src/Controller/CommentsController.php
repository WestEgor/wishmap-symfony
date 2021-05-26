<?php


namespace App\Controller;


use App\Entity\Comments;
use App\Repository\CommentsRepository;
use App\Repository\WishMapRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentsController extends AbstractController
{

    #[Route('/wishmap/{wishmapId}/comments', name: 'show_comments')]
    public function showComments(PaginatorInterface $paginator, Request $request, int $wishmapId,
                                 WishMapRepository $wishMapRepository): Response
    {

        $wishMap = $wishMapRepository->findOneBy(['id' => $wishmapId,
            'isArchived' => 0,
        ]);

        $comments = $wishMap->getComments();

        $test = $comments->getValues();
        $id = [];
        foreach ($test as $tests) {
            $id[] = $tests->getSendUser()->getId();
        }


        $pagination = $paginator->paginate(
            $comments,
            $request->query->getInt('page', 1),
            5
        );


        return $this->render('wish_map/wish_map_comments_page.html.twig', [
            'wishmap' => $wishMap,
            'comments' => $pagination,
        ]);
    }

    #[Route('/wishmap/{wishmapId}/comments/add', name: 'add_comment')]
    public function addComment(WishMapRepository $wishMapRepository, int $wishmapId, Request $request)
    {
        $wishMap = $wishMapRepository->findOneBy(['id' => $wishmapId,
            'isArchived' => 0,
        ]);
        $commentBody = $request->query->get('comment_body');

        $comment = new Comments();
        $comment->setComment($commentBody);
        $comment->setSendUser($this->getUser());

        $wishMap->getComments()->add($comment);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();

        $this->addFlash('info',
            'Comment successfully added!');

        $response = new Response();
        return $response->send();
    }


    #[Route('/wishmap/{wishmapId}/comment/delete/{commentId}', name: 'delete_comment')]
    public function deleteComment(int $commentId, int $wishmapId,
                                  CommentsRepository $commentsRepository, WishMapRepository $wishMapRepository)
    {
        $comment = $commentsRepository->find($commentId);
        $wishMap = $wishMapRepository->find($wishmapId);

        $currentUserId = $this->getUser()->getId();
        $userId = $wishMap->getUser()->getId();
        $commentUserId = $comment->getSendUser()->getId();

        if ($userId != $currentUserId && $currentUserId != $commentUserId) {
            $this->addFlash('info',
                'Chunchunmaru');
            return $this->redirect('/wishmap/' . $wishmapId . '/comments');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $wishMap->getComments()->removeElement($comment);
        $entityManager->remove($comment);
        $entityManager->flush();

        $this->addFlash('info',
            'Comment successfully deleted!');

        $response = new Response();
        return $response->send();
    }


}