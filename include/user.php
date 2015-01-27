<?php
  include_once __DIR__.'/dbConn.php';

  class Utente {

    public static function login(){
      $db = Database::getInstance();
      $user = preg_replace('/[^a-z0-9]\-/', '', strtolower($_POST['username']));
      $password = sha1($_POST['password']);

      $q = mysql_query(
        "SELECT * FROM utenti WHERE username = '$user' AND password = '$password' LIMIT 1;",
        $db->conn) or die(mysql_error());;
      if (mysql_num_rows($q) > 0){
        $obj = mysql_fetch_object($q);

        session_regenerate_id(TRUE);

        mysql_query("INSERT INTO access_log (id_user) VALUE ($obj->id);") or die(mysql_error());

        $_SESSION['name'] = $obj->username;
        $_SESSION['id'] = $obj->id;
        $_SESSION['level'] = 0;

        return 'ok';
      }
      else {
        die('username o password sbagliata');
      }
    }

    public static function logout(){
      session_destroy();
      session_regenerate_id();
      header('Locaion: ?a=home');
    }

    public static function get($utente){
      $utente = intval($utente);
      $db = Database::getInstance();
      $q = mysql_query("SELECT * FROM utenti WHERE id=$utente LIMIT 1", $db->conn) or die(mysql_error());

      if(mysql_num_rows($q) > 0) {
        return mysql_fetch_object($q);
      }
      else {
        return null;
      }
    }

    public static function displayUser($utente){
      $row = Utente::get($utente);

      if($row == null) return "Utente non trovato";

      return " <div id='content_header'>
      <ul id='menu'>
      <li><a href='#educazione'>EDUCAZIONE </a></li>
      <li><a href='#provenienza'>PROVENIENZA </a></li>
      <li><a href='#provenienza'>PROVENIENZA </a></li>
      <li><a href='#hobbies'>HOBBIES </a></li>
      </ul>
      </div>
      <div class='immagine_profilo'>
      <img src='./asset/img/$row->immagine' alt='$row->nome_cognome' />
      </div>
      <div class='intestazione_profilo'>
      <p>$row->nome_cognome</p>
      <p>$row->citta - $row->eta anni</p>
      </div>
      <div class='clear'></div>
      <div id='descrizione_profilo'>
        <h2 id='educazione'>Ho studiato</h2>
        <p>$row->studio</p>
        <h2 id='residenza'>Vivo a</h2>
        <p>$row->citta</p>
        <h2 id='provenienza'>Nato a</h2>
        <p>$row->cittanatale</p>
        <h2 id='hobbies'>Hobbies</h2>
        <p>$row->hobbies</p>

      </div>";
    }


    public static function ulist(){
      $db = Database::getInstance();

      $q = mysql_query('SELECT id, nome_cognome, immagine, eta, citta FROM utenti;')
        or die(mysql_error());

      $content = array();

      while($row = mysql_fetch_object($q)) {
        $content[] = $row;
      }

      return $content;
    }

    public static function friendsList(){
      $db = Database::getInstance();
      $id = $_SESSION['id'];

      $q = mysql_query("SELECT * FROM amicizia
        INNER JOIN utenti ON amicizia.amico1 = $id OR amicizia.amico2 = $id
        WHERE accettata = 1 and utenti.id <> $id GROUP BY utenti.id ;")
      or die(mysql_error());

      $content = array();

      while($row = mysql_fetch_object($q)) {
        $content[] = $row;
      }

      return $content;
    }

    public static function displayList($amico = '0'){

      if ($amico == 'amici')
        $list = Utente::friendsList();
      else
        $list = Utente::ulist();

      $content = '<ul id="amici">';

      foreach($list as $row){

        $content .= "<li>
        <div class='immagine_lista'>
        <img src='./asset/img/$row->immagine' alt='$row->nome_cognome immagine profilo' />
        </div>
        <div class='intestazione_lista'>
        <p><a href='./?a=amici&amp;id=$row->id'>$row->nome_cognome</a></p>
        <p>$row->citta - $row->eta anni</p>
        </div>
        <div class='clear'></div>
        </li>";


      }
      $content .= '</ul>';

      return $content;
    }

    public static function updateUser($id) {
      $db = Database::getInstance();

      $nome_cognome = mysql_real_escape_string(htmlentities($_POST['nome_cognome']));
      $password = sha1($_POST['password']);
      $eta = intval($_POST['eta']);
      $citta = mysql_real_escape_string(htmlentities($_POST['citta']));
      $cittanatale = mysql_real_escape_string(htmlentities($_POST['cittanatale']));
      $hobbies = mysql_real_escape_string(htmlentities($_POST['hobbies']));
      $studio = mysql_real_escape_string(htmlentities($_POST['studio']));
      $email = mysql_real_escape_string(htmlentities($_POST['email']));

      if($_POST['password'] != '') {
        $q = mysql_query("UPDATE `utenti` SET nome_cognome = '$nome_cognome',
        password = '$password', eta = '$eta', citta = '$citta', cittanatale = '$cittanatale',
        hobbies = '$hobbies', studio = '$studio', email = '$email' WHERE id=$id LIMIT 1 ;
        ") or die (mysql_error());
      }
      else {
        $q = mysql_query("UPDATE `utenti` SET nome_cognome = '$nome_cognome',
        eta = '$eta', citta = '$citta', cittanatale = '$cittanatale',
        hobbies = '$hobbies', studio = '$studio', email = '$email' WHERE id=$id LIMIT 1 ;
        ") or die (mysql_error());
      }



      return 'ok';
    }

    public static function registerUser() {
      $db = Database::getInstance();

      $username = mysql_real_escape_string(htmlentities($_POST['username']));
      $password = sha1($_POST['password']);
      $email = mysql_real_escape_string(htmlentities($_POST['email']));

      $q = mysql_query("INSERT INTO `utenti` (`username`, `password`, `email`)
      VALUES ('$username', '$password', '$email');") or die (mysql_error());

      $id = mysql_insert_id();

      mysql_query("INSERT INTO amicizia (amico1, amico2, accettata) VALUES ($id,$id, 1)") or die (mysql_error());

      return 'ok';
    }

    public static function getPanel($id){
      $row = Utente::get($id);
      $content = "
      <div id='content_header'>
        <div id='intestazione'>
          <h2>Pannello di controllo</h2>
        </div>
        <div id='replace_div'>
          <table>
            <input type='hidden' id='id' value='$id' />
            <tr>
              <td>Password</td>
              <td><input type='password' id='password' required /></td>
            </tr>
            <tr>
              <td>Email</td>
              <td><input type='text' id='email' value='$row->email' required /></td>
            </tr>
            <tr>
              <td>Nome e cognome</td>
              <td><input type='text' id='nome_cognome' value='$row->nome_cognome' required /></td>
            </tr>
            <tr>";
            if ($row->immagine != ''){
              $content .= "<td><img src='./asset/img/$row->immagine' width='150px' /></td>";
            }
            $content .= "
              <td><span id='immagine'><input type='file' name='fileToUpload' id='fileToUpload' onchange='uploadFile();'/></span></td>
            </tr>
            <tr>
              <td>Eta</td>
              <td><input type='number' id='eta' value='$row->eta' required /></td>
            </tr>
            <tr>
              <td>Citt&agrave;</td>
              <td><input type='text' id='citta' value='$row->citta' required /></td>
            </tr>
            <tr>
              <td>Citt&agrave; natale</td>
              <td><input type='text' id='cittanatale' value='$row->cittanatale' required /></td>
            </tr>
            <tr>
              <td>Hobbies</td>
              <td><input type='text' id='hobbies' value='$row->hobbies' required /></td>
            </tr>
            <tr>
              <td>Dove hai studiato</td>
              <td><input type='text' id='studio' value='$row->studio' required /></td>
            </tr>
            <tr>
              <td></td><td><a id='cambia_utente' class='button' href='javascript:updateUser();'>Aggiorna profilo</a></td>
            </tr>
          </table>
        </div>
      </div>";

      return $content;
    }

    public static function deleteUser($id){
      $db = Database::getInstance();

      $q = mysql_query("DELETE FROM utenti WHERE id = $id LIMIT 1;") or die (mysql_error());

      return 'ok';
    }

    public static function uploadFoto(){

      $ext_arr = array('.jpg','.jpeg', 'gif','.png');

      if(($_FILES['fileToUpload']['tmp_name'] == NULL) or ($_FILES['fileToUpload']['size'] < 1)){
        die('Nessun file caricato (nessun nome o size < 1)');
      }

      $_FILES['fileToUpload']['name'] = strtolower($_FILES['fileToUpload']['name']);
      //controllo l'estensione
      $pos=strrpos($_FILES['fileToUpload']['name'], '.');
      $ext=substr($_FILES['fileToUpload']['name'] , $pos , strlen($_FILES['fileToUpload']['name'])-$pos);

      if(!in_array($ext, $ext_arr)){
        die('estensione non permessa');
      }
      //cerco dove collocarlo
      $nome = mt_rand().$ext;
      $uri = __DIR__.'/../asset/img/'.$nome;
      while (file_exists('./'.$uri)){
        $nome = mt_rand().$ext;
        $uri = __DIR__.'/../asset/img/'.$nome;
      }

      list($width, $height) = getimagesize($_FILES['fileToUpload']['tmp_name']);

      if($width > 800){

        $newwidth=800;
        $newheight=($height/$width)*800;

        if ($ext == ".jpg" || $ext == ".jpeg" ){
          $src = imagecreatefromjpeg($_FILES['fileToUpload']['tmp_name']);
        } else if ($ext == ".png"){
          $src = imagecreatefrompng($_FILES['fileToUpload']['tmp_name']);
        }


        $tmp=imagecreatetruecolor($newwidth,$newheight);

        imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);

        if ($ext == ".jpg" || $ext == ".jpeg"){
          imagejpeg($tmp,$uri);
        } else if ($ext == ".png"){
          imagepng($tmp,$uri);
        }
      } else {
        move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $uri);
        //chmod('./'.$uri,0644) or die('Cannot change permission');
      }

      return $nome;
    }

    public static function updateFoto($nome, $id) {
      $db = Database::getInstance();
      $q = mysql_query("UPDATE `utenti` SET immagine = '$nome' WHERE id = $id LIMIT 1;") or die(mysql_error());

      return "<img src='./asset/img/$nome' width='150px' />";
    }

    public static function cercaUtente($parametro, $ordinamento){
      $list_ord = array (0 => 'nome_cognome', 1 => 'citta', 2 => 'cittanatale');
      $ordinamento = $list_ord[$ordinamento];

      $db = Database::getInstance();

      $q = mysql_query("SELECT *
      FROM utenti
      WHERE nome_cognome LIKE '%$parametro%'
      OR citta LIKE '%$parametro%'
      OR cittanatale LIKE '%$parametro%'
      ORDER BY $ordinamento;");

      $content = '<table><tr><td>Nome</td><td>Citt&agrave;</td><td>Citt&agrave; natale</td><td></td></tr>';

      while ($row = mysql_fetch_object($q)) {
        $content .= "<tr><td>$row->nome_cognome</td><td>$row->citta</td><td>$row->cittanatale</td><td><a href='javascript:chiediAmicizia($row->id)''>Chiedi amicizia</a></td></tr>";
      }

      $content .= '</table>';

      return $content;
    }

    public static function creaAmicizia($id2) {
      $db = Database::getInstance();

      $id = intval($_SESSION['id']);
      $q = mysql_query("INSERT INTO amicizia (amico1, amico2) VALUES ($id,$id2)") or die (mysql_error());

      return 'ok';
    }

    public static function accettaAmicizia($id2){
      $db = Database::getInstance();

      $id = intval($_SESSION['id']);
      $q = mysql_query("UPDATE amicizia SET accettata=1 WHERE amico1=$id2 AND amico2=$id LIMIT 1") or die(mysql_error());

      return 'ok';
    }

    public static function getRichiestaAmicizia($id){
      $db = Database::getInstance();

      $q = mysql_query("SELECT amico1, utenti.nome_cognome FROM amicizia INNER JOIN utenti WHERE amicizia.amico1 = utenti.id AND amico2=$id AND accettata = 0");

      $content = '<table><tr><td>Nome</td><td>Accetta</td></tr>';

      while ($row = mysql_fetch_object($q)) {
        $content .= "<tr><td><a href='?a=amici&amp;id=$row->amico1' target='_blank'>$row->nome_cognome</a></td><td><a class='button' href='javascript:accettaAmicizia($row->amico1)' > Accetta amicizia </a></td></tr>";
      }

      $content .= '</table>';

      return $content;
    }

  }
