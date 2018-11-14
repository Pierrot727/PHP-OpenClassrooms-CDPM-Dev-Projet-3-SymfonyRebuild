<?php

namespace App\Controller;

use App\Entity\Billet;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Manager\CommentManager;
use App\Repository\BilletRepository;
use App\Repository\CommentRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class BlogAccueilController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param AuthorizationCheckerInterface $authChecker
     *
     * @return Response
     */
    public function index(AuthorizationCheckerInterface $authChecker)
    {

        $repoBillets = $this->getDoctrine()->getRepository(Billet::class);
        $billets = $repoBillets->findAll();

        $repoCommentaires = $this->getDoctrine()->getRepository(Comment::class);
        $commentaires = $repoCommentaires->findAll();

        return $this->render('blog_accueil/index.html.twig', [
            'controller_name' => 'BlogAccueilController',
            'billets' => $billets,
            'commentaires' => $commentaires
        ]);

    }

    /**
     * @Route ("/blog/histoire", name="myHistory")
     */
    public function monHistoire()
    {
        return $this->render('blog_monHistoire/index.html.twig');
    }

    /**
     * @Route("/blog/{id}", name="blog_show")
     * @param Request $request
     * @param ObjectManager $manager
     * @param BilletRepository $billetRepository
     *
     * @param CommentRepository $commentRepository
     * @param Billet $billet
     *
     * @return Response
     */
    public function show(Request $request, CommentManager $commentManager, BilletRepository $billetRepository, CommentRepository $commentRepository, Billet $billet)
    {
        $billets = $billetRepository->findAll();
        $commentaires = $commentRepository->findAll();

        $form = $this->createForm(CommentType::class);


        //on traite la request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $commentManager->createComment($form->getData(),$this->getUser()->getName(),$billet);

        }

        return $this->render('blog_accueil/show.html.twig', [
            'billet' => $billet,
            'billets' => $billets,
            'commentaires' => $commentaires,
            'form' => $form->createView()]);
    }

    /**
     * @Route("/blog/signaler/{id}", name="blog_signaler")
     * @param Comment $comment
     * @param CommentManager $commentManager
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function signaler(Comment $comment, CommentManager $commentManager)
    {
        $commentManager->incrementSignalement($comment);


        return $this->redirectToRoute('blog_show', ['id'=> $comment->getBillet()->getId()]);
    }

}
