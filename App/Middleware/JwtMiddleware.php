<?php
    namespace App;
    use Firebase\JWT\JWT;
    use Exception;

    use App\TokenModel;
    use App\UserModel;

    /**
     * JwtMiddleware - The JwtMiddleware. This Controller makes use of a few Models, Classes and packages for authenticating requests....
     *
     * @author      Ilori Stephen A <stephenilori458@gmail.com>
     * @link        https://github.com/learningdollars/php-rest-api/App/Middleware/JWTMiddleware.php
     * @license     MIT
     */
    class JwtMiddleware {
        protected static $user;
        protected static $token;
        protected static $user_id;

        /**
         * JWTSecret
         *
         * Returns a JWT Secret...
         *
         * @param   void
         * @return  string
        */
        private static function JWTSecret()
        {
            return 'K-lyniEXe8Gm-WOA7IhUd5xMrqCBSPzZFpv02Q6sJcVtaYD41wfHRL3';
        }

         /**
         * getToken
         *
         * Fetches and return the JWT Token from the request Header
         *
         * @param   void
         * @return  string
        */
        protected static function getToken()
        {
            Self::$token = $_SERVER['HTTP_AUTHORIZATION'];
            return $_SERVER['HTTP_AUTHORIZATION'];
        }

        /**
         * validateToken
         *
         * Validates the JWT Token and returns a boolean true...
         *
         * @param   void
         * @return  boolean
        */
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

        /**
         * getAndDecodeToken
         *
         * Decodes and returns a boolean true or the user_id.
         *
         * @param   void
         * @return  mixed
        */
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

         /**
         * getUser
         *
         * Fetches the user ID from the JWT Token and returns a User Array.
         *
         * @param   void
         * @return  array
        */
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
