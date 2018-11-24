<p align="center">
<a href="https://insight.symfony.com/projects/48af693f-97d3-4f11-a697-3e6ec9ff7e3c">
	<img src="https://insight.symfony.com/projects/48af693f-97d3-4f11-a697-3e6ec9ff7e3c/big.svg"/>
</a>
<br/>
<br/>
<a href="https://travis-ci.org/tulik/symfony-4-rest-api.svg?branch=master">
	<img src="https://travis-ci.org/tulik/symfony-4-rest-api.svg?branch=master"/>

<a href="https://scrutinizer-ci.com/g/tulik/symfony-4-rest-api/?branch=master">
	<img src="https://scrutinizer-ci.com/g/tulik/symfony-4-rest-api/badges/coverage.png?b=master"/>
</a>

<a href="https://scrutinizer-ci.com/g/tulik/symfony-4-rest-api/?branch=master">
	<img src="https://scrutinizer-ci.com/g/tulik/symfony-4-rest-api/badges/quality-score.png?b=master"/>
</a>

</p>

<p style="font-size: 3em;" align="center"> Symfony 4 REST API</p>

<p style="font-size: 2em;" align="center">  Demo of RESTfull application written <br/><strong>without</strong><br/> <i>FOSUserBundle</i> and <i>FOSRestBundle</i>. </p>

<p style="font-size: 1.5em;" align="center"><strong>See demo: </strong> <a href="http://rest-api.tulik.io">http://rest-api.tulik.io</a></p>


**Requirements:** PHP min. version 7.2.0

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

## Listing with filters and pagination
It is possible filtering listed data using **LexikFormFilterBundle** and to paginate results using **whiteoctober/Pagerfanta**

## Flexibility
The whole API including contains **only ~2000 lines of code**, gives you full control possibility easily adapt it with an existing project.

## Extensibility
 Extending its functionality of additional **ElasticSearch**, **Redis** or **RabbitMQ** solution is straightforward. In case you need to change something it's always under your 

<p align="center">
<strong><a style="font-size: 1.5em;" href="EXAMPLES.md">See examples of usage</a></strong>
</p>

# Documentation of implementation
1. [Controllers](tree/master/src/Controller)
2. [Entities](tree/master/src/Entity)
3. [EventListener](tree/master/src/EventListener)
4. [Form](tree/master/src/EventListener)
5. [Resource](tree/master/src/Resource)
6. [Security](tree/master/src/Security)
7. [Service](tree/master/src/Service)
8. [Traits](tree/master/src/Traits)