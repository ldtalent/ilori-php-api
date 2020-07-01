<?php
    namespace App;
    use App\Model;

    class UserModel extends Model {

        public static function createUser($payload)
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

        public static function fetchUserId($Id)
        {
            $Sql = "SELECT id, firstName, lastName, email, created_at, updated_at FROM `db_user` WHERE id = :id";
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

        public static function checkEmail($email)
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