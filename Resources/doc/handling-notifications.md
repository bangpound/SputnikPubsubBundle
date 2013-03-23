# Handling notifications

The whole point of _PubSubHubbub_ protocol is to get notified by hub, when certain topic was updated. Whenever a publisher
updates certain topic (e.g. writes a blogpost), the hub pushes the update to all subscribers.

## Callback URL

Each subscription created by SputnikPubsubBundle has a unique callback URL registered within a relevant hub.

Consider below subscription:

```bash
$ php app/console sputnik:pubsub:subscribe http://my-topic-url blogger --context-host=sputnik
Subscription created: 829e61439c6e9acf8438f93b040f0da4f0647ec4
```

The relevant callback URL for this case will be 

    http://sputnik/app_dev.php/push/829e61439c6e9acf8438f93b040f0da4f0647ec4
    
Host name was taken from _parameters.yaml_ (in CLI mode Symfony uses _localhost_ by default):

```yaml
router.request_context.host: sputnik
```

Route prefix was defined in _routing.yaml_ (see [installation](https://github.com/sputnik-project/SputnikPubsubBundle/blob/master/Resources/doc/installation.md)):

```yaml
sputnik_pubsub_callback:
    resource: "@SputnikPubsubBundle/Resources/config/routing/callback.xml"
    prefix:   /push
```

## Register notification listener

In order to get notified you need to create a service listening to `notification.received` event. Consider the following
example:

```php
namespace Acme\DemoBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Sputnik\Bundle\PubsubBundle\PubsubEvents;
use Sputnik\Bundle\PubsubBundle\Event\NotificationReceivedEvent;

class MyListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(PubsubEvents::NOTIFICATION_RECEIVED => 'onNotificationReceived');
    }

    public function onNotificationReceived(NotificationReceivedEvent $event)
    {
        $content = $event->getContent();

        // do something with $content
    }
}
```

Next register it in dependency injection container:

```yaml
acme_demo.my_listener: 
    class: Acme\DemoBundle\EventListener\MyListener
    tags:
        - { name: sputnik_pubsub.event_subscriber }
```

## While in development

As stated before, when in development you will not be notified by any real hub. Subscriptions are confirmed
by test hub, hence notifications will not arrive. However, there is a console command allowing to post arbitrary
content to existing subscriptions.

```bash
$ php app/console sputnik:pubsub:push http://my-topic-url blogger composer.json
```

In above example we "pushed" the contents of _composer.json_ to `http://my-topic-url` from _blogger_ hub.
In a real world Blogger would send XML (not JSON), but you get the idea. 

In that way you can "emulate" hub notification for topic updates and test your listeners.

## Debug

There is a [LogListener](https://github.com/sputnik-project/SputnikPubsubBundle/blob/master/EventListener/LogListener.php)
implemented. It logs notification information. This is handy if you want to review what kind of notification arrive from hub.
