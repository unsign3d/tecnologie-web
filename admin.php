<?php
include_once __DIR__.'/include/amministratore.php';
include_once __DIR__.'/include/notizie.php';

if (!isset($_GET['p'])) header('Location: admin.php?p=home');

$action = $_GET['p'];

session_start();

if(!isset($_SESSION['admin']) || $_SESSION['admin'] == null){
  $action = 'login';
}

switch($action){
  case 'login':
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $content = file_get_contents(__DIR__.'/tema/login_admin.html');
    }
    else if ($_SERVER['REQUEST_METHOD'] === 'POST'){
      die(Amministratore::login());
    }
  break;
  case 'list':
    $content = Amministratore::listUser();
    break;
  case 'modifica':
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $content = Utente::getPanel(intval($_GET['id']));
    }
    else {
      die(Utente::updateUser(intval($_GET['id'])));
    }
    break;
  case 'asyncupload':
    die(Utente::updateFoto(Utente::uploadFoto(intval($_GET['id'])), intval($_GET['id'])));
  break;
  case 'notizie':
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $content = Notizie::getLastNotizie();
    }
    else {
      die(Notizie::eliminaNotizia(intval($_POST['id'])));
    }
    break;
  case 'logout':
    die(Amministratore::logout());
    break;
  case 'elimina':
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    die(Utente::deleteUser(intval($_POST['id'])));
  }
  break;
  default:
    $content = '404 not found';
}

include __DIR__.'/tema/default.php';
