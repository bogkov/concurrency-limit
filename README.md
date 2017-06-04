[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/bogkov/concurrency-limit/master/LICENSE)
[![Latest Stable Version](https://img.shields.io/packagist/v/bogkov/concurrency-limit.svg?style=flat-square)](https://packagist.org/packages/bogkov/concurrency-limit)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.0-8892BF.svg?style=flat-square)](https://php.net/)
[![Build Status](https://travis-ci.org/bogkov/concurrency-limit.svg?branch=master)](https://travis-ci.org/bogkov/concurrency-limit)
[![codecov](https://codecov.io/gh/bogkov/concurrency-limit/branch/master/graph/badge.svg)](https://codecov.io/gh/bogkov/concurrency-limit)

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