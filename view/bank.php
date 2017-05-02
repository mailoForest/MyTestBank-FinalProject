<?php
session_start();
function setSession($var){
    $_SESSION[$var] = $_POST[$var];
}
$message = '';

if (isset($_POST['submit'])){
    setSession('region');
    setSession('settlement');
    setSession('district');
    setSession('street');
    setSession('streetNumber');
    setSession('blockNumber');
    setSession('entrance');
    setSession('apartment');
    setSession('egn');
    setSession('firstName');
    setSession('secondName');
    setSession('thirdName');
    setSession('email');
    setSession('gsm');
    setSession('currency');
    setSession('accountType');

    header('Location: ../controller/BankController.php');
}
if (empty($_SESSION['error'])){
    $message = 'Successfully added!';
} else {
    $message = $_SESSION['error'];
}

?>
<h1>Add new Client</h1>
<form action="" method="post">
    <table>
        <caption>Адрес</caption>
        <tr><th><label for="">Област</label></th><td><input name="region" type="text"></td></tr>
        <tr><th><label for="">Град/село</label></th><td><input name="settlement" type="text"></td></tr>
        <tr><th><label for="">Квартал</label></th><td><input name="district" type="text"></td></tr>
        <tr><th><label for="">Улица</label></th><td><input name="street" type="text"></td></tr>
        <tr><th><label for="">Номер №</label></th><td><input name="streetNumber" type="number"></td></tr>
        <tr><th><label for="">Блок</label></th><td><input name="blockNumber" type="text"></td></tr>
        <tr><th><label for="">Вход</label></th><td><input name="entrance" type="text"></td></tr>
        <tr><th><label for="">Ап. №</label></th><td><input name="apartment" type="number"></td></tr>
    </table>
    <table>
        <caption>Клиент</caption>
        <tr><th><label for="">ЕГН</label></th><td><input name="egn" type="text"></td></tr>
        <tr><th><label for="">Име</label></th><td><input name="firstName" type="text"></td></tr>
        <tr><th><label for="">Презиме</label></th><td><input name="secondName" type="text"></td></tr>
        <tr><th><label for="">Фамилия</label></th><td><input name="thirdName" type="text"></td></tr>
        <tr><th><label for="">Имейл</label></th><td><input name="email" type="email"></td></tr>
        <tr><th><label for="">Моб. телефон</label></th><td><input name="gsm" type="tel"></td></tr>
    </table>
    <table>
        <caption>Банкова сметка</caption>
        <tr><th><label for="">Валута</label></th>
            <td>
                <select name="currency" id="">
                    <option value="AUD">AUD</option>
                    <option value="BGN">BGN</option>
                    <option value="EUR">EUR</option>
                    <option value="BRL">BRL</option>
                    <option value="CAD">CAD</option>
                    <option value="CHF">CHF</option>
                    <option value="CNY">CNY</option>
                    <option value="CZK">CZK</option>
                    <option value="DKK">DKK</option>
                    <option value="GBP">GBP</option>
                    <option value="HKD">HKD</option>
                    <option value="HRK">HRK</option>
                    <option value="HUF">HUF</option>
                    <option value="IDR">IDR</option>
                    <option value="ILS">ILS</option>
                    <option value="INR">INR</option>
                    <option value="JPY">JPY</option>
                    <option value="KRW">KRW</option>
                    <option value="MXN">MXN</option>
                    <option value="MYR">MYR</option>
                    <option value="NOK">NOK</option>
                    <option value="NZD">NZD</option>
                    <option value="PHP">PHP</option>
                    <option value="PLN">PLN</option>
                    <option value="RON">RON</option>
                    <option value="RUB">RUB</option>
                    <option value="SEK">SEK</option>
                    <option value="SGD">SGD</option>
                    <option value="THB">THB</option>
                    <option value="TRY">TRY</option>
                    <option value="USD">USD</option>
                    <option value="XAU">XAU</option>
                    <option value="ZAR">ZAR</option>
                </select>
            </td>
        </tr>
        <tr><th><label for="">Тип банкова сметка</label></th>
            <td>
                <select name="accountType" id="">
                    <option value="1">Разплащателна<</option>
                    <option value="2">Дебитна</option>
                    <option value="3">Депозитна</option>
                    <option value="4">Спестовна</option>
                </select>
            </td>
        </tr>
    </table>
    <input type="submit" name="submit" value="Add new client">
</form>
<h1><?=$message?></h1>
