<?php

namespace App\EventSubscriber;

use App\Entity\Article;
use App\Entity\Comment;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $slugger;
    private $security;

    public function __construct(SluggerInterface $slugger, Security $security){
        $this->slugger = $slugger;
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return [
        BeforeEntityPersistedEvent::class => ['setArticleDateAndUser'],
        BeforeEntityPersistedEvent::class => ['setCommentDateAndUser'],
        ];
    }

    public function setArticleDateAndUser(BeforeEntityPersistedEvent $event){
        $entity = $event->getEntityInstance();


        if(!($entity instanceof Article)){
            return;
        }

        // Set the information for the entity instance ! Youpi
        $entity->setCreatedAt(new \DateTimeImmutable());
        $user = $this->security->getUser();
        $entity->setIdUser($user);
        $entity->setIsPublic(true);
    }

    public function setCommentDateAndUser(BeforeEntityPersistedEvent $event){
        $entity = $event->getEntityInstance();


        if(!($entity instanceof Comment)){
            return;
        }

        // Set the information for the entity instance ! Youpi
        $entity->setCreatedAt(new \DateTimeImmutable());
        $user = $this->security->getUser();
        $entity->setIdUser($user);
    }
}
?>