<?php
/**
 * Created by PhpStorm.
 * User: Xavier
 * Date: 04/03/2016
 * Time: 16:46
 */

namespace TheCodingMachine;


use Doctrine\DBAL\Connection;
use Interop\Container\ContainerInterface;
use Interop\Container\ServiceProvider;
use Interop\Container\Exception\ContainerException;
use Interop\Container\Exception\NotFoundException;

class DbalServiceProvider implements ServiceProvider
{
    public static function getServices()
    {
        return [
            Connection::class => 'createConnection'
        ];
    }
    public static function createConnection(ContainerInterface $container, callable $previous = null) : Connection
    {
        $params = $container->get(Connection::class.'.params');
        $driver = $container->get(Connection::class.'.driver');

        $connection =  new Connection($params, $driver);

        return $connection;
    }

}