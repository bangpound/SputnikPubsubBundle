<?php

namespace Sputnik\Bundle\PubsubBundle\Tests\EventListener;

use Sputnik\Bundle\PubsubBundle\Event\NotificationReceivedEvent;
use Sputnik\Bundle\PubsubBundle\PubsubEvents;
use Sputnik\Bundle\PubsubBundle\Tests\Fixtures\SampleTopic;
use Sputnik\Bundle\PubsubBundle\EventListener\NotificationLogger;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\EventDispatcher\EventDispatcher;

class NotificationLoggerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function listenerIsNotified()
    {
        $topic = new SampleTopic();
        $headers = new HeaderBag(array('foo' => 'bar', 'baz' => 'qux'));
        $content = 'content';

        $logger = $this->getMock('Psr\\Log\\LoggerInterface');
        $listener = new NotificationLogger($logger);

        $message = sprintf("Pubsub notification:\nTopic: %s [%s]\nHeaders: \n%s\nContent:\n%s", $topic, $topic->getId(), $this->flattenArray($headers), $content);

        $logger
            ->expects($this->once())
            ->method('debug')
            ->with($this->equalTo($message))
        ;

        $dispatcher = new EventDispatcher();
        $dispatcher->addListener(PubsubEvents::NOTIFICATION_RECEIVED, array($listener, 'onNotificationReceived'));
        $dispatcher->dispatch(PubsubEvents::NOTIFICATION_RECEIVED, new NotificationReceivedEvent($topic, $headers, $content));
    }

    /**
     * @param array|\Iterator $array
     */
    private function flattenArray($array)
    {
        $result = '';
        foreach ($array as $k => $v) {
            $result .= $result ? "\n" : '';
            $result .= "$k => {$v[0]}";
        }

        return $result;
    }
}
