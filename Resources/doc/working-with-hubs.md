# Working with hubs

Hubs are instances of [HubInterface](https://github.com/sputnik-project/SputnikPubsubBundle/blob/master/Hub/HubInterface.php).
The simpliest way to create a hub is to reuse [standard implementation](https://github.com/sputnik-project/SputnikPubsubBundle/blob/master/Hub/Hub.php),
which is a simple value object.

## Registering a new hub

Add the following code to your config:

```yaml
services:
    hub.blogger:
        class: Sputnik\Bundle\PubsubBundle\Hub\Hub
        arguments: [ blogger, http://pubsubhubbub.appspot.com ]
        tags:
            - { name: sputnik_pubsub.hub }
    hub.etsy:
        class: Sputnik\Bundle\PubsubBundle\Hub\Hub
        arguments: [ etsy, https://hub.etsy.com, { api_key: %etsy.api_key% } ]
        tags:
            - { name: sputnik_pubsub.hub }

```

Above will register two hubs with names _blogger_ and _etsy_ within [HubProvider](https://github.com/sputnik-project/SputnikPubsubBundle/blob/master/Hub/HubProviderInterface.php).
You can output the list of hubs known to hub provider by running `php app/console sputnik:pubsub:list-hubs`, e.g.:

```bash
$ php app/console sputnik:pubsub:list-hubs
blogger - http://pubsubhubbub.appspot.com
etsy - https://hub.etsy.com
```

## Hub attributes

Each hub has:
 - a unique name (e.g. _etsy_, _blogger_, _instagram_),
 - hub URL,
 - hub parameters.

Parameters allow to pass additional fields to hub subscibe/unsubscibe requests. In case of e.g. _etsy_ hub,
you need to specify the api key given by Etsy.

## While in development

Obviously, while in development you don't want to send subscribe/unsubscribe requests to real hubs. Hubs will try
to ping your server back to approve your request (this bundle does not support asynchronious subscriptions).
To mitigate this problem there is a config option called `sputnik_pubsub.live_hub`, which is
set to _false_ by default. What that means that (un)subscribe requests will be routed to a test (local) hub instead.

In order to ping live hubs you need to explicitly allow this:

```yaml
sputnik_pubsub:
    live_hub: true
```

Test hub will most likely confirm your request returning 204 HTTP code.

## Subscribe/unsubscibe

Let's try to subscribe to _blogger_ hub in develpoment mode via console:

```bash
$ php app/console sputnik:pubsub:subscribe http://my-topic-url blogger
Subscription created: 829e61439c6e9acf8438f93b040f0da4f0647ec4
```

Above created new _blogger_ subscription for topic URL `http://my-topic-url`. Obviously such topic URL does not exist
on real hub, but we are in development mode, hence subscription was successfully confirmed by test hub.

To unsubscribe, please, run:

```bash
$ php app/console sputnik:pubsub:subscribe -u http://my-topic-url blogger
Subscription removed: 829e61439c6e9acf8438f93b040f0da4f0647ec4
```

As noted in [installation instructions](https://github.com/sputnik-project/SputnikPubsubBundle/blob/master/Resources/doc/installation.md),
in "console mode" the router does not know much about current host, protocol or base URL. Please, refer to installation
to find out how to specify default host, protocol or base URL for router in CLI.

You can also supply router context adding relevant options:

```bash
$ php app/console sputnik:pubsub:subscribe http://my-another-topic-url blogger \
    --context-host=sputnik \
    --context-scheme=https \
    --context-base-url=/app_dev.php
```

#### Using a service

You can use `sputnik_pubsub.hub_subscriber` to (un)subcribe within your code, e.g.:

```php
// subscribe
$topic = $this->get('sputnik_pubsub.hub_subscriber')->subscribe('http://my-topic-url', 'blogger');
// unsubscribe
$topic = $this->get('sputnik_pubsub.hub_subscriber')->unsubscribe('http://my-topic-url', 'blogger');
```

The result is the [TopicInterface](https://github.com/sputnik-project/SputnikPubsubBundle/blob/master/Model/TopicInterface.php) instance.

## Debug

SputnikPubsubBundle tries to log all possible information related to hub/topic requests, responses, etc. In search
of errors it is quite helpful to tail the logs and see what's going on:

```bash
$ tail -f app/logs/pubsub.dev.log
```

## Next

Reed next [how to handle notifications](https://github.com/sputnik-project/SputnikPubsubBundle/blob/master/Resources/doc/handling-notifications.md).
