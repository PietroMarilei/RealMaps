<?php
namespace Driver\BackEnd\Controllers;

abstract class Controller {
   abstract public function run();
}

// function a(Controller  $c) {
//    $c->run(/)
// }

// a(UpdateData)

// polimorfismo, serve a poter scrivere funct generiche e poterle usare con il controller specifico dentro