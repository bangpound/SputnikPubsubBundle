<?php

namespace Sputnik\Bundle\PubsubBundle\Tests\Handler;

use Sputnik\Bundle\PubsubBundle\PubsubEvents;
use Sputnik\Bundle\PubsubBundle\Event\NotificationReceivedEvent;
use Sputnik\Bundle\PubsubBundle\Tests\Fixtures\SampleTopic;
use Sputnik\Bundle\PubsubBundle\Handler\EventNotificationHandler;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\HeaderBag;

class EventNotificationHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function handlerShouldDelegate()
    {
        $headers = new HeaderBag(array('foo' => 'bar', 'bar' => 'quux'));
        $topic = new SampleTopic();
        $content = 'content';

        $test = $this;
        $notified = false;

        $dispatcher = new EventDispatcher();
        $dispatcher->addListener(PubsubEvents::NOTIFICATION_RECEIVED, function(NotificationReceivedEvent $event) use ($test, $headers, $topic, $content, &$notified) {
            $notified = true;

            $test->assertInstanceOf('Sputnik\\Bundle\\PubsubBundle\\Event\\TopicAwareEvent', $event);
            $test->assertSame($headers, $event->getHeaders());
            $test->assertSame($topic, $event->getTopic());
            $test->assertSame($content, $event->getContent());
        });

        $handler = new EventNotificationHandler($dispatcher);
        $handler->handle($topic, $headers, $content);

        $this->assertTrue($notified);
    }
}
