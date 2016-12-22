# Sentry for [Nette](https://www.nette.org)
[![Build Status](https://travis-ci.org/nofutur3/nette-sentry.svg?branch=master)](https://travis-ci.org/nofutur3/tracy-gitpanel)
[![Downloads this Month](https://img.shields.io/packagist/dm/nofutur3/nette-sentry.svg)](https://packagist.org/packages/nofutur3/tracy-gitpanel)
[![Latest stable](https://img.shields.io/packagist/v/nofutur3/nette-sentry.svg)](https://packagist.org/packages/nofutur3/tracy-gitpanel)


Nette integration for Sentry

## Installation

The recommended installation is using [composer](https://getcomposer.org/). 

_If you are not still using composer, you should check it out. It's 2016(+) afterall._

```
composer require nofutur3/nette-sentry
```

Alternative way - in case you are not able to use composer. Download the source code (ie clone git repo) into your project
and require it some way. For [nette framework](https://nette.org/en/) like this in your bootstrap file:
```
$configurator
    ->createRobotLoader()
    ->addDirectory(__DIR__ . 'path/to/library/');
```

## Usage
##### With Nette (2.3+)
```
extensions:
    sentry: Nofutur3\Sentry\SentryExtension
    
sentry:
    dsn: (your dsn from sentry)
```
##### Standalone with Tracy