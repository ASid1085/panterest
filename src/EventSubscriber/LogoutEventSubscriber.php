<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutEventSubscriber implements EventSubscriberInterface
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator) {
        $this->urlGenerator = $urlGenerator;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LogoutEvent::class => 'onLogoutEvent',
        ];
    }

    public function onLogoutEvent(LogoutEvent $event): void
    {
        //dd($event)
        $event->getRequest()->getSession()->getFlashBag()->add(
            'success',
            'Logged out successfully !'
        );

        $event->setResponse(new RedirectResponse(
            $this->urlGenerator->generate('app_home')
        ));
    }

    
}
