[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/bogkov/concurrency-limit/master/LICENSE)
[![Latest Stable Version](https://img.shields.io/packagist/v/bogkov/concurrency-limit.svg)](https://packagist.org/packages/bogkov/concurrency-limit)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.0-8892BF.svg)](https://php.net/)
[![Build Status](https://travis-ci.org/bogkov/concurrency-limit.svg)](https://travis-ci.org/bogkov/concurrency-limit)
[![codecov](https://img.shields.io/codecov/c/github/bogkov/concurrency-limit.svg)](https://codecov.io/gh/bogkov/concurrency-limit)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bogkov/concurrency-limit/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/bogkov/concurrency-limit/?branch=master)

# Concurrency Limit

This component provides the functionality to concurrency limit on server

## Installation

This package can be installed as a [Composer](https://getcomposer.org/) dependency [bogkov/concurrency-limit](https://packagist.org/packages/bogkov/concurrency-limit)

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