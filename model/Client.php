<?php

class Client
{
    const SEARCH_FOR_CLIENT = 'SELECT *  FROM clients WHERE id = ?';
    const GET_CLIENT_ID_WITH_EGN = 'SELECT id FROM clients WHERE egn = ?';

    private $id;
    private $egn;
    private $firstName;
    private $secondName;
    private $thirdName;
    private $address;
    private $email;
    private $gsm;

    public function __construct($egn, $firstName, $secondName, $thirdName, Address $address, $email, $gsm)
    {
        $this->setEGN($egn);
        $this->setID($egn);
        $this->firstName = $firstName;
        $this->secondName = $secondName;
        $this->thirdName = $thirdName;
        $this->address = $address;
        $this->setEmail($email);
        $this->setGSM($gsm);
    }

    public function getAccounts(){
        $this->checkID();

        $psmt = DBConnection::getInstance()->prepare('SELECT * FROM accounts where client_id = ?');
        $psmt->execute([$this->id]);
        return $psmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAddress(){
        return $this->address;
    }

    public function setGSM($gsm){
        $res = preg_match('/(0|\+359)(?:8[7-9][0-9]{7}|988[0-9]{6})/', $gsm);
        if (!$res){
            throw new Exception('Invalid gsm!');
        }
        $this->gsm = $gsm;
    }
    public function setEmail($email){
        $res = preg_match('/^[A-Za-z]+[\w.-]{4,}@[a-z.-]+[.]{1}[a-z]{2,3}$/', $email);
        if (!$res){
            throw new Exception('Invalid email!');
        }
        $this->email = $email;
    }
    public function setEGN($egn){
        $egn .= '';
        $day = substr($egn, 4,2) + 0;
        $month = substr($egn, 2,2) + 0;
        if (strlen($egn) != 10 && !is_numeric($egn) && $egn < 0 && $day < 1 && $day > 31 && $month < 1 && $month > 12){
            throw new Exception('Invalid egn!');
        }
        $this->egn = $egn;
    }

    public function getClientIDWithEGN($egn){
        $psmt = DBConnection::getInstance()->prepare(self::GET_CLIENT_ID_WITH_EGN);
        $psmt->execute([$egn]);
        $result = $psmt->fetchAll();
        return $result ? $result[0][0] : false;
    }
    public function checkIfClientExists($clientID){
        $psmt = DBConnection::getInstance()->prepare(self::SEARCH_FOR_CLIENT);
        $psmt->execute([$clientID]);
        $result = $psmt->fetchAll();
        if (!empty($result)){
            throw new Exception('Client exists!');
        }
        return true;
    }

    public function checkID(){
        if (!$this->id){
            throw new Exception('ID not specified! Client is not in the database yet!');
        }
    }
    public function setID($egn){
        $this->id = $this->getClientIDWithEGN($egn);
    }

    public function __get($name)
    {
        return $this->$name;
    }
}