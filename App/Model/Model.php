<?php
    namespace App;

    use PDO;
    use Exception;

    /**
     * Model - The Base Model for all other Models.... All Other Model extends this Model.
     *
     * @author      Ilori Stephen A <stephenilori458@gmail.com>
     * @link        https://github.com/learningdollars/php-rest-api/App/Model/Model.php
     * @license     MIT
     */
    class Model {
        protected static $dbHost = '127.0.0.1';
        protected static $dbName = 'php_mini_rest_api';
        protected static $dbUser = 'root';
        protected static $dbPass = '';
        protected static $dbConn;
        protected static $stmt;

        /**
         * __construct
         *
         * Creates a New Database Connection...
         *
         * @param void
         * @return void
         */
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

        /**
         * query
         *
         * Takes advantage of PDO prepare method to create a prepared statement.
         *
         * @param string $query  Sql query from extending Models
         * @return void Anonymos
         */
        protected static function query($query)
        {
            Self::$stmt = Self::$dbConn->prepare($query);
            return true;
        }

        /**
         * bindParams
         *
         * Binds the prepared statement using the bindValue method.
         *
         * @param mixed $param, $value, $type  The parameter to bind the value to and the data type which is by default null.
         * @return void Anonymos
         */
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

         /**
         * execute
         *
         * Executes the Sql statement and returns a boolean status
         *
         * @param void
         * @return boolean Anonymos
         */
        protected static function execute()
        {
            Self::$stmt->execute();
            return true;
        }

         /**
         * fetch
         *
         * Executes the Sql statement and returns a single array from the resulting Sql query.
         *
         * @param void
         * @return array Anonymos
         */
        protected static function fetch()
        {
            Self::execute();
            return Self::$stmt->fetch(PDO::FETCH_ASSOC);
        }

         /**
         * fetchAll
         *
         * Executes the Sql statement and returns an array from the resulting Sql query.
         *
         * @param void
         * @return array Anonymos
         */
        protected static function fetchAll()
        {
            Self::execute();
            return Self::$stmt->fetchAll(PDO::FETCH_ASSOC);
        }

         /**
         * lastInsertedId
         *
         * Makes use of the database connection and returns the last inserted id in the database.
         *
         * @param void
         * @return int Anonymos
         */
        protected static function lastInsertedId()
        {
            return Self::$dbConn->lastInsertId();
        }
    }
?>
