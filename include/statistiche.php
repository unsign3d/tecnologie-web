<?php
include_once __DIR__.'/dbConn.php';

/* Accessi giornalieri
 * SELECT count(id) FROM tweb.access_log WHERE id_user = 1 AND date_format(date_access, '%Y-%m-%d') = date_format(now(), '%Y-%m-%d');
 *
 * Accessi mensili
 * SELECT count(id) FROM tweb.access_log WHERE id_user = 1 AND date_format(date_access, '%Y-%m') = date_format(now(), '%Y-%m')
 *
 QUERY COMPLETA
 SELECT * FROM
 (SELECT count(id) as nome FROM access_log WHERE id_user = 1 AND date_format(date_access, '%Y-%m-%d') = date_format(now(), '%Y-%m-%d')) as daily_at,
 (SELECT count(id) as monthly_acc FROM access_log WHERE id_user = 1 AND date_format(date_access, '%Y-%m') = date_format(now(), '%Y-%m')) as monthly_at,
 (SELECT count(id) as weekly_acc FROM notizie WHERE id_utente = 1 AND data between date_sub(now(),INTERVAL 1 WEEK) and now()) as weekly_at,
 (SELECT count(id) as monthly_post FROM notizie WHERE id_utente = 1 AND date_format(data, '%Y-%m') = date_format(now(), '%Y-%m')) as monthly_pt,
 (SELECT count(id) as yearly_post FROM notizie WHERE id_utente = 1 AND date_format(data, '%Y') = date_format(now(), '%Y')) as yearly_pt,
 (SELECT count(id) as nlike FROM `like` WHERE id_utente = 2) as like_t
 */

class Statistiche {

  public static function get($stat){
    switch ($stat) {
      case 'da':
        return Statistiche::getDailyAcc();
      case 'ma':
        return Statistiche::getMonthlyAcc();
      case 'wa':
        return Statistiche::getWeeklyAcc();
      case 'mp':
        return Statistiche::getMonthlyPosts();
      case 'yp':
        return Statistiche::getYearlyPosts();
      case 'like':
        return Statistiche::getLikes();
      default:
        return null;
    }
  }
  public static function getDailyAcc(){
    $db = Database::getInstance();
    $id = $_SESSION['id'];
    $q = mysql_query("SELECT count(access_log.id) as contatore, utenti.nome_cognome as nome
    FROM (access_log INNER JOIN amicizia
      ON ($id = amicizia.amico1 AND id_user = amicizia.amico2)
      OR ($id = amicizia.amico2 AND id_user = amicizia.amico1)
    ) INNER JOIN utenti ON access_log.id_user = utenti.id
    WHERE date_format(date_access, '%Y-%m-%d') = date_format(now(), '%Y-%m-%d')
    GROUP BY id_user") or die (mysql_error());

    $res = array();


    if(mysql_num_rows($q) > 0) {
      while($row = mysql_fetch_assoc($q)){
        array_push($res, $row);
      }

      return json_encode($res);
    }
    else {
      return null;
    }

  }

  public static function getMonthlyAcc() {
    $db = Database::getInstance();
    $id = $_SESSION['id'];
    $q = mysql_query("SELECT count(access_log.id) as contatore, utenti.nome_cognome as nome
    FROM (access_log INNER JOIN amicizia
      ON ($id = amicizia.amico1 AND id_user = amicizia.amico2)
      OR ($id = amicizia.amico2 AND id_user = amicizia.amico1)
    ) INNER JOIN utenti ON access_log.id_user = utenti.id
    WHERE date_format(date_access, '%Y-%m') = date_format(now(), '%Y-%m')
    GROUP BY id_user") or die (mysql_error());

    $res = array();

    if(mysql_num_rows($q) > 0) {
      while($row = mysql_fetch_assoc($q)){
        array_push($res, $row);
      }

      return json_encode($res);
    }
    else {
      return null;
    }
  }

  public static function getWeeklyAcc() {
    $db = Database::getInstance();
    $id = $_SESSION['id'];
    $q = mysql_query("SELECT count(access_log.id) as contatore, utenti.nome_cognome as nome
    FROM (access_log INNER JOIN amicizia
      ON ($id = amicizia.amico1 AND id_user = amicizia.amico2)
      OR ($id = amicizia.amico2 AND id_user = amicizia.amico1)
    ) INNER JOIN utenti ON access_log.id_user = utenti.id
    WHERE date_access BETWEEN date_sub(now(),INTERVAL 1 WEEK) and now()
    GROUP BY id_user") or die (mysql_error());

    $res = array();

    if(mysql_num_rows($q) > 0) {
      while($row = mysql_fetch_assoc($q)){
        array_push($res, $row);
      }
      return json_encode($res);
    }
    else {
      return null;
    }
  }

  public static function getMonthlyPosts(){
    $db = Database::getInstance();
    $id = $_SESSION['id'];
    $q = mysql_query("SELECT count(notizie.id) as contatore, utenti.nome_cognome as nome
    FROM (notizie INNER JOIN amicizia
      ON ($id = amicizia.amico1 AND id_utente = amicizia.amico2)
      OR ($id = amicizia.amico2 AND id_utente = amicizia.amico1)
    ) INNER JOIN utenti ON notizie.id_utente = utenti.id
    WHERE date_format(data, '%Y-%m') = date_format(now(), '%Y-%m')
    GROUP BY id_utente;") or die (mysql_error());

    $res = array();

    if(mysql_num_rows($q) > 0) {
      while($row = mysql_fetch_assoc($q)){
        array_push($res, $row);
      }
      return json_encode($res);
    }
    else {
      return null;
    }
  }

  public static function getYearlyPosts(){
    $db = Database::getInstance();
    $id = $_SESSION['id'];
    $q = mysql_query("SELECT count(notizie.id) as contatore, utenti.nome_cognome as nome
    FROM (notizie INNER JOIN amicizia
      ON ($id = amicizia.amico1 AND id_utente = amicizia.amico2)
      OR ($id = amicizia.amico2 AND id_utente = amicizia.amico1)
    ) INNER JOIN utenti ON notizie.id_utente = utenti.id
    WHERE date_format(data, '%Y') = date_format(now(), '%Y')
    GROUP BY id_utente;") or die (mysql_error());

    $res = array();

    if(mysql_num_rows($q) > 0) {
      while($row = mysql_fetch_assoc($q)){
        array_push($res, $row);
      }
      return json_encode($res);
    }
    else {
      return null;
    }
  }

  public static function getLikes(){
    $db = Database::getInstance();
    $id = $_SESSION['id'];
    $q = mysql_query("SELECT count(`like`.id) as contatore, utenti.nome_cognome as nome
    FROM `like` INNER JOIN amicizia
    ON ($id = amicizia.amico1 AND id_utente = amicizia.amico2)
    OR ($id = amicizia.amico2 AND id_utente = amicizia.amico1)
    INNER JOIN utenti ON id_utente = utenti.id
    GROUP BY id_utente") or die (mysql_error());

    $res = array();

    if(mysql_num_rows($q) > 0) {
      while($row = mysql_fetch_assoc($q)){
        array_push($res, $row);
      }
      return json_encode($res);
    }
    else {
      return null;
    }
  }

}
