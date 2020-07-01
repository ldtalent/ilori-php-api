<?php
    namespace App;
    use App\Model;

    class TokenModel extends Model {
        public function createToken($payload)
        {
            $Sql = "INSERT INTO db_token (user_id, jwt_token, jwt_secret) VALUES (:user_id, :jwt_token, :jwt_secret)";
            Parent::query($Sql);
            // Bind Params...
            Parent::bindParams('user_id', $payload['user_id']);
            Parent::bindParams('jwt_token', $payload['jwt_token']);
            Parent::bindParams('jwt_secret', $payload['jwt_secret']);

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
            $Sql = "SELECT db_token.user_id, db_token.jwt_token, db_token.jwt_secret, db_user.firstName, db_user.lastName, db_user.email INNER JOIN db_token.jwt_token = :jwt_token";
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