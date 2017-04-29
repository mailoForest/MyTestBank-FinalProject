<?php
/**
 * Created by PhpStorm.
 * User: HP Pavilion 17
 * Date: 29.4.2017 г.
 * Time: 9:03
 */
const UPDATE_INFO_INTO_CURRENCIES_SQL =
        'UPDATE currencies SET one_unit_to_one_BGN = ?, one_BGN_to_one_unit = ? WHERE id = ?';

$json = file_get_contents('http://api.fixer.io/latest?base=BGN');
$json = json_decode($json, true);
$currencyExchangeRates = $json['rates'];
try{

    $db = new PDO("mysql:host=localhost;dbname=mytestbank;charset=utf8", "root", '');
    $pstmt = $db->prepare(UPDATE_INFO_INTO_CURRENCIES_SQL);

    foreach ($currencyExchangeRates as $currency => $currencyExchangeRate){
        $unitToLeva = 1 / $currencyExchangeRate;
        $pstmt->execute([$unitToLeva, $currencyExchangeRate, $currency]);
    }
} catch (Exception $e){
    echo $e->getMessage();
}
