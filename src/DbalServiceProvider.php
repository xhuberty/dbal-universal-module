<?php
/**
 * Created by PhpStorm.
 * User: Xavier
 * Date: 04/03/2016
 * Time: 16:46
 */

namespace TheCodingMachine;


use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver;
use Interop\Container\ContainerInterface;
use Interop\Container\ServiceProvider;

class DbalServiceProvider implements ServiceProvider
{
    public static function getServices()
    {
        return [
            Connection::class => 'createConnection',
            'dbal.host'=> 'getHost',
            'dbal.user'=> 'getUser',
            'dbal.password'=> 'getPassword',
            'dbal.port'=> 'getPort',
            'dbal.dbname'=> 'getDbname',
            'dbal.charset'=> 'getCharset',
            'dbal.driverOptions'=> 'getDriverOptions',
            Driver::class => 'getDriver'
        ];
    }
    public static function createConnection(ContainerInterface $container, callable $previous = null) : Connection
    {
        if($container->has(Connection::class.'.params')) {
            $params = $container->get('dbal.params');
        } else {
            $params = array(
                'host' => $container->get('dbal.host'),
                'user' =>$container->get('dbal.user'),
                'password' => $container->get('dbal.password'),
                'port' => $container->get('dbal.port'),
                'dbname' => $container->get('dbal.dbname'),
                'charset' => $container->get('dbal.charset'),
                'driverOptions' => $container->get('dbal.driverOptions')
            );
        }

        $driver = $container->get(Driver::class);

        $connection =  new Connection($params, $driver);

        return $connection;
    }

    public static function getHost() :string
    {
        return 'localhost';
    }

    public static function getUser():string
    {
        return 'root';
    }

    public static function getPassword():string
    {
        return '';
    }

    public static function getPort():int
    {
        return 3306;
    }

    public static function getDbname():string
    {
        throw new DBALException('The "dbname" must be set in the container entry "dbal.dbname"');
    }

    public static function getCharset():string
    {
        return 'utf8';
    }

    public static function getDriverOptions():array
    {
        return array(1002 =>"SET NAMES utf8");
    }
    public static function getDriver():Driver
    {
        return new Driver\PDOMySql\Driver();
    }
}