<?php

require_once 'Telegram.php';
require_once 'user.php';
$telegram = new Telegram("6395779599:AAFd9anUak6ZoY50vEwIeUwd-Wik7i3dYpQ");
$Admin_Chat_Id =1435346034;
$user_name = $telegram->FirstName();

$orderTypes = ["1kg - 50 000 so'm", "1.5kg(1 litr)  - 75 000 so'm", "4,5 kg (3 litr) - 220 000", "7.5kg(5litr) - 370 000"];


$data = $telegram->getData();

$message = $data['message'];
$chat_id = $message['chat']['id'];
$text = $message['text'];

if ($text == '/start') {
    showMain();
} else {
    switch (getPage($chat_id)) {
        case 'main':
            if ($text === "🍯 Batafsil malumot") {
                getAbout();
            } elseif ($text === "🍯 Buyurtma berish") {
                showProducts();
            } else {
                chooseButtons();
            }
            break;
        case 'massa':
            if (in_array($text, $orderTypes)) {
                setMass($chat_id, $text);
                getOrder();
            } elseif ($text == 'orqaga') {
                showMain();
            } else {
                chooseButtons();
            }
            break;
        case 'phone':
            if ($message['contact']['phone_number'] != "") {
                setPhone($chat_id, $message['contact']['phone_number']);
                showDeleveryType();
            } elseif ($text == 'orqaga') {
                showProducts();
            } else {
                setPhone($chat_id, $text);
            }
            break;
        case 'delevery':
            if ($text == '✈ Yetkazib berish ✈') {
                getLocation();
            } elseif ($text == 'orqaga') {
                getOrder();
            } elseif ($text == '🚶 Olib ketish 🚶') {
                showReady();
            } else
                chooseButtons();
            break;
        case 'location':
            if ($message['location']['latitude'] != "") {
                setLatitude($chat_id, $message['location']['latitude']);
                setLongitude($chat_id, $message['location']['longitude']);
                showReady();
            } elseif ($text == "Locatsiya Jo'natmayman") {
                showReady();
            } elseif ($text == "orqaga") {
                showDeleveryType();
            } else {
                chooseButtons();
            }
            break;
        case 'ready':
            if ($text == 'Boshqa buyurtma berish') {
                showMain();
            } else
                chooseButtons();
            break;

    }
}
function showMain()
{
    global $telegram, $chat_id, $user_name;
    setPage($chat_id, 'main');
    $option = array(
        array($telegram->buildKeyboardButton("🍯 Batafsil malumot")),
        array($telegram->buildKeyboardButton("🍯 Buyurtma berish")));
    $keyb = $telegram->buildKeyBoard($option, $onetime = true, $resize_keyboard = true);
    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Assalomu alaykum  ${user_name} botimizga xush kelibsiz");
    $telegram->sendMessage($content);
}
function showProducts()
{
    global $telegram, $chat_id;
    setPage($chat_id, 'massa');
    $option = array(
        array($telegram->buildKeyboardButton("1kg - 50 000 so'm")),
        array($telegram->buildKeyboardButton("1.5kg(1 litr)  - 75 000 so'm")),
        array($telegram->buildKeyboardButton("4,5 kg (3 litr) - 220 000")),
        array($telegram->buildKeyboardButton("7.5kg(5litr) - 370 000")),
        array($telegram->buildKeyboardButton("orqaga")));

    $keyb = $telegram->buildKeyBoard($option, $onetime = false, $resize_keyboard = true);
    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Bizda Toshkent bo'ylab yetkazib berish ximati bor.");
    $telegram->sendMessage($content);
}
function getOrder()
{
    global $telegram, $chat_id;
    setPage($chat_id, 'phone');
    $option = array(
        array($telegram->buildKeyboardButton("Raqam jo'natish", true)),
        array($telegram->buildKeyboardButton("orqaga")));
    $keyb = $telegram->buildKeyBoard($option, $onetime = false, $resize_keyboard = true);
    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Hajm tanlandi. Endi telefon raqamizngizni yuborishiingzi kerak");
    $telegram->sendMessage($content);
}
function showDeleveryType()
{
    global $telegram, $chat_id;
    setPage($chat_id, 'delevery');
    $option = array(
        array($telegram->buildKeyboardButton("✈ Yetkazib berish ✈")),
        array($telegram->buildKeyboardButton("🚶 Olib ketish 🚶")),
        array($telegram->buildKeyboardButton("orqaga")));
    $keyb = $telegram->buildKeyBoard($option, $onetime = true, $resize_keyboard = true);
    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Mahsulotni yetkazish berish ximatidan foydalanasizmi yoki olib ketasizmi!");
    $telegram->sendMessage($content);
}
function getLocation()
{
    global $telegram, $chat_id;
    setPage($chat_id, 'location');
    $option = [
        [$telegram->buildKeyboardButton("Locatsiya Jo'natish", false, true)],
        [$telegram->buildKeyboardButton("Locatsiya Jo'natmayman")],
        [$telegram->buildKeyboardButton("orqaga")]
    ];
    $keyb = $telegram->buildKeyBoard($option, $onetime = false, $resize_keyboard = true);

    $content = [
        'chat_id' => $chat_id,
        'reply_markup' => $keyb,
        'text' => 'Lakatsiya yuborishingiz mumkin yoki Yubormasigingiz mumkin '
    ];
    $telegram->sendMessage($content);
}
function showReady()
{
    global $telegram, $chat_id, $Admin_Chat_Id, $user_name;
    setPage($chat_id, 'ready');
    $option = array(
        array($telegram->buildKeyboardButton("Boshqa buyurtma berish"))
    );

    $keyb = $telegram->buildKeyBoard($option, $onetime = false, $resize_keyboard = true);
    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => 'Sizning buyurtmangiz qabul qilindi. Sizga aloqaga chiqmiz. Hizmatdan foydalganiz uchun rahmat');
    $telegram->sendMessage($content);

    $text= "Yangi buyurtma keldi!!!";
    $text.= "\n";
    $text.="Ismi : ".$user_name;
    $text.= "\n";
    $text.="Hajm: ".getMass($chat_id);
    $text.= "\n";
    $text.="Telefon raqam: ".getPhone($chat_id);
    $text.= "\n";


    $content = array('chat_id' => $Admin_Chat_Id, 'text' => $text);
    $telegram->sendMessage($content);

    if (getLatitude($chat_id)!=""){
        $content = array('chat_id' => $Admin_Chat_Id, 'latitude' => getLatitude($chat_id),'longitude' => getLongitude($chat_id) );
        $telegram->sendLocation($content);
    }
}
function getAbout()
{
    global $telegram, $chat_id;
    $content = array('chat_id' => $chat_id, 'text' => "Biz haqimizdagi batafsil malumot. <a href='https://telegra.ph/Biz-Haqimizda-06-27'>Havola</a>", 'parse_mode' => 'html');
    $telegram->sendMessage($content);
}
function chooseButtons()
{
    global $telegram, $chat_id;
    $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => 'Iltomos tugmalardan birini tanlang ',
    ]);
}
