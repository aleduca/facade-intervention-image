<?php

use core\library\Session;

Session::flash('error', 'Error, user not found', '<i class="bi bi-exclamation-circle"></i> ');

redirect('/user/create');
