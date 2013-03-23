# Configuration reference

All options are not required and respective default values are used instead.

```yaml
sputnik_pubsub:
    live_hub: false
    route: sputnik_pubsub_callback_process
    hub_test_route: sputnik_pubsub_hub_process
    driver: doctrine # allowed values: doctrine, doctrine_mongo, mandango
```

## Examples

To enable subscriptions to live hub:

```yaml
sputnik_pubsub:
    live_hub: true
```
        
To change storage driver:

```yaml
sputnik_pubsub:
    driver: doctrine_mongo
```
        
To change topic callback route:

```yaml
sputnik_pubsub:
    route: my-route-name-in-here
```
