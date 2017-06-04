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

namespace Bogkov\ConcurrencyLimitTests;

use Bogkov\ConcurrencyLimit\Exception\LessThanMinimumException;
use Bogkov\ConcurrencyLimit\Exception\SaveException;
use Bogkov\ConcurrencyLimit\Handler;
use Bogkov\ConcurrencyLimit\Provider\Cache;
use Bogkov\ConcurrencyLimit\Provider\ProviderInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class HandlerTest
 *
 * @package Bogkov\ConcurrencyLimitTests
 */
class HandlerTest extends TestCase
{
    /**
     * @return array
     */
    public function providerStart(): array
    {
        return [
            'value is empty and call return true' => [
                '$config' => [
                    'limit'      => 3,
                    'fetchValue' => null,
                    'saveValue'  => 1,
                    'saveResult' => true,
                    'expect'     => true,
                ],
            ],

            'value equal limit and call return false' => [
                '$config' => [
                    'limit'      => 3,
                    'fetchValue' => 3,
                    'expect'     => false,
                ],
            ],

            'value is empty but failed save' => [
                '$config' => [
                    'limit'                  => 3,
                    'fetchValue'             => null,
                    'saveValue'              => 1,
                    'saveResult'             => false,
                    'expectException'        => SaveException::class,
                    'expectExceptionMessage' => 'Failed save concurrent limit by "some-long-key" with value 1',
                    'expect'                 => true,
                ],
            ],
        ];
    }

    /**
     * @dataProvider providerStart
     *
     * @param array $config config
     *
     * @return void
     */
    public function testStart(array $config)/*: void*/
    {
        $key = 'some-long-key';

        $providerMock = $this->getMockBuilder(Cache::class)
            ->disableOriginalConstructor()
            ->getMock();

        $providerMock->expects($this->once())
            ->method('fetch')
            ->with($this->equalTo($key))
            ->will($this->returnValue($config['fetchValue']));

        if (true === isset($config['saveValue'])) {
            $providerMock->expects($this->once())
                ->method('save')
                ->with($this->equalTo($key), $this->equalTo($config['saveValue']))
                ->will($this->returnValue($config['saveResult']));
        } else {
            $providerMock->expects($this->never())
                ->method('save');
        }

        /** @var ProviderInterface $providerMock */
        $handler = new Handler($providerMock);

        if (true === isset($config['expectException'])) {
            $this->expectException($config['expectException']);
            $this->expectExceptionMessage($config['expectExceptionMessage']);
        }

        $this->assertEquals($config['expect'], $handler->start($key, $config['limit']));
    }

    /**
     * @return array
     */
    public function providerEnd(): array
    {
        return [
            'value is empty' => [
                '$config' => [
                    'fetchValue' => null,
                ],
            ],

            'value is not empty' => [
                '$config' => [
                    'fetchValue' => 2,
                    'saveValue'  => 1,
                    'saveResult' => true,
                ],
            ],

            'value equal minimum' => [
                '$config' => [
                    'fetchValue'             => 0,
                    'expectException'        => LessThanMinimumException::class,
                    'expectExceptionMessage' => 'Value -1 less than minimum 0 by "some-long-key"',
                ],
            ],
        ];
    }

    /**
     * @dataProvider providerEnd
     *
     * @param array $config config
     *
     * @return void
     */
    public function testEnd(array $config)/*: void*/
    {
        $key = 'some-long-key';

        $providerMock = $this->getMockBuilder(Cache::class)
            ->disableOriginalConstructor()
            ->getMock();

        $providerMock->expects($this->once())
            ->method('fetch')
            ->with($this->equalTo($key))
            ->will($this->returnValue($config['fetchValue']));

        if (true === isset($config['saveValue'])) {
            $providerMock->expects($this->once())
                ->method('save')
                ->with($this->equalTo($key), $this->equalTo($config['saveValue']))
                ->will($this->returnValue($config['saveResult']));
        } else {
            $providerMock->expects($this->never())
                ->method('save');
        }

        /** @var ProviderInterface $providerMock */
        $handler = new Handler($providerMock);

        if (true === isset($config['expectException'])) {
            $this->expectException($config['expectException']);
            $this->expectExceptionMessage($config['expectExceptionMessage']);
        }

        $handler->end($key);

        $this->assertTrue(true);
    }
}