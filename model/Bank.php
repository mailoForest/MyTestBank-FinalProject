<?php

/**
 * Created by PhpStorm.
 * User: HP Pavilion 17
 * Date: 29.4.2017 г.
 * Time: 14:37
 */
final class Bank
{
    const UPDATE_INFO_INTO_CURRENCIES_SQL = 'UPDATE currencies SET one_unit_to_one_BGN = ?, one_BGN_to_one_unit = ? WHERE id = ?';

    private static $instance = null;
    private $val;

    private function __construct()
    {
        $this->val = 5;
    }

    public static function getInstance(){
        if (self::$instance === null){
            self::$instance = new Bank();
        }
        return self::$instance;
    }

    public static function addClient(){
        self::getInstance();

    }

    public static function updateAllCurrencies(){
    }
}

var_dump(Bank::getInstance());