<?php
session_start();
function __autoload($class){
    require_once '../model/' . $class . '.php';
}

if (!isset($_SESSION)) header('Location: ../view/bank.php');

$_SESSION['error'] = '';

try{
    $address =
        new Address(
            $_SESSION['region'],
            $_SESSION['settlement'],
            $_SESSION['district'],
            $_SESSION['street'],
            $_SESSION['streetNumber'],
            $_SESSION['blockNumber'],
            $_SESSION['entrance'],
            $_SESSION['apartment']
        );

    $client =
        new Client(
            $_SESSION['egn'],
            $_SESSION['firstName'],
            $_SESSION['secondName'],
            $_SESSION['thirdName'],
            $address,
            $_SESSION['email'],
            $_SESSION['gsm']
        );
    $acc = new Account($_SESSION['currency'], $_SESSION['accountType'], $client);

    Bank::getInstance()->createNewClient($client, $acc);
} catch (Exception $exception){
    $_SESSION['error'] = $exception->getMessage();
}
header('Location: ../view/bank.php');