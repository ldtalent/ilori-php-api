<?php
    namespace App;
    use Firebase\JWT\JWT;
    use Exception;
    
    use App\TokenModel;
    use App\UserModel;

    class JwtMiddleware {
        protected static $user;
        protected static $token;
        protected static $user_id;

        private static function JWTSecret()
        {
            return 'K-lyniEXe8Gm-WOA7IhUd5xMrqCBSPzZFpv02Q6sJcVtaYD41wfHRL3';
        }

        protected static function getToken()
        {
            Self::$token = $_SERVER['HTTP_AUTHORIZATION'];
            return $_SERVER['HTTP_AUTHORIZATION'];
        }

        protected static function validateToken()
        {
            Self::getToken();
            if (Self::$token == '' || Self::$token == null) {
                return false;
            }

            try {
                $Token = explode('Bearer ', Self::$token);

                if (isset($Token[1]) && $Token == '') {
                    return false;
                }
                
                return $Token[1];
            } catch (Exception $e) { return false; }
            
        }

        public function getAndDecodeToken()
        {
            $token = Self::validateToken();
            try {
                if ($token !== false) {
                    // Query the database for the token and decode it....
                    $TokenModel = new TokenModel();
                    // Check the database for the query before decoding it...
                    $tokenDB = $TokenModel::fetchToken($token);
 
                    if ($tokenDB['status']) {
                        // decode the token and pass the result on to the controller....
                        $decodedToken = (Array) JWT::decode($token, Self::JWTSecret(), array('HS256'));
                        if (isset($decodedToken['user_id'])) {
                            Self::$user_id = $decodedToken['user_id'];
                            return $decodedToken['user_id'];
                        }

                        return false;
                    }
                    return false;
                }

                return false;
            } catch (Exception $e) {
                return false;
            }
        }

        public function getUser()
        {
            try {
                $UserModel = new UserModel();
                $user = $UserModel::fetchUserById(Self::$user_id);
                if ($user['status']) {
                    return $user['data'];
                }

                return [];
            } catch (Exception $e) { return []; }
        }
    }
?>