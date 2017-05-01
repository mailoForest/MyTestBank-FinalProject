<?php

/**
 * Created by PhpStorm.
 * User: HP Pavilion 17
 * Date: 29.4.2017 г.
 * Time: 14:55
 */
final class DBConnection
{
    private static $connection = null;

    private function __construct()
    {
    }

    public static function getInstance(){
        if (self::$connection === null){
            self::$connection = new PDO('mysql:host=localhost;dbname=mytestbank;charset=utf8', 'root', '');
        }
        return self::$connection;
    }
}