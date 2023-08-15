<?php

use app\database\model\User;
use core\facades\UploadImage;
use core\library\Session;

$image = new UploadImage;
$image->make()
->watermark('watermark.png', width:50, height:50, x:10, y:10)
->resize(400, null, true)
->execute();

$info = $image->get_image_info();

$user = new User;
remove_file($user->find('id', 5)->image);
$user->update([
    'image' => $info['path'],
], ['id' => 5]);


Session::flash('message', 'Upload successfully');

redirect('/');
