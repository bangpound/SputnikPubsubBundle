<?php

namespace Sputnik\Bundle\PubsubBundle\Hub;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Sputnik\Bundle\PubsubBundle\Event\TopicAwareEvent;
use Sputnik\Bundle\PubsubBundle\PubsubEvents;
use Sputnik\Bundle\PubsubBundle\Manipulator\TopicManipulator;

class HubSubscriber implements HubSubscriberInterface
{
    private $manipulator;
    private $provider;
    private $request;
    private $dispatcher;

    /**
     * @param TopicManipulator         $manipulator
     * @param HubProviderInterface     $provider
     * @param HubRequest               $request
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        TopicManipulator $manipulator,
        HubProviderInterface $provider,
        HubRequest $request,
        EventDispatcherInterface $dispatcher
    )
    {
        $this->manipulator = $manipulator;
        $this->provider = $provider;
        $this->request = $request;
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe($topicUrl, $hubName)
    {
        $hub = $this->provider->getHub($hubName);
        $topic = $this->manipulator->create($topicUrl, $hubName);

        $result = $this->request->sendRequest(self::SUBSCRIBE, $topic, $hub);
        if ($result !== false) {
            $result = $topic;
            $this->dispatcher->dispatch(PubsubEvents::TOPIC_SUBSCRIBE, new TopicAwareEvent($topic));
        } else {
            $this->manipulator->remove($topicUrl, $hubName);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function unsubscribe($topicUrl, $hubName)
    {
        $hub = $this->provider->getHub($hubName);
        $topic = $this->manipulator->remove($topicUrl, $hubName);

        $result = $this->request->sendRequest(self::UNSUBSCRIBE, $topic, $hub);
        if ($result !== false) {
            $result = $topic;
            $this->dispatcher->dispatch(PubsubEvents::TOPIC_UNSUBSCRIBE, new TopicAwareEvent($topic));
        }

        return $result;
    }
}
