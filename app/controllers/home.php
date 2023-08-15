<?php

use app\database\model\User;

$user = new User;
$user = $user->find('id', 5);

// Session::session_flash('name', 'My name is Alexandre');

view('app/views/home.php', ['user' => $user]);
