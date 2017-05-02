<?php

final class Bank
{
    const UPDATE_INFO_INTO_CURRENCIES_SQL = 'UPDATE currencies SET one_unit_to_one_BGN = ?, one_BGN_to_one_unit = ? WHERE id = ?';
    const SEARCH_FOR_IBAN = 'SELECT iban FROM accounts WHERE iban = ?';
    const SEARCH_FOR_SWIFT = 'SELECT * FROM mytestbank.banks where swift_code LIKE ?';
    const SEARCH_FOR_BAU = 'SELECT code FROM bau WHERE code = ?';
    const ADD_NEW_CLIENT = 'INSERT INTO clients() VALUES (NULL, ?, ?, ?, ?, ?, ?, ?)';

    const LOGIN_NAME = "DuRiechstSoGut";
    const LOGIN_PASS = 'ImABunny';

    const COUNTRY  = "BG";
    const SWIFT_CODE = 'TESTBANK';
    const BANK_IDENTIFIER = 'TEST';
    const BAU_IDENTIFIER = 9465;

    private static $instance = null;

    private function __construct(){}
    public static function getInstance(){
        if (self::$instance === null){
            self::$instance = new Bank();
        }
        return self::$instance;
    }

    //==================================
    //CLIENT RELATED FUNCTIONS
    //==================================
    public function createNewClient(Client $client, Account $account){
        if ($client->getClientIDWithEGN($client->egn)){
            throw new Exception('Client already exists!');
        }
        $psmt = DBConnection::getInstance()->prepare(self::ADD_NEW_CLIENT);
        $psmt->execute([$client->egn, $client->firstName, $client->secondName, $client->thirdName, $client->email, $client->address->id, $client->gsm]);
        $client->setID($client->egn);
        $account->createNewAccount($client);
    }


    public static function removeClient(){
    }
    //==================================
    //SEND SMS OR EMAIL
    //==================================
    public static function sendSMS(){
    }
    public static function sendEMail(){
    }

}