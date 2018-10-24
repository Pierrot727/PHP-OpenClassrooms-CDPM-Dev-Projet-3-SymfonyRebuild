<?php

namespace App\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SecurityController extends Controller
{
    /**
     * @route ("/inscription", name="security_registration")
     */
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {

        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        //analyse la request
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            /** @var User $user */
            $user = $form->getData();

            $hash = $encoder->encodePassword(($user), $user->getPassword());
            $user->setPassword($hash);


            // Concaténation            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));


            $manager->persist($user);// persister le user
            $manager->flush();//enregistrer en bdd

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @route("/update", name="security_update")
     * @param Request $request
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {

        $user = $this->getUser();
        $form = $this->createForm(RegistrationType::class, $user);

        //analyse la request
        $form->handleRequest($request);

        if ($form->isSubmitted() ) {
            /** @var User $user */
            $user = $form->getData();
            $hash = $encoder->encodePassword(($user), $user->getPassword());
            $user->setPassword($hash);


            $manager->persist($user);// persister le user
            $manager->flush();//enregistrer en bdd

            return $this->redirectToRoute('admin_home');
        }

        return $this->render('security/update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @route("/connexion", name="security_login")
     * @param AuthorizationCheckerInterface $authChecker
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthorizationCheckerInterface $authChecker)
    {
        if (false === $authChecker->isGranted('ROLE_USER')) {
            return $this->render('security/login.html.twig');
        }

        return $this->redirectToRoute('admin_home');



    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout()
    {

    }
}
