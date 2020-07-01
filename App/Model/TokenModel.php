<?php
    namespace App;
    use App\Model;

    class TokenModel extends Model {
        public function createToken($payload)
        {
            $Sql = "INSERT INTO db_token (user_id, jwt_token) VALUES (:user_id, :jwt_token)";
            Parent::query($Sql);
            // Bind Params...
            Parent::bindParams('user_id', $payload['user_id']);
            Parent::bindParams('jwt_token', $payload['jwt_token']);

            $Token = Parent::execute();
            if ($Token) {
                return array(
                    'status' => true,
                    'data' => $payload
                );
            }

            return array(
                'status' => false,
                'data' => []
            );
        }

        public function fetchToken($token)
        {
            $Sql = "SELECT * FROM db_token WHERE jwt_token = :jwt_token";
            Parent::query($Sql);
            Parent::bindParams('jwt_token', $payload['jwt_token']);

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
    }
?>