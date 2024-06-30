<?php
include 'Telegram.php';

$telegram = new Telegram("6395779599:AAFd9anUak6ZoY50vEwIeUwd-Wik7i3dYpQ");
$user_name = $telegram->FirstName();

$data = $telegram->getData();
//$telegram->sendMessage([
//    'chat_id' => $telegram->ChatID(),
//    'text' => json_encode($data, JSON_PRETTY_PRINT),
//]);

$chat_id = $data['message']['chat']['id'];
$text = $data['message']['text'];
$message = $data['message'];

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
    case '✈ Yetkazib berish ✈':
        getLocation();
        break;
    case '🚶 Olib ketish 🚶':
        $telegram->sendMessage([
            'chat_id' => $chat_id,
            'text' => "Xizmatdan foydalnganiz uchun rahmat Sizga Adminlarimiz aloqaga chiqadi",
        ]);
        getStart();
        break;
    case 'orqaga':
        switch (file_get_contents('users/step.txt')) {
            case 'start':
                break;
            case 'order':
                getStart();
            case 'delevery':
                getStart();
                break;
            case 'phone':
                showProducts();
                break;
            case 'location':
                showDeleveryType();
                break;
        }
    default:
        if (in_array($text, $orderTypes)) {
            file_put_contents('users/massa.txt', $text);
            getOrder();
        } else {
            switch (file_get_contents('users/step.txt')) {
                case 'phone':
                    if ($message['contact']['phone_number'] != "") {
                        file_put_contents('users/phone.txt', $message['contact']['phone_number']);
                    } else {
                        file_put_contents('users/phone.txt', $text);
                    }
                    showDeleveryType();
                case 'location':
                    if ($message['location'] != "") {
                        file_put_contents('users/location.txt', json_encode($message['location']));
                        $telegram->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => "Xizmatdan foydalnganiz uchun rahmat sizga Adminlarimiz aloqaga chiqadi",
                        ]);
                        getStart();
                    } elseif(file_get_contents('users/location.txt') == "Locatsiya Jo'natmayman") {
                        $telegram->sendMessage([
                            'chat_id' => $chat_id,
                            'text' => "Xizmatdan foydalnganiz uchun rahmat sizga Adminlarimiz aloqaga chiqadi",
                        ]);
                        getStart();
                    }
                    break;
            }
        }
        break;
}

function getOrder()
{
    global $telegram, $chat_id;
    file_put_contents('users/step.txt', 'phone');
    $option = array(
        array($telegram->buildKeyboardButton("Raqam jo'natish", true)),
        array($telegram->buildKeyboardButton("orqaga")));
    $keyb = $telegram->buildKeyBoard($option, $onetime = false, $resize_keyboard = true);
    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Hajm tanlandi. Endi telefon raqamizngizni yuborishiingzi kerak");
    $telegram->sendMessage($content);
}


function getLocation()
{
    global $telegram, $chat_id;
    file_put_contents('users/step.txt', 'location');

    $option = [
        [$telegram->buildKeyboardButton("Locatsiya Jo'natish", false, true)],
        [$telegram->buildKeyboardButton("Locatsiya Jo'natmayman")],
        [$telegram->buildKeyboardButton("orqaga")]
    ];
    $keyb = $telegram->buildKeyBoard($option, $onetime = false, $resize_keyboard = true);

    $content = [
        'chat_id' => $chat_id,
        'reply_markup' => $keyb,
        'text' => 'Lakatsiya junatsuih'
    ];
    $telegram->sendMessage($content);
}

function showProducts()
{
    global $telegram, $chat_id;
    file_put_contents('users/step.txt', 'order');
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

function getAbout()
{
    global $telegram, $chat_id;
    $content = array('chat_id' => $chat_id, 'text' => "Biz haqimizdagi batafsil malumot. <a href='https://telegra.ph/Biz-Haqimizda-06-27'>Havola</a>", 'parse_mode' => 'html');
    $telegram->sendMessage($content);
}

function getStart()
{
    global $telegram, $chat_id, $user_name;
    file_put_contents('users/step.txt', 'start');
    $option = array(
        array($telegram->buildKeyboardButton("🍯 Batafsil malumot")),
        array($telegram->buildKeyboardButton("🍯 Buyurtma berish")));
    $keyb = $telegram->buildKeyBoard($option, $onetime = true, $resize_keyboard = true);
    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Assalomu alaykum  ${user_name} botimizga xush kelibsiz");
    $telegram->sendMessage($content);
}

function showDeleveryType()
{
    global $telegram, $chat_id;
    file_put_contents('users/step.txt', 'delevery');
    $option = array(
        array($telegram->buildKeyboardButton("✈ Yetkazib berish ✈")),
        array($telegram->buildKeyboardButton("🚶 Olib ketish 🚶")),
        array($telegram->buildKeyboardButton("orqaga")));
    $keyb = $telegram->buildKeyBoard($option, $onetime = true, $resize_keyboard = true);
    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Mahsulotni yetkazish berish ximatidan foydalanasizmi yoki olib ketasizmi!");
    $telegram->sendMessage($content);
}
