<?php

trait TraitIBAN {

    /**
     * Checks if an IBAN exists in my database
     * @param string $IBAN - the swift code to be checked
     * @return bool true code if exists, else false
     */
    private function checkIfIBANExists($IBAN){
        $pstmt = DBConnection::getInstance()->prepare(Bank::SEARCH_FOR_IBAN);
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
        $psmt = DBConnection::getInstance()->prepare(Bank::SEARCH_FOR_SWIFT);
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
        $psmt = DBConnection::getInstance()->prepare(Bank::SEARCH_FOR_BAU);
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
        if (substr($IBAN, 0, 2) !== Bank::COUNTRY) return false;
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

        $IBAN = Bank::COUNTRY . $controlNumber . Bank::BANK_IDENTIFIER . Bank::BAU_IDENTIFIER . $accountType . $rest;

        if ($this->checkIfIBANExists($IBAN)) {
            $this->generateNewIBAN($accountTypeID);
        }

        return $IBAN;
    }
}