<?php
    namespace App;

    class Model {
        protected $dbHost = 'localhost';
        protected $dbName = 'php_mini_rest_api';
        protected $dbUser = 'root';
        protected $dbPass = '';
        protected $dbConn;
        protected $stmt;

        protected function _construct()
        {
            // Create a DSN...
            $Dsn = "mysql:host=" . $this->dbHost . ";dbname=" . $this->dbName;
            $options = array(
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            );

            try {
                $this->dbConn = new PDO($Dsn, $dbUser, $dbPass, $options);
            } catch(Exception $e) {
                $Response = array(
                    status => 500,
                    data => [],
                    message => $e->getMessage()
                );
                return $Response;
            }
        }

        protected function query($query)
        {
            $this->stmt = $this->dbConn->prepare($query);
            return true;
        }

        protected function bindParams($param, $value, $type = null)
        {
            if ($type == null) {
                switch(true) {
                    case is_int:
                        $type = PDO::PARAM_INT;
                    break;
                    case is_bool:
                        $type = PDO::PARAM_BOOL;
                    break;
                    case is_null:
                        $type = PDO::PARAM_NULL;
                    break;
                    default:
                        $type = PDO::PARAM_STR;
                    break;
                }
            }

            $this->stmt->bindValue($param, $value, $type);
            return;
        }

        protected function execute()
        {
            $this->stmt->execute();
            return true;
        }

        protected function fetch()
        {
            $this->execute();
            return $this->stmt->fetch(PDO::FETCH_ASSOC);
        }

        protected function fetchAll()
        {
            $this->execute();
            return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>