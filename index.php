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
        	'text' => "Ngga mau ketinggalan dengan berbagai info dan update dari YISC Al Azhar? Follow aja nih :
Facebook : http://facebook.com/yisc.alazhar
Twitter  : http://twitter.com/yisc_alazhar
Google+  : https://plus.google.com/103786599270861299742
Youtube  : https://www.youtube.com/channel/UCLGTGGY_KFCAtb11zhy6xHA
			"
     	]);
    }
    else if($update->message->text == '/salam')
    {
		$randomAyah = getRandomAyah();
		
    	$response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
    	$response = $client->sendMessage([
    		'chat_id' => $update->message->chat->id,
    		'text' => "Wa'alaikumussalaam Warahmatullahi Wabarakaatuh\n
Inspirasi harian : $randomAyah\n
Untuk mengetahui cara berinteraksi dengan Marbot YISC Al Azhar, silahkan ketik /help"
    	]);

    }
    else if($update->message->text == '/help')
    {
		$response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
		$response = $client->sendMessage([
			'chat_id' => $update->message->chat->id,
			'text' => "Daftar Perintah Marbot YISC Al Azhar
/salam - Dapatkan informasi terbaru dari YISC Al Azhar
/beye - Berita dan Artikel Terbaru dari website www.yisc-alazhar.or.id
/inspirasi - Inspirasi dari ayat suci Al Qur'an khusus untuk kamu
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
    		'text' => "Assalaamu'alaikum Warahmatullahi Wabarakaatuh
Perkenalkan saya adalah Marbot YISC Al Azhar yang akan membantu kamu mendapatkan informasi terbaru seputar YISC Al Azhar.
Untuk memulai, silahkan ketik /salam"
    		]);
    }
    else if($update->message->text == '/inspirasi')
    {
		$text = "Inspirasi Ayat Suci Al Qur'an\n";
		$text .= getRandomAyah();

    	$response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
    	$response = $client->sendMessage([
    		'chat_id' => $update->message->chat->id,
    		'text' => $text
    		]);
    }
    else if($update->message->text == '/waktushalat')
    {
		$text = "'afwan, fitur ini belum tersedia";//getShalatTime();

    	$response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
    	$response = $client->sendMessage([
    		'chat_id' => $update->message->chat->id,
    		'text' => $text
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

function getApi($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	$result = curl_exec($ch);
	curl_close($ch);
	
	return $result;
}

function getShalatTime(){
	$result = getApi('http://muslimsalat.com/jakarta.json?key=d9f9908ca4c7567ed473fb80dece7324');
	$shalat = json_decode($result);
	$waktu = $shalat['items'][0];
	
	$msg = "Waktu Shalat Jakarta dan Sekitarnya\n".date('j, d M Y')."\n";
	$msg .= "Shubuh  : ".$waktu['fajr']."\n";
	$msg .= "Terbit  : ".$waktu['shurooq']."\n";
	$msg .= "Zhuhur  : ".$waktu['dhuhr']."\n";
	$msg .= "Ashar   : ".$waktu['asr']."\n";
	$msg .= "Maghrib : ".$waktu['maghrib']."\n";
	$msg .= "Isya    : ".$waktu['isha'];
	
	return $msg;
}

function getRandomAyah(){
	$rand = rand(1,6236); // random ayah from 1:1 - 114:7
	
	$result = getApi('http://api.globalquran.com/ayah/'.$rand.'/id.indonesian?key=d1bdfe6421908ff4cfb71fd1e7630e0b');
	
	$data = json_decode($result,true);
	$data = $data['quran']['id.indonesian'];
	$line = array();
	foreach($data as $id=>$val)
		$line = $val;
	return $line['verse']."\n[QS. ".$line['surah'].":".$line['ayah']."]";
}