<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     * @Security("is_granted('PIN_CREATE')")
     */
    public function index(Request $request, PinRepository $repo, PaginatorInterface $paginator): Response
    {
        $donnees = $repo->findBy([], ['createdAt' => 'DESC']);

        $pins = $paginator->paginate(
            $donnees, 
            $request->query->getInt('page', 1), 
            8
        );

        return $this->render('pins/index.html.twig', compact('pins'));
    }

    /**
     * @Route("pins/{id<[0-9]+>}", priority=10, name="app_pin_show")
     * @Security("is_granted('ROLE_USER') && user.isVerified()")
     */
    public function show(Pin $pin){
        return $this->render('pins/show.html.twig', compact('pin'));

    }

    /**
     * @Route("pins/create", name="app_pin_create")
     * @Security("is_granted('ROLE_USER') && user.isVerified()")
     */
    public function create(Request $request, EntityManagerInterface $em) : Response {
        
        $pin = new Pin;

        $form = $this->createForm(PinType::class, $pin);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $pin->setUser($this->getUser());
                $em->persist($pin);
                $em->flush();

                $this->addFlash('success', 'Pin Crée !');

                return $this->redirectToRoute('app_pin_show', ['id' => $pin->getId()]);
            }

            return $this->render('pins/create.html.twig', ['form' => $form->createView()
        ]);
    }

     /**
     * @Route("pins/{id<[0-9]+>}/edit", priority=11, name="app_pin_edit", methods={"GET", "POST"})
     * @Security("is_granted('PIN_EDIT', pin)")
     */
    public function edit(Request $request, Pin $pin, EntityManagerInterface $em) : Response {
        
        $form = $this->createForm(PinType::class, $pin);
          

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($pin);
                $em->flush();

                $this->addFlash('success', 'Pin modiié !');

                return $this->redirectToRoute('app_pin_show', ['id' => $pin->getId()]);
            }

            $form = $form->createView();
            return $this->render('pins/edit.html.twig', compact('form', 'pin')
        );
    }

    /**
     * @Route("pins/{id<[0-9]+>}/delete", priority=12, name="app_pin_delete", methods={"GET"})
     * @Security("is_granted('ROLE_USER') && user.isVerified() && pin.getUser() == user")
     * @Security("is_granted('PIN_DELETE', pin)")
     */
    public function delete(Pin $pin, EntityManagerInterface $em) : Response {

        //denyAccessUnlessGranted() Peut Remplacer @Security("is_granted('PIN_DELETE', pin)")
        $this->denyAccessUnlessGranted('PIN_DELETE', $pin);

        $em->remove($pin);
        $em->flush();

        $this->addFlash('info', 'Pin supprimé');

        return $this->redirectToRoute('app_home');

    }

    

    
}
