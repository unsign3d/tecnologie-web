<?php

include_once __DIR__.'/include/notizie.php';
include_once __DIR__.'/include/user.php';
include_once __DIR__.'/include/statistiche.php';

if (!isset($_GET['a'])) header('Location: ?a=home');

$action = $_GET['a'];

session_start();

// se l'utente non è connesso può solo effettuare il login o la registrazione
if(!isset($_SESSION['id']) && $action != 'registrazione'){
  $action = 'login';
}
// se l'utente è amministratore può effettuare alcune cose
if(isset($_SESSION['admin']) && $_GET['a'] == 'amici' && isset($_GET['id'])){
  $action = $_GET['a'];
}

$content = '404 Pagina non trovata';
$stato = Notizie::getStato();

switch($action){

  case 'login':
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $content = file_get_contents(__DIR__.'/tema/login.html');
    }
    else if ($_SERVER['REQUEST_METHOD'] === 'POST'){
      die(Utente::login());
    }
  break;

  case 'home':
    $content = $content = Utente::displayUser(intval($_SESSION['id']));;
  break;

  case 'statistiche':
    $content = file_get_contents(__DIR__.'/tema/statistiche.html');
  break;

  case 'asyncupload':
    die(Utente::updateFoto(Utente::uploadFoto(intval($_SESSION['id'])), intval($_SESSION['id'])));
  break;

  case 'statistiche_json':
    die(Statistiche::get($_GET['t']));
  break;

  case 'contatti':
    $content = file_get_contents(__DIR__.'/tema/contatti.html');
  break;

  case 'amici':
    if(!isset($_GET['id'])){
      $content = Utente::displayList('amici');
    }
    else {
      $content = Utente::displayUser(intval($_GET['id']));
    }
  break;

  case 'stato':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if(Notizie::setStato($_POST['stato'])) {
        die ('ok');
      }
      else{
        die ('no');
      }
    }

  case 'notizie':
    $content = Notizie::get();
    break;

  case 'commenti':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      include __DIR__.'/include/commenti.php';
      if(Commenti::insert(intval($_POST['id']), $_POST['testo'])) {
        die (header('Location: ?a=notizie'));
      }
      else{
        die ('no');
      }
    }
  break;

  case 'amicizie':
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      die(Utente::creaAmicizia(intval($_GET['id'])));
    }
    else {
      die(Utente::accettaAmicizia(intval($_POST['id'])));
    }
  break;

  case 'richiesta_amicizia':
    $content = Utente::getRichiestaAmicizia(intval($_SESSION['id']));
  break;
  case 'like':
    die(Notizie::like(intval($_GET['id']), 0));
    break;

  case 'dislike';
    die (Notizie::like(intval($_GET['id']), 1));
  break;

  case 'logout':
    Utente::logout();
    die(header('Location: ?a=login'));
  break;

  case 'registrazione':
  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $content = file_get_contents(__DIR__.'/tema/registrazione.html');
  }
  else {
    die(Utente::registerUser());
  }
  break;

  case 'profilo':
  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $content = Utente::getPanel($_SESSION['id']);
  }
  else {
    die(Utente::updateUser($_SESSION['id']));
  }
  break;

  case 'richieste_amicizia':
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $content = Utente::getRichiestaAmicizia(intval($_SESSION['id']));
    }
    else {
      die(Utente::accettaAmicizia($_POST['amico']));
    }
  break;

  case 'richiedi_amicizia':
    die(Utente::creaAmicizia($_GET['id']));
  break;

  case 'search':
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
      $content = file_get_contents(__DIR__.'/tema/cerca.html');
    }
    else {
      die(Utente::cercaUtente($_POST['parametro'], $_POST['ordinamento']));
    }
  default:
    break;
}

include __DIR__.'/tema/default.php';
