<?php
namespace Kruul;
use \PDO;
use \Exception;
/*
Pdodb - simple PDO wrapper

Author: AShvager
Mailto: alex.shvager@gmail.com
Start: 17.10.2016
2017-01-17 + setAttribute
2017-01-17 * early initialization setAttribute
2017-01-20 * fix with parameters
2017-02-15 + PDO::ATTR_DEFAULT_FETCH_MODE

*/
error_reporting(E_ALL);

class Pdodb
{
    private $config;
    private $pdo;
    private $sql;
    private $sth;
    private $attribute;

    public function __construct($config){
        $this->config=array('driver'=>'','host'=>'','database'=>'','charset'=>'','username'=>'','password'=>'');
        $this->config = array_merge($this->config,$config);
        $this->attribute=array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
                               PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ,
                               PDO::ATTR_CASE=>PDO::CASE_LOWER
                              );
    }

    public function query($sql, $params = null){//echo $sql;
        $this->connect();
        if ($this->sql!=$sql) {
            $this->sql=$sql;
            $this->sth = $this->pdo->prepare($this->sql);
        }
        $params = is_array($params) ? $params : (is_null($params) ? null : [$params]);
        if ($params && $this->sth->execute($params)) {
            return $this->sth;
        } elseif ($this->sth->execute()) {
            return $this->sth;
        }
        return false;
    }

    public function setAttribute($attribute,$option){
      $this->attribute[$attribute]=$option;
      if ($this->pdo) $this->pdo->setAttribute($attribute, $option);
      return $this;
    }

    private function connect(){
      if (!$this->pdo) {
        try {
          $dsn = $this->config['driver'].':host=' . $this->config['host'] . ';dbname=' . $this->config['database'] . ';charset=' . $this->config['charset'];
          $this->pdo = new PDO($dsn, $this->config['username'], $this->config['password']);
          foreach ($this->attribute as $attribute => $value) {
            $this->pdo->setAttribute($attribute,$value);
          }
        } catch (Exception $e) {
              throw new Exception($e->getMessage(), 1);
        }
      }
    }
}