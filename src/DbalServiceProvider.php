<?php
/**
 * Created by PhpStorm.
 * User: Xavier
 * Date: 04/03/2016
 * Time: 16:46
 */

namespace TheCodingMachine;


use Assembly\ArrayDefinitionProvider;
use Assembly\ObjectDefinition;
use Assembly\ParameterDefinition;
use Assembly\Reference;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver;
use Interop\Container\ContainerInterface;
use Interop\Container\ServiceProvider;

class DbalServiceProvider implements ServiceProvider
{
    /**
     * @return array
     */
    public static function getServices() : array
    {
        return [
            Connection::class => new ObjectDefinition(Connection::class, [new Reference(Connection::class.'.params', Driver::class)]),
            Connection::class.'.params' => new ArrayDefinitionProvider([
                new Reference('dbal.host'),
                new Reference('dbal.user'),
                new Reference('dbal.password'),
                new Reference('dbal.port'),
                new Reference('dbal.dbname'),
                new Reference('dbal.charset'),
                new Reference('dbal.driverOptions'),
            ]),
            'dbal.host'=> new ParameterDefinition('localhost'),
            'dbal.user'=> new ParameterDefinition('root'),
            'dbal.password'=> new ParameterDefinition(''),
            'dbal.port'=> new ParameterDefinition('3306'),
            'dbal.dbname'=> [DbalServiceProvider::class, 'getDbname'],
            'dbal.charset'=> new ParameterDefinition('utf8'),
            'dbal.driverOptions'=> new ArrayDefinitionProvider([1002 => new ParameterDefinition("SET NAMES utf8")]),
            Driver::class => new ObjectDefinition(Driver::class)
        ];
    }

    
    public static function getDbname(): string
    {
        throw new DBALException('The "dbname" must be set in the container entry "dbal.dbname"');
    }
    
}