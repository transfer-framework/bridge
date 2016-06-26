<?php

namespace Bridge\EventSubscriber;

use Bridge\Event\BridgeEvents;
use Bridge\Event\PostActionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ActionActivitySubscriber implements EventSubscriberInterface
{
    /**
     * @var array List of action activities
     */
    private $activity;

    /**
     * @var float Total execution time
     */
    private $totalExecutionTime = 0.0;

    /**
     * @var int Total call count
     */
    private $totalCallCount = 0;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            BridgeEvents::POST_ACTION => 'collectActivity'
        );
    }

    /**
     * Returns collected activity.
     *
     * @return array
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * @return float Total execution time
     */
    public function getTotalExecutionTime()
    {
        return round($this->totalExecutionTime * 1000, 2);
    }

    /**
     * @return int Total call count
     */
    public function getTotalCallCount()
    {
        return $this->totalCallCount;
    }

    /**
     * Collects activity.
     *
     * @param PostActionEvent $event
     */
    public function collectActivity(PostActionEvent $event)
    {
        $this->activity[] = array(
            'action_name' => $event->getAction()->getName(),
            'resource_name' => $event->getAction()->getResource()->getName(),
            'service_name' => $event->getAction()->getResource()->getService()->getName(),
            'arguments' => $event->getArguments(),
            'execution_time' => round($event->getExecutionTime() * 1000, 2),
        );

        $this->totalExecutionTime += $event->getExecutionTime();
        $this->totalCallCount += 1;
    }
}
