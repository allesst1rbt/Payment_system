<?php 
    class Conection {
        private $host = $_ENV['host'];
        private $dbname = 'crud_simples';
        private $user = 'root';
        private $pass = '';
        public function conectar()
        {
            try {
                $Conection = new PDO(
                    "mysql:host=$this->host;dbname=$this->dbname",
                    "$this->user",
                    "$this->pass"
                );
                return $Conection;
            } catch (PDOException $e) {
                echo '<p>'.$e->getMessage().'</p>';
            }
        }
    }