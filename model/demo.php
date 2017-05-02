<?php

function __autoload($class){
    require_once $class . '.php';
}

try{
    $address = new Address('София', 'Елин Пелин', 'Върбето', 'Ангел Кънчев', '16');

    $client = new Client('9404118725', 'kal', 'kl', 'kl', $address, 'kaloya@examle.com', '0898111111');
    $acc = new Account('BGN', '2', $client);
    Bank::getInstance()->createNewClient($client, $acc);
    var_dump($acc);
} catch (Exception $exception){
    echo $exception->getMessage() . ' in ' . $exception->getFile() . ' on ' .$exception->getLine() . $exception->getTraceAsString();
}