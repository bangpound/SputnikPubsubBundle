<?php

namespace Sputnik\PubsubBundle;

/**
 * @package SputnikPubsubBundle_Bundle
 */
final class PubsubEvents
{
    const NOTIFICATION_RECEIVED = 'notification.received';
    const TOPIC_SUBSCRIBE = 'topic.subscribe';
    const TOPIC_UNSUBSCRIBE = 'topic.unsubscribe';
}
