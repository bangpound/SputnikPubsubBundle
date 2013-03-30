# SputnikPubsubBundle

Despite the name SputnikPubsubBundle implements support for _pubsubhubbub_ subscriber. This bundle was inspired by https://github.com/hearsayit/PubSubHubbubBundle
and was used as a foundation for current Sputnik version.

#### Main features

 - Support for multiple storage drivers: Doctrine ORM, Doctrine ODM and [Mandango](http://mandango.org).
 - Ability to define and subscribe to different hubs.
 - Set of console commands to manage and test subscriptions.
 - Implementation of really simple test hub i.e. you don't need to send requests to real hubs when developing.
 - Extensive logging (via separate _pubsub_ channel).

#### Notes

 - This bundle requires Symfony 2.2 to operate, but could be easily adopted to work with Symfony 2.1. Patches welcome!
 - At the moment SputnikPubsubBundle supports synchronous subscriptions only.
 - There is a sample _Sandbox_ application with Sputnik bundles installed and configured - https://github.com/sputnik-project/sandbox.

[![Build Status](https://api.travis-ci.org/sputnik-project/SputnikPubsubBundle.png?branch=master)](https://travis-ci.org/sputnik-project/SputnikPubsubBundle)

## Documentation

Documentation can be found in `Resources/doc`. You can start with
[installation instructions](https://github.com/sputnik-project/SputnikPubsubBundle/blob/master/Resources/doc/installation.md).

[API documentation](http://api.sputnik-project.org).

## Resources

 - https://github.com/hearsayit/PubSubHubbubBundle
 - https://code.google.com/p/pubsubhubbub
 - http://superfeedr.com
