<?php

namespace App\EventSubscriber;

use App\Entity\DataB;
use App\Entity\Usr;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use DateTime;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    
    private $encode;
    
    public function __construct(UserPasswordEncoderInterface $encode)
    {
        $this->encode = $encode;
    }
    
    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setDataBSlug'],
            BeforeEntityPersistedEvent::class => ['encodePassword'],
        ];
    }

    public function setDataBSlug(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof DataB)) {
            return;
        }

        $entity->setDatac(new DateTime('NOW'));
    }


    public function encodePassword(BeforeEntityPersistedEvent $event)
    {
         
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Usr)) {
            return;
        }

        $usr_pwd = $entity->getPassword();
        $entity->setPassword(
            $this->encode->encodePassword(
                $entity,
                $usr_pwd
            )
        );
    }
}