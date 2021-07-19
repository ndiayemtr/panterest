<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use  Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class LogoutSubscriber implements EventSubscriberInterface
{
    private $urlGenerator;
    private $flashBag;

    public function __construct(UrlGeneratorInterface $urlGenerator, FlashBagInterface $flashBag){
        $this->urlGenerator = $urlGenerator;
        $this->flashBag = $flashBag;
    }

    public function onLogoutEvent(LogoutEvent $event)
    {
        /*$event->getRequest()->getSession()->getFlashBag()->add(
            'success',
            'Logout out successfully'
        );*/

        $this->flashBag->add('success', 'Logout out successfully' );

        $event->setResponse(new RedirectResponse($this->urlGenerator->generate('app_home')));
        
    }

    public static function getSubscribedEvents()
    {
        return [
            LogoutEvent::class => 'onLogoutEvent',
        ];
    }
}
