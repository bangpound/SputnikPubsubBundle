# Installation

The installation consist of 3 steps:

 - bundle setup,
 - registering of _pubsub_ logging channel,
 - storage driver setup and schema creation.

## Bundle setup

This part is straight forward and is absolutely the same as for any other Symfony bundle.

#### Add relevant require statement to composer.json, e.g.

    "sputnik/pubsub-bundle": "~0.1"
    
#### Register bundle in the kernel:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Sputnik\Bundle\PubsubBundle\SputnikPubsubBundle(),
    );
}
```

#### Update your routing files

```yaml
sputnik_pubsub_hub:
    resource: "@SputnikPubsubBundle/Resources/config/routing/hub.xml"
    prefix:   /hub

sputnik_pubsub_callback:
    resource: "@SputnikPubsubBundle/Resources/config/routing/callback.xml"
    prefix:   /push
```

You can add any prefixes you like, you can always check the end result with `php app/console router:debug`.

## Registering of _pubsub_ logging channel

In _config_dev.yml_ and/or in _config_prod.yml_ add below:

```yaml
monolog:
    handlers:
        main:
            type:  stream
            path:  %kernel.logs_dir%/%kernel.environment%.log
            level: debug
            channels: [ !pubsub ]
        pubsub:
            type:     stream
            path:     "%kernel.logs_dir%/pubsub.%kernel.environment%.log"
            level:    debug
            channels: pubsub
```

Above ensures you have the _pubsub_ logging channel setup. All logging done by SputnkPubsubBudle uses that channel,
therefore it must be registered. Also note the exclusion of _pubsub_ channel from _main_. This is optional and prevents
_pubsub_ messages to go into the main stream.
