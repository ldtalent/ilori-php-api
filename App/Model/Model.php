<?php
    namespace App;

    use PDO;
    use Exception;

    class Model {
        protected static $dbHost = '127.0.0.1';
        protected static $dbName = 'php_mini_rest_api';
        protected static $dbUser = 'root';
        protected static $dbPass = '';
        protected static $dbConn;
        protected static $stmt;

        public function __construct()
        {
            // Create a DSN...
            $Dsn = "mysql:host=" . Self::$dbHost . ";dbname=" . Self::$dbName;
            $options = array(
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            );

            try {
                Self::$dbConn = new PDO($Dsn, Self::$dbUser, Self::$dbPass, $options);
            } catch(Exception $e) {
                $Response = array(
                    status => 500,
                    data => [],
                    message => $e->getMessage()
                );
                return $Response;
            }
        }

        protected static function query($query)
        {
            Self::$stmt = Self::$dbConn->prepare($query);
            return true;
        }

        protected static function bindParams($param, $value, $type = null)
        {
            if ($type == null) {
                switch(true) {
                    case is_int($value):
                        $type = PDO::PARAM_INT;
                    break;
                    case is_bool($value):
                        $type = PDO::PARAM_BOOL;
                    break;
                    case is_null($value):
                        $type = PDO::PARAM_NULL;
                    break;
                    default:
                        $type = PDO::PARAM_STR;
                    break;
                }
            }

            Self::$stmt->bindValue($param, $value, $type);
            return;
        }

        protected static function execute()
        {
            Self::$stmt->execute();
            return true;
        }

        protected static function fetch()
        {
            Self::execute();
            return Self::$stmt->fetch(PDO::FETCH_ASSOC);
        }

        protected static function fetchAll()
        {
            Self::execute();
            return Self::$stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        protected static function lastInsertedId()
        {
            return Self::$dbConn->lastInsertId();
        }
    }
?>