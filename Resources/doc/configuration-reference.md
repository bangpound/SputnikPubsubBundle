# Configuration reference

All options are not required and respective default values are used instead.

    sputnik_pubsub:
        live_hub: false
        route: sputnik_pubsub_callback_process
        hub_test_route: sputnik_pubsub_hub_process
        driver: doctrine # allowed values: doctrine, doctrine_mongo, mandango

## Examples

To enable subscriptions to live hub:

    sputnik_pubsub:
        live_hub: true
        
To change storage driver:

    sputnik_pubsub:
        driver: doctrine_mongo
        
To change topic callback route:

    sputnik_pubsub:
        route: my-route-name-in-here
