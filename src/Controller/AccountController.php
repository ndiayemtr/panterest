<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Form\ChangePasswordFormType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="app_account", methods="GET")
     * @IsGranted("ROLE_USER")
     */
    public function show(): Response
    {
        return $this->render('account/show.html.twig');
    }

    /**
     * @Route("/account/edit", name="app_account_edit", methods={"GET", "POST"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function edit(Request $request, EntityManagerInterface $em): Response {
        $user = $this->getUser();
        $form = $this->createForm(UserFormType::class, $user);
          

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Account update successfully !');

            return $this->redirectToRoute('app_account');
        }

        $form = $form->createView();
        return $this->render('account/edit.html.twig', compact('form'));

    }

    /**
     * @Route("/account/change-password", name="app_account_change_password", methods={"GET", "POST"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function changePassword(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $this->getUser();       

        $form = $this->createForm(ChangePasswordFormType::class, null, [
            'current_password_is_required' => true
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword($user, $form['plainPassword']->getData() )
            );
            
            $em->flush();

            $this->addFlash('success', 'password update successfully !');

            return $this->redirectToRoute('app_account');
        }

        $form = $form->createView();

        return $this->render('account/change_password.html.twig', compact('form'));
    }


}
