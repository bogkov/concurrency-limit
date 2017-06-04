[![Build Status](https://travis-ci.org/bogkov/concurrency-limit.svg?branch=master)](https://travis-ci.org/bogkov/concurrency-limit) [![Coverage Status](https://coveralls.io/repos/github/bogkov/concurrency-limit/badge.svg?branch=master)](https://coveralls.io/github/bogkov/concurrency-limit?branch=master)

# Concurrency Limit

This component provides the functionality to concurrency limit on server

## Installation

This package can be installed as a [Composer](https://getcomposer.org/) dependency [bogkov/bogkov/concurrency-limit](https://packagist.org/packages/bogkov/concurrency-limit)

```bash
composer require bogkov/concurrency-limit
```

## Usage

```php
<?php
$provider = new \Bogkov\ConcurrencyLimit\Provider\Cache(new \Doctrine\Common\Cache\ArrayCache());
$handler = new \Bogkov\ConcurrencyLimit\Handler($provider);

$key = 'some-handle-key';
$limit = 1;

if (true === $handler->start($key, $limit)) {
    echo 'continue process' . PHP_EOL;
    $handler->end($key);
} else {
    echo 'concurrency limit exceeded' . PHP_EOL;
}
```