<?php

namespace App\Controller;

use App\Entity\Billet;
use App\Entity\User;
use App\Entity\Comment;
use App\Form\BilletType;
use App\Repository\BilletRepository;
use App\Repository\CommentRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogAdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_home")
     */
    public function index()
    {
        // Vérification de l'authentification user
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Vous devez être authentifié!');

        $user = $this->getUser();
        $repoBillets = $this->getDoctrine()->getRepository(Billet::class);
        $billets = $repoBillets->findAll();

      //  $repoComments = $this->getDoctrine()->getRepository(CommentRepository::class);
      //  $comments = $repoComments->findAll();

        return $this->render('blog_admin/index.html.twig', [
            'user' => $user,
            'billets' => $billets,
      //      'comments' => $comments
        ]);
    }

    /**
     * @Route("/administration", name="admin_administration")
     * @param BilletRepository $billetRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function administration(BilletRepository $billetRepository, CommentRepository $commentRepository)
    {
        // Vérification de l'authentification admin
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Vous devez être authentifié en tant qu\'administrateur!');

        return $this->render('blog_admin/administration.html.twig', array(
            'billets' => $billetRepository->findAll(),
            'comments' => $commentRepository->findAll(),
        ));
    }

    /**
     * @Route("/blogAdmin/{id}/switch-visibility", name="blog_hide")
     * @param BilletRepository $billetRepository
     *
     * @param Billet $billet
     * @param ObjectManager $manager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function hide(Billet $billet, ObjectManager $manager)
    {
        // Vérification de l'authentification admin
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Vous devez être authentifié en tant qu\'administrateur!');

        $billet->switchVisible();

        $manager->persist($billet);// persister le billet
        $manager->flush();

        return $this->redirectToRoute('admin_administration');
    }

    /**
     * @Route("/billetCreate", name="admin_billetCreate")
     * @param Request $request
     *
     * @param ObjectManager $manager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function billetCreate(Request $request, ObjectManager $manager)
    {

        $billet = new Billet();
        $form = $this->createForm(BilletType::class, $billet);


        //on traite la request
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            /** @var Billet $billet */
            $billet = $form->getData();


            $manager->persist($billet);
            $manager->flush();

            return $this->redirectToRoute('admin_administration');
        }

        return $this->render('blog_admin/billetCreate.html.twig', [
                'form' => $form->createView()

            ]
        );
    }

    /**
     * @Route ("/admin/utilisateurs", name="admin_utilisateurs")
     */
    public function utilisateurs()
    {
        $repo = $this->getDoctrine()->getRepository(User::class);
        $users = $repo->findAll();

        return $this->render('blog_admin/utilisateurs.html.twig', [
            'users' => $users,

        ]);

    }

    /**
     * @Route ("/admin/moderation", name="admin_moderation")
     * @param CommentRepository $commentRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function moderation(CommentRepository $commentRepository, BilletRepository $billetRepository) {


        return $this->render('blog_admin/moderation.html.twig', [
            'comments' => $commentRepository->findAll(),
            'billets' => $billetRepository->findAll()
        ]);
    }

    /**
     * @Route ("/admin/demote", name="admin_demote")
     */

    public function demoteUser()
    {
        $this->demoteUser();

        return $this->redirectToRoute('admin_moderation');
    }
}
