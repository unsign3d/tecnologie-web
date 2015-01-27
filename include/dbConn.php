<?php
class Database{
  private $host = 'localhost';
  private $user = 'root';
  private $password = '';
  private $database = 'tweb';
  public $conn = null;

  private static $singleton = null ;

  public function __construct(){
    // mi collego al database
    $this->conn = mysql_connect($this->host, $this->user, $this->password)
      or die ('connessione non riuscita: ' . mysql_error() );

    // cambio il database
    mysql_set_charset("UTF8", $this->conn);
    // seleziono in database corrente
    mysql_select_db($this->database, $this->conn);
  }

  public static function getInstance(){
    if(Database::$singleton === null){
      Database::$singleton = new Database();
    }
    return Database::$singleton;
  }

  public function __destruct(){
    Database::$singleton = null;
    mysql_close();
  }
}
