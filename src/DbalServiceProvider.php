<?php
/**
 * Created by PhpStorm.
 * User: Xavier
 * Date: 04/03/2016
 * Time: 16:46
 */

namespace TheCodingMachine;


use Assembly\ArrayDefinitionProvider;
use Assembly\ParameterDefinition;
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
            Connection::class => [DbalServiceProvider::class,'createConnection'],
            Driver::class => [DbalServiceProvider::class,'getDriver'],
            // Default parameters should be overloaded by the container
            'dbal.host'=> new ParameterDefinition('localhost'),
            'dbal.user'=> new ParameterDefinition('root'),
            'dbal.password'=> new ParameterDefinition(''),
            'dbal.port'=> new ParameterDefinition('3306'),
            'dbal.dbname'=> [DbalServiceProvider::class, 'getDbname'],
            'dbal.charset'=> new ParameterDefinition('utf8'),
            'dbal.driverOptions'=> new ArrayDefinitionProvider([1002 => new ParameterDefinition("SET NAMES utf8")])
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

    public static function getDbname():string
    {
        throw new DBALException('The "dbname" must be set in the container entry "dbal.dbname"');
    }


    public static function getDriver():Driver
    {
        return new Driver\PDOMySql\Driver();
    }
}