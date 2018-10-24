<?php

namespace App\Controller;

use App\Entity\Billet;
use App\Entity\Comment;
use App\Repository\BilletRepository;
use App\Repository\CommentRepository;
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
    public function monHistoire() {
        return $this->render('blog_monHistoire/index.html.twig');
    }

    /**
     * @Route("/blog/{id}", name="blog_show")
     * @param BilletRepository $billetRepository
     *
     * @param Billet $billet
     *
     * @return Response
     */
    public function show(BilletRepository $billetRepository, CommentRepository $commentRepository,Billet $billet){
        $billets = $billetRepository->findAll();
        $commentaires = $commentRepository->findAll();
        return $this->render('blog_accueil/show.html.twig', [
            'billet' => $billet,
            'billets' => $billets,
            'commentaires' => $commentaires
        ]);
    }

}
