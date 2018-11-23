<p align="center">

[![SymfonyInsight](https://insight.symfony.com/projects/48af693f-97d3-4f11-a697-3e6ec9ff7e3c/big.svg)](https://insight.symfony.com/projects/48af693f-97d3-4f11-a697-3e6ec9ff7e3c)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tulik/symfony-4-rest-api/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tulik/symfony-4-rest-api/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/tulik/symfony-4-rest-api/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/tulik/symfony-4-rest-api/?branch=master) [![Build Status](https://travis-ci.org/tulik/symfony-4-rest-api.svg?branch=master)](https://travis-ci.org/tulik/symfony-4-rest-api)
</p>

**Symfony 4 Rest API** is a demo application written **without** FOSUserBundle and FOSRestBundle.

**See demo:** [http://rest-api.tulik.io](http://rest-api.tulik.io)

Requirements: PHP min. version 7.2.0

## Quick start

**Clone repository**

```
git clone https://github.com/tulik/symfony-4-rest-api.git
```

**Install dependencies**

```
composer install
```

**Start local server**

```
bin/console server:start
```

### [See examples of usage](EXAMPLES.md)

## Listing with filters and pagination
It is possible filtering listed data using **LexikFormFilterBundle** and to paginate results using **whiteoctober/Pagerfanta**

## Flexibility
The whole API including contains **only ~2000 lines of code**, gives you full control possibility easily adapt it with an existing project.

## Extensibility
 Extending its functionality of additional **ElasticSearch**, **Redis** or **RabbitMQ** solution is straightforward. In case you need to change something it's always under your 


# Documentation of implementation
1. [Controllers](tree/master/src/Controller)
2. [Entities](tree/master/src/Entity)
3. [EventListener](tree/master/src/EventListener)
4. [Form](tree/master/src/EventListener)
5. [Resource](tree/master/src/Resource)
6. [Security](tree/master/src/Security)
7. [Service](tree/master/src/Service)
8. [Traits](tree/master/src/Traits)


