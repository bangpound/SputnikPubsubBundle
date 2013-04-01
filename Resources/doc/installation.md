# Installation

The installation includes 4 steps:

 - bundle setup,
 - registering of _pubsub_ logging channel,
 - defining route context for console commands (optional),
 - storage driver setup and schema creation.

## Bundle setup

This part is straight forward and is the same as for any other Symfony bundle.

#### Add relevant require statement to composer.json, e.g.

```json
"sputnik/pubsub-bundle": "~0.1"
```
    
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

## Defining route context (optional)

This bundle provides a set of console commands to deal with subscriptions. In "console mode" Symfony knows nothing about
your host, protocol or base URL when generating the routes with router.

As of Symfony 2.2 there are 3 parameters you can define to mitigate this:

 - router.request_context.host
 - router.request_context.scheme
 - router.request_context.base_url

To set host name to _foo_ for all console generated URLs, you can add the following to the _parameters.yml_:

```yaml
router.request_context.host: foo
```
   
Now URLs generated in console will have this hostname defined. Please, read the following cookbook entry
for more information - http://symfony.com/doc/current/cookbook/console/sending_emails.html

## Storage driver setup

To setup storage driver, please, use `sputnik_pubsub.driver` configuration option, e.g.

```yaml
sputnik_pubsub:
    driver: doctrine_mongo # allowed values: doctrine (default), doctrine_mongo, mandango
```
        
__Note:__ Unlike most distributed Symfony bundles, SputnikPubsubBundle provides a complete Entities/Documents for you.
That means you don't have to register/extend your own models/entities. This approach is used to simplify installation process.
Power users can always figure out how to extend such entities. However, this might be a subject of change in future versions.

#### Doctrine ORM

This is the defaut option. You need to have `doctrine/doctrine-bundle` registered. 

To create database schema, please, run:

```bash
$ php app/console doctrine:schema:update --dump-sql # review SQL change set
$ php app/console doctrine:schema:update --force
```
    
#### Doctrine Mongo ODM

Configuration needs to be updated to enable this driver:

```yaml
sputnik_pubsub:
    driver: doctrine_mongo
```
        
This driver assumes `doctrine/mongodb-odm-bundle` is installed and default database configured.

#### Mandango

Update config to:

```yaml
sputnik_pubsub:
    driver: mandango
```
        
This driver assumes `mandango/mandango-bundle` bundle is installed.

To create Mandango models, please, run:

```bash
$ php app/console mandango:generate
```
    
More information about Mandango can be found at official website - http://mandango.org.

## Next

Read next [how to setup your first hub](https://github.com/sputnik-project/SputnikPubsubBundle/blob/master/Resources/doc/working-with-hubs.md).
