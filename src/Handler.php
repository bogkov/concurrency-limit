<?php
/*
 * This file is part of the Concurrency Limit package.
 *
 * (c) Bogdan Koval' <scorpioninua@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Bogkov\ConcurrencyLimit;

use Bogkov\ConcurrencyLimit\Exception\LessThanMinimumException;
use Bogkov\ConcurrencyLimit\Exception\SaveException;
use Bogkov\ConcurrencyLimit\Provider\ProviderInterface;

/**
 * Class Handler
 *
 * @package Bogkov\ConcurrencyLimit
 */
class Handler
{
    const MINIMUM_VALUE = 0;

    /**
     * @var ProviderInterface
     */
    protected $provider;

    /**
     * Handler constructor.
     *
     * @param ProviderInterface $provider provider
     */
    public function __construct(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param string $key   key
     * @param int    $limit limit
     *
     * @return bool
     */
    public function start(string $key, int $limit): bool
    {
        $value = $this->provider->fetch($key) ?? static::MINIMUM_VALUE;

        if ($limit <= $value) {
            return false;
        }

        $this->save($key, ++$value);

        return true;
    }

    /**
     * @param string $key key
     *
     * @return void
     */
    public function end(string $key)/*: void*/
    {
        $value = $this->provider->fetch($key);

        if (null === $value) {
            return;
        }

        $this->save($key, --$value);
    }

    /**
     * @param string $key   key
     * @param int    $value value
     *
     * @throws LessThanMinimumException
     * @throws SaveException
     *
     * @return void
     */
    protected function save(string $key, int $value)/*: void*/
    {
        if (static::MINIMUM_VALUE > $value) {
            throw new LessThanMinimumException(
                'Value ' . $value . ' less than minimum ' . static::MINIMUM_VALUE . ' by "' . $key . '"'
            );
        }

        if (false === $this->provider->save($key, $value)) {
            throw new SaveException('Failed save concurrent limit by "' . $key . '" with value ' . $value);
        }
    }
}