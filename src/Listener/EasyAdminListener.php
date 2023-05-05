<?php

declare(strict_types = 1);

namespace App\Listener;

use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class EasyAdminListener implements EventSubscriberInterface
{
    private SessionInterface $session;
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AfterEntityPersistedEvent::class => ['flashMessageAfterPersist'],
            AfterEntityUpdatedEvent::class => ['flashMessageAfterUpdate'],
            AfterEntityDeletedEvent::class => ['flashMessageAfterDelete'],
        ];
    }

    public function flashMessageAfterPersist(AfterEntityPersistedEvent $event): void
    {
        $this->session->getFlashBag()->add('success', 'Création "' . $event->getEntityInstance() . '" avec succès !');
    }

    public function flashMessageAfterUpdate(AfterEntityUpdatedEvent $event): void
    {
        $this->session->getFlashBag()->add('success', 'Modification "' . $event->getEntityInstance() . '" avec succès !');
    }

    public function flashMessageAfterDelete(AfterEntityDeletedEvent $event): void
    {
        $this->session->getFlashBag()->add('success', 'Suppression "' . $event->getEntityInstance() . '" avec succès !');
    }
}