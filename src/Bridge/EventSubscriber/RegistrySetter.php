<?php

namespace Bridge\EventSubscriber;

use Bridge\Event\BridgeEvents;
use Bridge\Event\PreActionEvent;
use Bridge\Registry;
use Bridge\RegistryAwareInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RegistrySetter implements EventSubscriberInterface
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            BridgeEvents::PRE_ACTION => 'preAction',
        );
    }

    /**
     * @param PreActionEvent $event
     */
    public function preAction(PreActionEvent $event)
    {
        if ($event->getAction() instanceof RegistryAwareInterface) {
            $event->getAction()->setRegistry($this->registry);
        }
    }
}
