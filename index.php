<?php

/*
* This file is part of GeeksWeb Bot (GWB).
*
* GeeksWeb Bot (GWB) is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License version 3
* as published by the Free Software Foundation.
* 
* GeeksWeb Bot (GWB) is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.  <http://www.gnu.org/licenses/>
*
* Author(s):
*
* © 2015 Kasra Madadipouya <kasra@madadipouya.com>
*
*/
require 'vendor/autoload.php';

$client = new Zelenin\Telegram\Bot\Api('214939698:AAEGmpZkp_81hnh6xcfImgJzB49xTFqw1eY'); // Set your access token
$url = 'http://yisc-alazhar.or.id/feed'; // URL RSS feed
$update = json_decode(file_get_contents('php://input'));

//your app
try {

    if($update->message->text == '/sosmed')
    {
    	$response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
    	$response = $client->sendMessage([
        	'chat_id' => $update->message->chat->id,
        	'text' => "Ngga mau ketinggalan dengan berbagai info dan update dari YISC Al Azhar? Follow aja nih : \n
			Facebook : http://facebook.com/yisc.alazhar\n
			Twitter  : http://twitter.com/yisc_alazhar\n
			Google+  : https://plus.google.com/103786599270861299742\n
			Youtube  : https://www.youtube.com/channel/UCLGTGGY_KFCAtb11zhy6xHA
			"
     	]);
    }
    else if($update->message->text == '/salam')
    {
    	$response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
    	$response = $client->sendMessage([
    		'chat_id' => $update->message->chat->id,
    		'text' => "Wa'alaikumussalaam Warahmatullahi Wabarakaatuh \n
			Semoga Allah SWT senantiasa melimpahkan rahmat dan karunia-Nya kepada kita semua dalam menjalankan aktivitas sehari-hari, Amiin. \n
			Untuk daftar perintah silahkan ketik /help"
    	]);

    }
    else if($update->message->text == '/help')
    {
		$response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
		$response = $client->sendMessage([
			'chat_id' => $update->message->chat->id,
			'text' => "Daftar Perintah Marbot YISC Al Azhar\n
			/salam - Dapatkan informasi terbaru dari YISC Al Azhar
			/beye - Berita dan Artikel Terbaru dari website www.yisc-alazhar.or.id
			/sosmed - Daftar Sosial Media YISC Al Azhar
			"
		]);
    }
    else if($update->message->text == '/beye')
    {
		Feed::$cacheDir 	= __DIR__ . '/cache';
		Feed::$cacheExpire 	= '5 hours';
		$rss 		= Feed::loadRss($url);
		$items 		= $rss->item;
		$lastitem 	= $items[0];
		$lastlink 	= $lastitem->link;
		$lasttitle 	= $lastitem->title;
		$message = $lasttitle . " \n ". $lastlink;
		$response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
		$response = $client->sendMessage([
				'chat_id' => $update->message->chat->id,
				'text' => $message
		]);
    }
    else if($update->message->text == '/start')
    {
    	$response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
    	$response = $client->sendMessage([
    		'chat_id' => $update->message->chat->id,
    		'text' => "Assalaamu'alaikum Warahmatullahi Wabarakaatuh \n
			Perkenalkan saya adalah Marbot YISC Al Azhar yang akan membantu kamu mendapatkan informasi terbaru seputar YISC Al Azhar. \n
			Untuk memulai, silahkan ketik /salam"
    		]);
    }
    else
    {
    	$response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
    	$response = $client->sendMessage([
    		'chat_id' => $update->message->chat->id,
    		'text' => "Hai... untuk daftar perintah, silahkan ketik /help"
    		]);
    }

} catch (\Zelenin\Telegram\Bot\NotOkException $e) {

    //echo error message ot log it
    //echo $e->getMessage();

}
