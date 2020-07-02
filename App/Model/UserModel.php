<?php
    namespace App;
    use App\Model;

    /**
     * UserModel - This Model is consumed basically by the UserController and is also consumed by other controllers and Middlewares...
     *
     * @author      Ilori Stephen A <stephenilori458@gmail.com>
     * @link        https://github.com/learningdollars/php-rest-api/App/Model/UserModel.php
     * @license     MIT
     */
    class UserModel extends Model {

         /**
         * createUser
         *
         * creates a new User
         *
         * @param array $payload  Contains all the fields that will be created.
         * @return array Anonymos
         */
        public static function createUser(array $payload) :array
        {
            $Sql = "INSERT INTO `db_users` (firstName, lastName, email, password, created_at, updated_at) VALUES (:firstName, :lastName, :email, :password, :created_at, :updated_at)";
            Parent::query($Sql);
            // Bind Params...
            Parent::bindParams('firstName', $payload['firstName']);
            Parent::bindParams('lastName', $payload['lastName']);
            Parent::bindParams('email', $payload['email']);
            Parent::bindParams('password', $payload['password']);
            Parent::bindParams('created_at', $payload['created_at']);
            Parent::bindParams('updated_at', $payload['updated_at']);

            $newUser = Parent::execute();
            if ($newUser) {
                $user_id = Parent::lastInsertedId();
                $payload['user_id'] = $user_id;
                $Response = array(
                    'status' => true,
                    'data' => $payload
                );
                return $Response;
            }

            $Response = array(
                'status' => false,
                'data' => []
            );
            return $Response;
        }

         /**
         * fetchUserById
         *
         * fetches a user by it's Id
         *
         * @param int $Id  The Id of the row to be fetched...
         * @return array Anonymos
         */
        public static function fetchUserById(int $Id) :Array
        {
            $Sql = "SELECT id, firstName, lastName, email, created_at, updated_at FROM `db_users` WHERE id = :id";
            Parent::query($Sql);
            // Bind Params...
            Parent::bindParams('id', $Id);
            $Data = Parent::fetch();

            if (!empty($Data)) {
                return array(
                    'status' => true,
                    'data' => $Data
                );
            }

            return array(
                'status' => false,
                'data' => []
            );
        }

        /**
         * checkEmail
         *
         * fetches a user by it's email
         *
         * @param string $email  The email of the row to be fetched...
         * @return array Anonymos
         */
        public static function checkEmail(string $email) :Array
        {
            $Sql = "SELECT * FROM `db_users` WHERE email = :email";
            Parent::query($Sql);
            // Bind Params...
            Parent::bindParams('email', $email);
            $emailData = Parent::fetch();
            if (empty($emailData)) {
                $Response = array(
                    'status' => false,
                    'data' => []
                );
                return $Response;
            }

            $Response = array(
                'status' => true,
                'data' => $emailData
            );
            return $Response;
        }
    }
?>