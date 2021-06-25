<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(PinRepository $repo): Response
    {
        $pins = $repo->findBy([], ['createdAt' => 'DESC']);
        return $this->render('pins/index.html.twig', compact('pins'));
    }

    /**
     * @Route("pins/{id<[0-9]+>}", priority=10, name="app_pin_show")
     */
    public function show(Pin $pin){
        return $this->render('pins/show.html.twig', compact('pin'));

    }

    /**
     * @Route("pins/create", name="app_pin_create")
     */
    public function create(Request $request, EntityManagerInterface $em) : Response {
        
        $pin = new Pin;

        $form = $this->createFormBuilder($pin)
            ->add('title', null)
            ->add('description', null, [
                'attr' => ['rows' => 10, 'cols' => 50]
            ])
            ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($pin);
                $em->flush();

                return $this->redirectToRoute('app_pin_show', ['id' => $pin->getId()]);
            }

            return $this->render('pins/create.html.twig', ['form' => $form->createView()
        ]);
    }

     /**
     * @Route("pins/{id<[0-9]+>}/edit", priority=11, name="app_pin_edit")
     */
    public function edit(Request $request, Pin $pin, EntityManagerInterface $em) : Response {
        
        $form = $this->createFormBuilder($pin)
            ->add('title', null)
            ->add('description', null, [
                'attr' => ['rows' => 10, 'cols' => 50]
            ])
            ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($pin);
                $em->flush();

                return $this->redirectToRoute('app_pin_show', ['id' => $pin->getId()]);
            }
            
            $form = $form->createView();
            return $this->render('pins/edit.html.twig', compact('form', 'pin')
        );
    }

    

    
}
