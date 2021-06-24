<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Repository\PinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(PinRepository $repo): Response
    {
        $pins = $repo->findAll();
        return $this->render('pins/index.html.twig', compact('pins'));
    }

    /**
     * @Route("pins/{id<[0-9]+>}", priority=10, name="app_pin_show")
     */
    public function show(Pin $pin){
        return $this->render('pins/show.html.twig', compact('pin'));

    }
}
