<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Repository\CommentsRepository;
use App\Repository\WishMapRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommentsController
 * Controller for work with comments
 * @package App\Controller
 */
class CommentsController extends AbstractController
{

    /**
     * Show comments of chosen wish map card
     * @param PaginatorInterface $paginator for pagination of comments
     * @param Request $request
     * @param int $wishmapId
     * @param WishMapRepository $wishMapRepository
     * @return Response
     */
    #[Route('/wishmap/{wishmapId}/comments', name: 'comments_show')]
    public function showComments(PaginatorInterface $paginator, Request $request, int $wishmapId,
                                 WishMapRepository $wishMapRepository): Response
    {

        $wishMap = $wishMapRepository->findOneBy(['id' => $wishmapId,
            'isArchived' => 0,
        ]); //archived cards doesnt displayed

        $comments = $wishMap->getComments();

        $pagination = $paginator->paginate(
            $comments,
            $request->query->getInt('page', 1),
            5 //maximal value of comment on page
        );


        return $this->render('wish_map/wish_map_comments_page.html.twig', [
            'wishmap' => $wishMap,
            'comments' => $pagination,
        ]);
    }

    /**
     * Add comment to chosen wish map card
     * @param WishMapRepository $wishMapRepository
     * @param int $wishmapId
     * @param Request $request
     * @return Response
     */
    #[Route('/wishmap/{wishmapId}/comments/add', name: 'add_comment')]
    public function addComment(WishMapRepository $wishMapRepository, int $wishmapId, Request $request)
    {
        $wishMap = $wishMapRepository->findOneBy(['id' => $wishmapId,
            'isArchived' => 0,
        ]); //archived cards doesnt displayed
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

    /**
     * Delete comment from wish map card
     * Comment cant delete foreign user
     * @param int $commentId
     * @param int $wishmapId
     * @param CommentsRepository $commentsRepository
     * @param WishMapRepository $wishMapRepository
     * @return RedirectResponse|Response
     */
    #[Route('/wishmap/{wishmapId}/comment/delete/{commentId}', name: 'delete_comment')]
    public function deleteComment(int $commentId, int $wishmapId,
                                  CommentsRepository $commentsRepository, WishMapRepository $wishMapRepository)
    {
        $comment = $commentsRepository->find($commentId);
        $wishMap = $wishMapRepository->find($wishmapId);

        $currentUserId = $this->getUser()->getId(); // current user
        $userId = $wishMap->getUser()->getId(); // user of wish map card
        $commentUserId = $comment->getSendUser()->getId(); // user, who sends comment to wish map card

        // if CURRENT USER not user of wish map card or who send this comment, cannot delete
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