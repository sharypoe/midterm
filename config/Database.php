<?php
class Database
{
  // My previous local connection:
  // private $conn;
  // private  $host = 'localhost';
  // private $port = "5432";
  // private $dbname = 'quotesdb';
  // private $user = 'postgres';
  // private $password = 'postgres';

  // public function connect()
  // {
  //   $this->conn = null;
  //   try {
  //     $this->conn = new PDO('pgsql:host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->dbname, $this->user, $this->password); //$password
  //     $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //     return $this->conn;
  //   } catch (PDOException $e) {
  //     $error_message = 'Database Error: ' . $e->getMessage();
  //     echo $error_message;
  //     exit('<br>Unable to connect to the database.');
  //   }
  // }

  private $conn;
  private $host;
  private $port;
  private $dbname;
  private $user;
  private $password;

  public function connect()
  {
    $this->conn = null;
    try {
      // using environment variables
      $this->host = getenv('RENDER_POSTGRES_HOST');
      $this->port = getenv('RENDER_POSTGRES_PORT');
      $this->dbname = getenv('RENDER_POSTGRES_DB');
      $this->user = getenv('RENDER_POSTGRES_USER');
      $this->password = getenv('RENDER_POSTGRES_PASSWORD');

      $this->conn = new PDO('pgsql:host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->dbname, $this->user, $this->password);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $this->conn;
    } catch (PDOException $e) {
      $error_message = 'Database Error: ' . $e->getMessage();
      echo $error_message;
      exit('<br>Unable to connect to the database.');
    }
  }
}
