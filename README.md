# Sentry for [Nette](https://www.nette.org) framework
[![Build Status](https://travis-ci.org/nofutur3/nette-sentry.svg?branch=master)](https://travis-ci.org/nofutur3/nette-sentry)
[![Downloads this Month](https://img.shields.io/packagist/dm/nofutur3/nette-sentry.svg)](https://packagist.org/packages/nofutur3/nette-sentry)
[![Latest stable](https://img.shields.io/packagist/v/nofutur3/nette-sentry.svg)](https://packagist.org/packages/nofutur3/nette-sentry)


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
    sentry: Nofutur3\Sentry\DI\SentryExtension
    
sentry:
    dsn: (your dsn from sentry)
```

#### Extended configuration with default values
```
sentry:
    dsn: (your dsn from sentry)
    debug: false
    user:
        email: email # email property in IIdentity
        username: username # username property in IIDentity
    skip_capture:
        - 'Nette\Neon\Exception' # will not report these exceptions
    options: # check: https://docs.sentry.io/clients/php/config/
```