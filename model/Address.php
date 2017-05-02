<?php

class Address implements IAddress
{
    const ADD_NEW_ADDRESS = 'INSERT INTO  addresses() VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?)';

    private $region;
    private $settlement;
    private $district;
    private $street;
    private $streetNumber;
    private $blockNumber;
    private $entrance;
    private $apartmentNumber;
    private $id;

    public function __construct($region, $settlement, $district = null, $street = null, $streetNumber = null, $blockNumber = null, $entrance = null, $apartmentNumber = null)
    {
        $this->validateAddress($street, $streetNumber, $blockNumber, $entrance, $apartmentNumber);

        $this->region = $region;
        $this->settlement = $settlement;
        $this->district = $district;
        $this->street = $street;
        $this->streetNumber = $streetNumber;
        $this->blockNumber = $blockNumber;
        $this->entrance = $entrance;
        $this->apartmentNumber = $apartmentNumber;

        $check = $this->checkIfAddressExists($region, $settlement, $district, $street, $streetNumber, $blockNumber, $entrance, $apartmentNumber);
        if (!$check){
            $this->addNewAddress($region, $settlement, $district, $street, $streetNumber, $blockNumber, $entrance, $apartmentNumber);
            $this->id = $this->checkIfAddressExists($region, $settlement, $district, $street, $streetNumber, $blockNumber, $entrance, $apartmentNumber)[0][0];
        } else {
            $this->id = $check[0][0];
        }
    }

    private function determine($var){
        return is_null($var) ? 'is' : '=';
    }

    public function validateAddress($street, $streetNumber, $blockNumber, $entrance, $apartmentNumber){
        $street = empty($street) ? null : $street;
        $streetNumber = empty($streetNumber) ? null : $streetNumber;
        $blockNumber = empty($blockNumber) ? null : $blockNumber;
        $entrance = empty($entrance) ? null : $entrance;
        $apartmentNumber = empty($apartmentNumber) ? null : $apartmentNumber;

        if (is_null($street) && !is_null($streetNumber)){
            throw new Exception('There is no street to give a street number!');
        }
        if (is_numeric($street) && $street < 1) {
            throw new Exception('Street can not be negative number!');
        }
        if (!is_null($streetNumber) && (!is_numeric($streetNumber) || $streetNumber <= 0)){
            throw new Exception('Invalid street number!');
        }
        if (is_null($street) && is_null($blockNumber)){
            throw new Exception('If the street has no name, then you live in a block witch can not be null in this case!');
        }
        if (!is_null($street) && is_null($blockNumber) && is_null($streetNumber)){
            throw new Exception('There must be street number in this case in this case!');
        }
        if (!is_null($blockNumber)){
            if (!is_numeric($blockNumber) || $blockNumber <= 0){
                throw new Exception('Block must be a number!');
            } else if (is_null($entrance) || !is_numeric($entrance) || $entrance <= 0){
                throw new Exception("Invalid block entrance!");
            } else if (is_null($apartmentNumber) || !is_numeric($apartmentNumber) || $apartmentNumber <= 0){
                throw new Exception("Entrance must have apartment number!");
            }
        }
    }
    public function addNewAddress($region, $settlement, $district, $street, $streetNumber, $blockNumber, $entrance, $apartmentNumber){
        $psmt = DBConnection::getInstance()->prepare(self::ADD_NEW_ADDRESS);
        $psmt->execute(func_get_args());
        $result = $psmt->fetchAll(PDO::FETCH_NUM);
    }
    public function checkIfAddressExists($region, $settlement, $district, $street, $streetNumber, $blockNumber, $entrance, $apartmentNumber){
        $stmt = "SELECT * FROM addresses 
        WHERE region = ? AND settlement = ? AND district " . $this->determine($district) . " ? 
        AND street " . $this->determine($street) . " ? AND street_number " . $this->determine($streetNumber) . " ? 
        AND block_number " . $this->determine($blockNumber) . " ? AND entrance " . $this->determine($entrance) . " ? 
        AND apartment_number " . $this->determine($apartmentNumber) . " ?;";

        $psmt = DBConnection::getInstance()->prepare($stmt);
        $psmt->execute(func_get_args());
        $result = $psmt->fetchAll(PDO::FETCH_NUM);

        return $result;
    }

    public function __get($name)
    {
        return $this->$name;
    }
}