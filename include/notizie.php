<?php
include_once __DIR__.'/dbConn.php';

class Notizie {
  public static function get(){
    $db = Database::getInstance();
    $id = $_SESSION['id'];
    $q = mysql_query(
    "SELECT notizie.id, utenti.immagine, notizie.testo, notizie.data, notizie.`like`,
    notizie.dislike, utenti.nome_cognome, commenti.testo AS testo_comm,
    commenti.data AS data_comm, utente_commento.nome_cognome AS uc_nome_cognome,
    utente_commento.id AS uc_id, utente_commento.immagine AS uc_immagine
    FROM
    (((notizie INNER JOIN utenti ON notizie.id_utente = utenti.id)
    LEFT JOIN commenti ON commenti.id_notizia = notizie.id)
    LEFT JOIN utenti AS utente_commento ON utente_commento.id = commenti.id_utente)
    INNER JOIN amicizia ON ($id = amicizia.amico1 AND utenti.id = amicizia.amico2)
      OR ($id = amicizia.amico2 AND utenti.id = amicizia.amico1)

    WHERE amicizia.accettata = 1

    ORDER BY notizie.id
    DESC;", $db->conn
    ) or die('Problema nel ricevere le notizie dal database: '. mysql_error());

    if (mysql_num_rows($q) > 0){
      $notizie = '';
      $oldid = 0;
      $i = 0;

      while(($row = mysql_fetch_object($q))){
        $row->testo = stripslashes($row->testo);
        $row->testo_comm = stripslashes($row->testo_comm);


        if ($oldid !== $row->id){
          $oldid = $row->id;

          if ($i !== 0) {
            $notizie .= '</div>';
          }
          else {
            ++$i;
          }

          $notizie .= "<div class='notizia'>
            <div class='header_n'>
              <img src='./asset/img/$row->immagine' alt='$row->nome_cognome' />
            <div>
            <p>$row->nome_cognome</p>
            <p>$row->data</p>
            </div>
          </div>
          <p>$row->testo</p>

          <div class='like'>
            <a href='#' onClick='like(\"like\",$row->id, this)'>
            <i class='fa fa-thumbs-o-up'></i></a>(<span class='like_n'>$row->like</span>)
            <a href='#' onClick='like(\"dislike\",$row->id, this)'>
          <i class='fa fa-thumbs-o-down'></i></a> (<span class='dislike_n'>$row->dislike</span>)</div>
          <div class='manda_commento'>
            <p>Commenta la notizia</p>
            <form action='?a=commenti' method='POST'>
              <textarea name='testo'></textarea><br />
              <input type='hidden' name='id' value='$row->id' />
              <button>Commenta</button>
            </form>
          </div>
          ";
        }
        else {
          if($row->uc_nome_cognome != "") {
            $notizie .= "<div class='commenti'><div class='header_n'>
            <img src='./asset/img/$row->uc_immagine' alt='$row->uc_nome_cognome' />
            <div>
            <p>$row->uc_nome_cognome</p>
            <p>$row->data_comm</p>
            </div>
            </div>
            <p>$row->testo_comm</p>
            </div>";
          }
        }

      }

      $notizie .= "</div>";
      return $notizie;
    }
    else {
      return null;
    }
  }
  public static function getStato(){
    if(!isset($_SESSION['id'])){
      return;
    }
    $db = Database::getInstance();
    if (isset($_SESSION['stato'])) {
      return $_SESSION['stato'];
    }

    $q = mysql_query('SELECT notizie.testo FROM notizie WHERE id_utente='.$_SESSION['id'].' ORDER BY id DESC LIMIT 1;', $db->conn)
     or die(mysql_error());

    if(mysql_num_rows($q) > 0){
      $user_obj = mysql_fetch_object($q);
      $user_obj->testo = stripslashes($user_obj->testo);
      $_SESSION['stato'] = $user_obj->testo;
      return $user_obj->testo;
    }
    else {
      return null;
    }
  }

  public static function setStato($status){
    if(!isset($_SESSION['id'])){
      return;
    }

    $db = Database::getInstance();
    $l = strlen($status);
    if($l == 0 || $l > 140) {
      return false;
    }
    if(preg_match('/(\||\+|(\-\-)|\=|<|>|(!=)|\(|\)|\%|\*)/', $status)){
      return false;
    }

    $status = mysql_real_escape_string(htmlentities($status));

    $_SESSION['stato'] = $status;
    $id = $_SESSION['id'];
    $db = Database::getInstance();

    mysql_query("INSERT INTO notizie (testo, id_utente) VALUES ('$status', '$id')", $db->conn)
    or die("Problema con l'update del messaggio: ". mysql_error());

    return true;
  }

  public static function like($id, $tipo){
    $db = Database::getInstance();

    $id = intval($id);
    $tipo = intval($tipo);
    $utente = $_SESSION['id'];

    $like_disl = ($tipo == 0) ? 'like' : 'dislike';

    mysql_query("INSERT INTO `like` (id_utente, id_notizia, `like`) VALUES ($utente, $id, $tipo );") or die(mysql_error());

    mysql_query("UPDATE notizie SET `$like_disl` = `$like_disl` + 1 WHERE id=$id", $db->conn)
    or die("Problema con il like: ". mysql_error());

    return 'ok';
  }

  public static function getLastNotizie(){
    // questa funzione è solamente per gli admin
    if(!isset($_SESSION['admin'])) return;

    $db = Database::getInstance();

    $content = '<table id="admin_notizie"><tr><td>Notizia</td><td></td>';

    // prendo le ultime 30 notizie
    $q = mysql_query('SELECT id, testo FROM notizie ORDER BY id ASC LIMIT 30');

    while ($row = mysql_fetch_object($q)) {
      $content .= "<tr><td>$row->testo</td><td><a class='button'
      href='javascript:Admin.eliminaNotizia($row->id)'><i class='fa fa-times'></i></a></td></tr>";
    }

    $content .= '</table>';

    return $content;
  }

  public static function eliminaNotizia($id){
    $db = Database::getInstance();

    $q = mysql_query("DELETE FROM notizie WHERE id = $id LIMIT 1;") or die (mysql_error());

    return 'ok';
  }
}
