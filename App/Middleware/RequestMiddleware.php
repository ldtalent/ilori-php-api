<?php
    namespace App;
    class RequestMiddleware {
        protected static $Request;
        public function __construct($paths = [])
        {
          Self::$Request = $_SERVER['CONTENT_TYPE'];
          return;
        }

        public static function acceptsJson()
        {
          if (strtolower(Self::$Request) == 'application/json') {
            return true;
          }

          return false;
        }

        public static function acceptsFormData()
        {
          if (strtolower(Self::$Request) == 'multipart/form-data') {
            return true;
          }

          return false;
        }
    }
?>
