<?php
/**
 * Created by PhpStorm.
 * User: HP Pavilion 17
 * Date: 1.5.2017 г.
 * Time: 17:10
 */

interface IAddress {

    public function validateAddress($street, $streetNumber, $blockNumber, $entrance, $apartmentNumber);
    public function checkIfAddressExists($region, $settlement, $district, $street, $streetNumber, $blockNumber, $entrance, $apartmentNumber);
    public function addNewAddress($region, $settlement, $district, $street, $streetNumber, $blockNumber, $entrance, $apartmentNumber);
}