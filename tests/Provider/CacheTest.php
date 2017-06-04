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

namespace Bogkov\ConcurrencyLimitTests\Provider;

use Bogkov\ConcurrencyLimit\Provider\Cache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\CacheProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class CacheTest
 *
 * @package Bogkov\ConcurrencyLimitTests\Provider
 */
class CacheTest extends TestCase
{
    /**
     * @return array
     */
    public function providerFetch(): array
    {
        return [
            'value exists and returned' => [
                '$config' => [
                    'value'    => 1,
                    'expect'   => 1,
                ],
            ],

            'value not exists and null returned' => [
                '$config' => [
                    'value'    => false,
                    'expect'   => null,
                ],
            ],
        ];
    }

    /**
     * @dataProvider providerFetch
     *
     * @param array $config config
     *
     * @return void
     */
    public function testFetch(array $config)/*: void*/
    {
        $key = 'some-long-key';

        $mock = $this->getMockBuilder(ArrayCache::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->once())
            ->method('fetch')
            ->with($this->equalTo('18bbde7c7688eefa47f9b54ec9771e0ae795e41b'))
            ->will($this->returnValue($config['value']));

        /** @var CacheProvider $mock */
        $cache = new Cache($mock);
        $this->assertEquals($config['expect'], $cache->fetch($key));
    }

    /**
     * @return array
     */
    public function providerSave(): array
    {
        return [
            'save success' => [
                '$config' => [
                    'value'    => 1,
                    'expect'   => true,
                ],
            ],

            'save failed' => [
                '$config' => [
                    'value'    => 1,
                    'expect'   => false,
                ],
            ],
        ];
    }

    /**
     * @dataProvider providerSave
     *
     * @param array $config config
     *
     * @return void
     */
    public function testSave(array $config)/*: void*/
    {
        $key = 'some-long-key';

        $mock = $this->getMockBuilder(ArrayCache::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->once())
            ->method('save')
            ->with(
                $this->equalTo('18bbde7c7688eefa47f9b54ec9771e0ae795e41b'),
                $this->equalTo($config['value']),
                $this->equalTo(Cache::DEFAULT_LIFETIME)
            )
            ->will($this->returnValue($config['expect']));

        /** @var CacheProvider $mock */
        $cache = new Cache($mock);
        $this->assertEquals($config['expect'], $cache->save($key, $config['value']));
    }
}