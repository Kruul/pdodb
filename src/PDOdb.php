<?php
class PDOdb
{
    private $config;
    private $pdo;
    private $sql;
    private $sth;

    public function __construct(Config $config){
        $this->config = $config;
    }

    public function query($sql, $params = null){
        $this->connect();
        if ($this->sql!=$sql) $this->sql=$sql;

        $sth = $this->pdo->prepare($this->sql);
        $params = is_array($params) ? $params : is_null($params) ? null : [$params];

        if ($params && $sth->execute($params)) {
            return $sth;
        } elseif ($sth->execute()) {
            return $sth;
        }

        return false;
    }

    private function connect(){
        if (!$this->pdo) {
            $driver=$this->config['driver'];
            $host=$this->config['host'];
            $database=$this->config['database'];
            $charset=$this->config['charset'];
            $username=$this->config['username'];
            $password=$this->config['password'];

            try {
                $dsn = $driver.':host=' . $host . ';dbname=' . $database . ';charset=' . $charset;
                $this->pdo = new PDO($dsn, $username, $password);

                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }
    }
}