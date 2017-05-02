<?php

class Account
{
    const SEARCH_FOR_SUCH_ACCOUNT = 'SELECT * FROM accounts WHERE currency_id = ? AND account_type_id = ? AND client_id = ?';
    const SEARCH_ACCOUNT_TYPE_ID = 'SELECT id FROM account_types WHERE id = ?';
    const SEARCH_FOR_CURRENCY = 'SELECT * FROM currencies WHERE id = ?';
    const CREATE_NEW_ACCOUNT = 'INSERT INTO accounts() VALUES (NULL,?,0,?,1,?,?)';

    const MAX_SECONDS_WITHOUT_A_TRANSACTION = 60*60*24*30*3;

    private $currency;
    private $balance = 0;
    private $accountTypeID;

    public function __construct($currency, $accountTypeID, Client $client)
    {
        $this->setCurrency($currency);
        //$this->iban = Bank::getInstance()->generateNewIBAN($accountTypeID);
        $this->accountTypeID = $this->checkAccountTypeID($accountTypeID);
    }

    use TraitIBAN;

    public function checkIfClientHasSuchAccount($currency, $accountTypeID, $clientID){
        $psmt = DBConnection::getInstance()->prepare(self::SEARCH_FOR_SUCH_ACCOUNT);
        $psmt->execute([$currency, $accountTypeID, $clientID]);
        $result = $psmt->fetchAll();
        if (!empty($result)){
            throw new Exception('Client already has such account!');
        }
        return false;
    }

    public function createNewAccount(Client $client){
        $this->checkAccountTypeID($this->accountTypeID);
        $this->checkCurrency($this->currency);
        $client->checkID();
        $this->checkIfClientHasSuchAccount($this->currency, $this->accountTypeID, $client->id);

        $iban = $this->generateNewIBAN($this->accountTypeID);
        $psmt = DBConnection::getInstance()->prepare(self::CREATE_NEW_ACCOUNT);
        $psmt->execute([$iban, $this->currency, $this->accountTypeID, $client->id]);
    }

    public static function deactivateAccount(){
        $psmt = DBConnection::getInstance()->exec('UPDATE accounts SET is_active = 0;');
    }
    public static function checkActivity(){

    }
    public function checkAccountTypeID($accountTypeID){
        $psmt =  DBConnection::getInstance()->prepare(self::SEARCH_ACCOUNT_TYPE_ID);
        $psmt->execute([$accountTypeID]);
        $res = $psmt->fetchAll(PDO::FETCH_NUM);
        if (empty($res)){
            throw new Exception('Not existing such account type id: ' . $accountTypeID);
        }
        return $res[0][0];
    }

    public function __get($name)
    {
        return $this->$name;
    }

    //==================================
    //CURRENCY RELATED FUNCTIONS
    //==================================
    public function checkCurrency($givenCurrency){
        $givenCurrency = strtoupper($givenCurrency);
        $psmt =  DBConnection::getInstance()->prepare(self::SEARCH_FOR_CURRENCY);
        $psmt->execute([$givenCurrency]);
        $res = $psmt->fetchAll(PDO::FETCH_NUM);
        if (empty($res)){
            throw new Exception('Invalid Currency!');
        }
        return $res[0][1];
    }

    public function setCurrency($currency){
        $this->currency = $this->checkCurrency($currency);
    }
}
