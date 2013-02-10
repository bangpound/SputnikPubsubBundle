<?php

namespace Sputnik\PubsubBundle\Hub;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Sputnik\PubsubBundle\Generator\TopicGeneratorInterface;
use Sputnik\PubsubBundle\Model\TopicManagerInterface;
use Sputnik\PubsubBundle\Model\TopicInterface;
use Sputnik\PubsubBundle\Event\TopicAwareEvent;
use Sputnik\PubsubBundle\PubsubEvents;

/**
 * @package SputnikPubsubBundle_Hub
 */
class HubSubscriber implements HubSubscriberInterface
{
    private $manager;
    private $generator;
    private $provider;
    private $request;
    private $dispatcher;

    /**
     * @param TopicManagerInterface    $manager
     * @param TopicGeneratorInterface  $generator
     * @param HubProviderInterface     $provider
     * @param HubRequest               $request
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        TopicManagerInterface $manager,
        TopicGeneratorInterface $generator,
        HubProviderInterface $provider,
        HubRequest $request,
        EventDispatcherInterface $dispatcher
    )
    {
        $this->manager    = $manager;
        $this->generator  = $generator;
        $this->provider   = $provider;
        $this->request    = $request;
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe($topicUrl, $hubName)
    {
        if (!filter_var($topicUrl, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException(sprintf('hub subscribe: invalid URL: %s', $topicUrl));
        }

        $hub = $this->provider->getHub($hubName);

        $topic = $this->manager->create();
        $topic->setHubName($hub->getName());
        $topic->setTopicUrl($topicUrl);

        $id = $this->generator->generateTopicId($topic);

        $current = $this->manager->find($id);
        if ($current instanceof TopicInterface) {
            $topic = $current;
        }

        $topic->setVerified(false);
        $topic->setId($id);
        $topic->setTopicSecret($this->generator->generateTopicSecret($topic));

        $this->manager->persist($topic);
        $this->manager->flush();

        $result = $this->request->sendRequest(self::SUBSCRIBE, $topic, $hub);
        if ($result !== false) {
            $result = $topic;
            $this->dispatcher->dispatch(PubsubEvents::TOPIC_SUBSCRIBE, new TopicAwareEvent($topic));
        } else {
            $this->manager->remove($topic);
            $this->manager->flush();
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function unsubscribe($topicUrl, $hubName)
    {
        $topic = $this->manager->findOneBy(array('topicUrl' => $topicUrl, 'hubName' => $hubName));
        if (!$topic instanceof TopicInterface) {
            throw new \InvalidArgumentException(sprintf('hub unsubscibe: topic not found - %s:%s', $hubName, $topicUrl));
        }

        $hub = $this->provider->getHub($topic->getHubName());
        $result = $this->request->sendRequest(self::UNSUBSCRIBE, $topic, $hub);
        if ($result !== false) {
            $result = $topic;
            $this->dispatcher->dispatch(PubsubEvents::TOPIC_UNSUBSCRIBE, new TopicAwareEvent($topic));
            $this->manager->remove($topic);
            $this->manager->flush();
        }

        return $result;
    }
}
