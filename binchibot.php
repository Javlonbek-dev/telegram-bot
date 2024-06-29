<?php
include 'Telegram.php';

$telegram = new Telegram("6395779599:AAFd9anUak6ZoY50vEwIeUwd-Wik7i3dYpQ");
$chat_id = $telegram->ChatID();
$user_name = $telegram->FirstName();
$text = $telegram->Text();
$orderTypes = ["1kg - 50 000 so'm", "1.5kg(1 litr)  - 75 000 so'm", "4,5 kg (3 litr) - 220 000", "7.5kg(5litr) - 370 000"];
switch ($text) {
    case '/start':
        getStart();
        break;
    case '🍯 Batafsil malumot':
        getAbout();
        break;
    case '🍯 Buyurtma berish':
        showProducts();
        break;
    default:
        if (in_array($text, $orderTypes)) {
            getOrder();
        }
        break;
}

function getOrder()
{
    global $telegram, $chat_id;
    $option = array(
        array($telegram->buildKeyboardButton("Raqam jo'natish", true)));
    $keyb = $telegram->buildKeyBoard($option, $onetime = false, $resize_keyboard = true);
    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Hajm tanlandi. Endi telefon raqamizngizni yuborishiingzi kerak");
    $telegram->sendMessage($content);
}

function showProducts()
{
    global $telegram, $chat_id;
    $option = array(
        array($telegram->buildKeyboardButton("1kg - 50 000 so'm")),
        array($telegram->buildKeyboardButton("1.5kg(1 litr)  - 75 000 so'm")),
        array($telegram->buildKeyboardButton("4,5 kg (3 litr) - 220 000")),
        array($telegram->buildKeyboardButton("7.5kg(5litr) - 370 000")));

    $keyb = $telegram->buildKeyBoard($option, $onetime = false, $resize_keyboard = true);
    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Bizda Toshkent bo'ylab yetkazib berish ximati bor.");
    $telegram->sendMessage($content);
}

function getAbout()
{
    global $telegram, $chat_id;
    $content = array('chat_id' => $chat_id, 'text' => "Biz haqimizdagi batafsil malumot. <a href='https://telegra.ph/Biz-Haqimizda-06-27'>Havola</a>", 'parse_mode' => 'html');
    $telegram->sendMessage($content);
}

function getStart()
{
    global $telegram, $chat_id, $user_name;
    $option = array(
        array($telegram->buildKeyboardButton("🍯 Batafsil malumot")),
        array($telegram->buildKeyboardButton("🍯 Buyurtma berish")));
    $keyb = $telegram->buildKeyBoard($option, $onetime = true, $resize_keyboard = true);
    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "This is a Keyboard Test ${user_name}");
    $telegram->sendMessage($content);
}

