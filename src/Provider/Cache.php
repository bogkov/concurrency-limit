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

namespace Bogkov\ConcurrencyLimit\Provider;

use Doctrine\Common\Cache\CacheProvider;

/**
 * Class Cache
 *
 * @package Bogkov\ConcurrencyLimit\Provider
 */
class Cache implements ProviderInterface
{
    const DEFAULT_LIFETIME = 30;

    /**
     * @var CacheProvider
     */
    protected $cache;

    /**
     * @var int
     */
    protected $lifetime;

    /**
     * Cache constructor.
     *
     * @param CacheProvider $cache    cache
     * @param int           $lifetime lifetime
     */
    public function __construct(CacheProvider $cache, int $lifetime = self::DEFAULT_LIFETIME)
    {
        $this->cache = $cache;
        $this->lifetime = $lifetime;
    }

    /**
     * @param string $key key
     *
     * @return int|null
     */
    public function fetch(string $key)/*: ?int*/
    {
        return false !== ($result = $this->cache->fetch(static::prepareKey($key))) ? $result : null;
    }

    /**
     * @param string $key   key
     * @param int    $value value
     *
     * @return bool
     */
    public function save(string $key, int $value): bool
    {
        return $this->cache->save(static::prepareKey($key), $value, $this->lifetime);
    }

    /**
     * @param string $key key
     *
     * @return string
     */
    protected static function prepareKey(string $key): string
    {
        return sha1(strtolower(__CLASS__ . $key));
    }
}