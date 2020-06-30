<?php
    namespace App;
    use App\UserModel;

    class UserController {
        public function __construct()
        {
            //We can define our middlewares here....
        }

        public static function createNewUser($request, $response, $service)
        {
            $data = json_decode($request->body());
            $sapi_type = php_sapi_name();
            $Response = [];

            if ($data->firstName == '' || $data->firstName == null) {
                array_push($Response, [
                    'status' => 400,                    
                    'message' => 'Sorry, The First Name field is required.',
                    'data' => []
                ]);                        
            }

            if (preg_match('/[^A-Za-z]/', $data->firstName)) {
                array_push($Response, [
                    'status' => 400,                    
                    'message' => 'Sorry, Only Alphabets are allowed.',
                    'data' => []
                ]);     
            }

            

            if (substr($sapi_type, 0, 3) == 'cgi') {
                header("Status: 400 Not Found");
                $response->json($Response);
                return;
            } else {
                header("HTTP/1.1 404 Not Found");
                $response->json($Response);
                return;
            }
        }

        public static function index($request, $response, $service)
        {
            echo "<pre>";
            print_r($request);
            return 'Hello World!';
        }
    }
?>