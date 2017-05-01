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
    const SEARCH_FOR_IBAN = 'SELECT iban FROM accounts WHERE iban = ?';
    const SEARCH_FOR_SWIFT = 'SELECT * FROM mytestbank.banks where swift_code LIKE ?';
    const SEARCH_FOR_BAU = 'SELECT code FROM bau WHERE code = ?';
    const SEARCH_FOR_SUCH_ACCOUNT = 'SELECT * FROM accounts WHERE currency_id = ? AND account_type_id = ? AND client_id = ?';
    const CREATE_NEW_ACCOUNT = 'INSERT INTO accounts() VALUES (NULL,?,0,?,1,?,?)';

    const LOGIN_NAME = "DuRiechstSoGut";
    const LOGIN_PASS = 'ImABunny';

    const MAX_SECONDS_WITHOUT_A_TRANSACTION = 60*60*24*30*3;

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
    public static function createNewClient(){
    }
    public static function checkIfClientExists(){
    }
    public static function removeClient(){
    }

    //==================================
    //UPDATE THE CURRENCY EXCHANGE RATES
    //==================================
    public function updateAllCurrencies(){
    }

    //==================================
    //ACCOUNT RELATED FUNCTIONS
    //==================================
    public static function checkIfAccountExists(){

    }

    /**
     * @param $currency
     * @param $accountType
     * @param $clientID
     * @return bool true if The client has such account, else false
     */
    public function checkIfClientHasSuchAccount($currency, $accountTypeID, $clientID){
        $psmt = DBConnection::getInstance()->prepare(self::SEARCH_FOR_SUCH_ACCOUNT);
        $psmt->execute([$currency, $accountTypeID, $clientID]);
        $result = $psmt->fetchAll();
        return !empty($result);
    }
    public function createNewAccount($currency, $accountTypeID, $clientID){
        if ($this->checkIfClientHasSuchAccount($currency, $accountTypeID, $clientID)){
            throw new Exception('Client already has such account!');
        }
        $iban = $this->generateNewIBAN($accountTypeID);
        $psmt = DBConnection::getInstance()->prepare(self::CREATE_NEW_ACCOUNT);
        $psmt->execute([$iban, $currency, $accountTypeID, $clientID]);
    }
    public static function deactivateAccount(){
    }
    public static function checkIfAccountIsActive(){
    }
    public function getAccountTypeID(){

    }

    //==================================
    //eBANKING RELATED FUNCTIONS
    //==================================
    public static function checkLastLogin(){
    }
    public static function checkIfEBankingAccountExists(){
    }
    public static function createEBankingAccount(){
    }
    public static function deleteEBankingAccount(){
    }

    //==================================
    //TRANSACTION RELATED FUNCTIONS
    //==================================
    public static function checkLastTransactionOfAccount(){
    }

    //==================================
    //IBAN RELATED FUNCTIONS
    //==================================

    /**
     * Checks if an IBAN exists in my database
     * @param string $IBAN - the swift code to be checked
     * @return bool true code if exists, else false
     */
    private function checkIfIBANExists($IBAN){
        $pstmt = DBConnection::getInstance()->prepare(self::SEARCH_FOR_IBAN);
        $pstmt->execute([$IBAN]);
        $result = $pstmt->fetchAll();
        return !empty($result);
    }

    /**
     * Checks if the given SWIFT code exist. Works only with Bulgarian swift codes!
     * @param string $swift - the swift code to be checked
     * @throws <b>'Invalid swift code search!'</b> exception if the SWIFT is shorter than 4 chars or if it is numeric
     * @return mixed false code if does not exist, else array of swift codes similar to the given one
     */
    public function checkSWIFT($swift){
        if (strlen($swift) < 4 || is_numeric($swift)){
            throw new Exception('Invalid swift code search!');
        }
        $swift = strtoupper($swift);
        $psmt = DBConnection::getInstance()->prepare(self::SEARCH_FOR_SWIFT);
        $swift .= '%';
        $psmt->execute([$swift]);
        $result = $psmt->fetchAll();
        return empty($result) ? false : $result;
    }

    /**
     * Checks if the BANK ADDRESS UNIT code exists in my database. Works only with bulgarian bank branches!
     * @param string $bau - the BANK ADDRESS UNIT to be checked
     * @return mixed false code if does not exist, else the the found BAU code
     */
    public function checkBAU($bau){
        $bau = strtoupper($bau);
        $psmt = DBConnection::getInstance()->prepare(self::SEARCH_FOR_BAU);
        $psmt->execute([$bau]);
        $result = $psmt->fetchAll();
        return empty($result) ? false : $result;
    }

    /**
     * Checks if the given IBAN is valid.
     * @param string $IBAN to be checked
     * @return bool true if IBAN is valid, false otherwise
     */
    public function checkIfIBANIsValid($IBAN){
        if (strlen($IBAN) != 22) return false;
        if (substr($IBAN, 0, 2) !== self::COUNTRY) return false;
        if (substr($IBAN, 2, 2) < 10) return false;
        if ($this->checkBAU(substr($IBAN, 4, 8) === false)) return false;
        return true;
    }

    /**
     * Creates new IBAN for my bank
     * @param int $accountTypeID - the ID of the type of the created account
     * @return string the new IBAN
     */
    public function generateNewIBAN($accountTypeID){
        $controlNumber = rand(10,99);
        $accountType = strlen("$accountTypeID") > 1 ? $accountTypeID : '0' . $accountTypeID;

        $rest = '';
        for ($i = 0; $i < 8; $i++){
            $rest .= rand(0,9);
        }

        $IBAN = self::COUNTRY . $controlNumber . self::BANK_IDENTIFIER . self::BAU_IDENTIFIER . $accountType . $rest;

        if ($this->checkIfIBANExists($IBAN)) {
            $this->generateNewIBAN($accountTypeID);
        }

        return $IBAN;
    }

    //==================================
    //SEND SMS OR EMAIL
    //==================================
    public static function sendSMS(){
    }
    public static function sendEMail(){
    }
}
require_once 'DBConnection.php';
var_dump(Bank::getInstance()->generateNewIBAN(5));