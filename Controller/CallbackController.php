<?php

namespace Sputnik\Bundle\PubsubBundle\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sputnik\Bundle\PubsubBundle\Model\TopicInterface;
use Sputnik\Bundle\PubsubBundle\Model\TopicManagerInterface;
use Sputnik\Bundle\PubsubBundle\Handler\NotificationHandlerInterface;
use Sputnik\Bundle\PubsubBundle\Hub\HubSubscriberInterface;

class CallbackController
{
    private $manager;
    private $logger;
    private $handler;

    /**
     * @param TopicManagerInterface        $manager
     * @param LoggerInterface              $logger
     * @param NotificationHandlerInterface $handler
     */
    public function __construct(TopicManagerInterface $manager, LoggerInterface $logger, NotificationHandlerInterface $handler)
    {
        $this->manager = $manager;
        $this->logger  = $logger;
        $this->handler = $handler;
    }

    /**
     * @param Request $request
     * @param string  $id
     *
     * @return Response
     */
    public function process(Request $request, $id)
    {
        $result = $this->manager->findTopicById($id);
        $mode = $request->query->get('hub_mode');
        $message = '';

        if ($mode === HubSubscriberInterface::SUBSCRIBE || $mode === HubSubscriberInterface::UNSUBSCRIBE) {
            if ($result->isEmpty() && $mode === HubSubscriberInterface::SUBSCRIBE) {
                $this->logger->warning(sprintf('pubsub callback: illegal %s request for topic %s', $mode, $id));
            } else {
                $this->logger->info(sprintf('pubsub callback: %s request for topic %s [%s]', $mode, $id, (!$result->isEmpty() ? $result->get() : 'deleted')));
                $message = $request->query->get('hub_challenge');
            }

        } elseif ($result->isEmpty()) {
            $this->logger->warning(sprintf('pubsub callback: error processing notification - topic %s not found', $id));

        } elseif (!$this->isValidNotification($request, $result->get())) {
            $this->logger->error(sprintf('pubsub callback: security check failed for topic %s [%s]', $id, (string) $result->get()));

        } else {
            $topic = $result->get();

            $this->logger->info(sprintf('pubsub callback: notification received for topic %s [%s]', $id, (string) $topic));

            try {
                $this->handler->handle($topic, $request->headers, $request->getContent());
            } catch (\Exception $e) {
                $msg = 'pubsub callback: caught %s while handling notification for topic %s [%s]: %s';
                $this->logger->error(sprintf($msg, get_class($e), $topic->getId(), (string) $topic, $e->getMessage()));
            }
        }

        // Always return 200!
        return new Response($message, 200);
    }

    /**
     * @param Request        $request
     * @param TopicInterface $topic
     *
     * @return boolean
     */
    private function isValidNotification(Request $request, TopicInterface $topic)
    {
        $secret = $topic->getTopicSecret();
        if (!$secret) {
            return false;
        }

        $matches = array();
        if (preg_match('/^sha1=(.+)/', $request->headers->get('X-Hub-Signature'), $matches)) {
            $result = hash_hmac('sha1', $request->getContent(), $secret) === $matches[1];
        } else {
            $result = false;
        }

        return $result;
    }
}
