<?php 

require_once __DIR__ . '/vendor/autoload.php';

use Driver\BackEnd\App;

App::init($_SERVER["REQUEST_URI"]);