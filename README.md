# SputnikPubsubBundle

Despite the name SputnikPubsubBundle implements support for _pubsubhubbub_ subscriber. This bundle was inspired by https://github.com/hearsayit/PubSubHubbubBundle
and was used as a foundation for current Sputnik version.

Main features include:

 - support for multiple storage drivers: Doctrine ORM, Doctrine ODM and Mandango;
 - ability to define and subscribe to different hubs;
 - set of CLI commands to manage subscriptions;
 - implementation of really simple test hub i.e. you don't need to send requests to real hubs when developing;
 - extensive logging (via separate _pubsub_ channel).
 
__Note__: This bundle requires Symfony 2.2 to operate, but could be easily adpated to work with Symfony 2.1. Patches welcome!
__Note__: At the moment this bundle supports synchronous subscriptions only.

[![Build Status](https://api.travis-ci.org/sputnik-project/SputnikPubsubBundle.png?branch=master)](https://travis-ci.org/sputnik-project/SputnikPubsubBundle)

## Documentation

Documentation can be found in `Resources/doc`. You can start with 
[installation instructions](https://github.com/sputnik-project/SputnikPubsubBundle/blob/master/Resources/doc/01-installation.md).

## Resources

 - https://github.com/hearsayit/PubSubHubbubBundle
 - https://code.google.com/p/pubsubhubbub
 - http://superfeedr.com
