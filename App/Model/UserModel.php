<?php
    namespace App;
    use App\Model;

    class UserModel extends Model {
        public static function createUser($payload)
        {   
            $Sql = "INSERT INTO `db_users` (firstName, lastName, email, password, token, secret, created_at, updated_at) VALUES (:firstName, :lastName, :email, :password, :token, :secret, :created_at, :updated_at)";
            Parent::query($Sql);
            // Bind Params...
            Parent::bindParams('firstName', $payload['firstName']);
            Parent::bindParams('lastName', $payload['lastName']);
            Parent::bindParams('email', $payload['email']);
            Parent::bindParams('password', $payload['password']);
            Parent::bindParams('token', $payload['token']);
            Parent::bindParams('secret', $payload['secret']);
            Parent::bindParams('created_at', $payload['created_at']);
            Parent::bindParams('updated_at', $payload['updated_at']);

            $newUser = Parent::execute();
            if ($newUser) {
                return true;
            }

            return false;
        }
    }
?>