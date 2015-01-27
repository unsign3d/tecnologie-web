<?php
class Commenti {

  public static function insert($id, $testo) {
      $db = Database::getInstance();
      $id = intval($id);
      $l = strlen($testo);
      if($l == 0 || $l > 140) {
        return false;
      }
      if(preg_match('/(\||\+|(\-\-)|\=|<|>|(!=)|\(|\)|\%|\*)/', $testo)){
        return false;
      }

      $testo = mysql_real_escape_string(htmlentities($testo));
      $id_utente = $_SESSION['id'];

      $q = mysql_query("INSERT INTO `commenti` (`testo`, `id_notizia`, `id_utente`)
        VALUES ('$testo', '$id', '$id_utente');", $db->conn)
        or die ('Impossibile inserire il commento: '. mysql_error());

      return 'ok';
  }

}
