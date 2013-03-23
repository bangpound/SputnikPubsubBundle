# Working with hubs

Hubs are instances of [HubInterface](https://github.com/sputnik-project/SputnikPubsubBundle/blob/master/Hub/HubInterface.php).
The simpliest way to create a hub is to reuse standard implementation.

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

    mbp:sandbox lakiboy$ php app/console sputnik:pubsub:list-hubs
    blogger - http://pubsubhubbub.appspot.com
    etsy - https://hub.etsy.com
    
## Hub parameters

[Default hub implementation](https://github.com/sputnik-project/SputnikPubsubBundle/blob/master/Hub/Hub.php) is simple value object.

Each hub has:
 - a unique name (e.g. _etsy_, _blogger_, _instagram_),
 - hub URL,
 - hub parameters.

Parameters allow to pass additional fields to hub subscibe/unsubscibe requests. In case of _etsy_ hub for instance,
you need to specify the api key given by Etsy.
