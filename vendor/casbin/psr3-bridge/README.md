# PSR-3 Bridge for PHP-Casbin Logger

[![Build Status](https://travis-ci.org/php-casbin/psr3-bridge.svg?branch=master)](https://travis-ci.org/php-casbin/psr3-bridge)
[![Coverage Status](https://coveralls.io/repos/github/php-casbin/psr3-bridge/badge.svg)](https://coveralls.io/github/php-casbin/psr3-bridge)
[![Latest Stable Version](https://poser.pugx.org/casbin/psr3-bridge/v/stable)](https://packagist.org/packages/casbin/psr3-bridge)
[![Total Downloads](https://poser.pugx.org/casbin/psr3-bridge/downloads)](https://packagist.org/packages/casbin/psr3-bridge)
[![License](https://poser.pugx.org/casbin/psr3-bridge/license)](https://packagist.org/packages/casbin/psr3-bridge)

This library provides a PSR-3 compliant bridge for `PHP-Casbin` Logger.

[Casbin](https://github.com/php-casbin/php-casbin) is a powerful and efficient open-source access control library.

### Installation

Via [Composer](https://getcomposer.org/).

```
composer require casbin/psr3-bridge
```

### Usage

Here is an example of using `Monolog`, `Monolog` implements the PSR-3 interface.

You can use any other library that implements PSR-3 interface.

```php

use Casbin\Bridge\Logger\LoggerBridge;
use Casbin\Log\Log;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;


$log = new Logger('name');
$log->pushHandler(new StreamHandler('path/to/your.log', Logger::WARNING));


Log::setLogger(new LoggerBridge($log));
```

### Getting Help

- [php-casbin](https://github.com/php-casbin/php-casbin)

### License

This project is licensed under the [Apache 2.0 license](LICENSE).
