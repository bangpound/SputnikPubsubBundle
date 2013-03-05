<?php

namespace Sputnik\Bundle\PubsubBundle;

final class PubsubEvents
{
    const NOTIFICATION_RECEIVED = 'notification.received';
    const TOPIC_SUBSCRIBE = 'topic.subscribe';
    const TOPIC_UNSUBSCRIBE = 'topic.unsubscribe';
}
