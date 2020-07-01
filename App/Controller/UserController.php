<?php
    namespace App;

    use Exception;
    use App\UserModel;
    use App\TokenModel;
    use App\Controller;
    use Firebase\JWT\JWT;
    use App\RequestMiddleware;    

    class UserController extends Controller {

        public static function createNewUser($request, $response, $service)
        {
            $Response = [];
            $sapi_type = php_sapi_name();     
            $Middleware = new RequestMiddleware();
            $Middleware = $Middleware::acceptsJson();   

            if (!$Middleware) {
                array_push($Response, [
                    'status' => 400,
                    'message' => 'Sorry, Only JSON Contents are allowed to access this Endpoint.',
                    'data' => []
                ]);

                $response->code(400)->json($Response);
                return;
            }

            $data = json_decode($request->body());                   
            // Do some validation...
            $validationObject = array(
                (Object) [
                    'validator' => 'required',
                    'data' => isset($data->firstName) ? $data->firstName : '',
                    'key' => 'First Name'
                ],
                (Object) [
                    'validator' => 'string',
                    'data' => isset($data->firstName) ? $data->firstName : '',
                    'key' => 'First Name'
                ],
                (Object) [
                    'validator' => 'required',
                    'data' => isset($data->lastName) ? $data->lastName : '',
                    'key' => 'Last Name',
                ],
                (Object) [
                    'validator' => 'string',
                    'data' => isset($data->lastName) ? $data->lastName : '',
                    'key' => 'Last Name',
                ],
                (Object) [
                    'validator' => 'emailExists',
                    'data' => isset($data->email) ? $data->email : '',
                    'key' => 'Email'
                ],
                (Object) [
                    'validator' => 'min:7',
                    'data' => isset($data->password) ? $data->password : '',
                    'key' => 'Password'
                ]
            );

            $validationBag = Parent::validation($validationObject);                    
            if ($validationBag->status) {              
                $response->code(400)->json($validationBag);  
                return;
            }

            // Trim the response and create the account....
            $payload = array(
                'firstName' => htmlspecialchars(stripcslashes(strip_tags($data->firstName))),
                'lastName' => htmlspecialchars(stripcslashes(strip_tags($data->lastName))),
                'email' => stripcslashes(strip_tags($data->email)),
                'password' => password_hash($data->password, PASSWORD_BCRYPT),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );

            try {
                $UserModel = new UserModel();
                $UserData = $UserModel::createUser($payload);
                if ($UserData['status']) {
                    
                    // Initialize JWT Token....
                    $tokenExp = date('Y-m-d H:i:s');  
                    $tokenSecret = Parent::JWTSecret();
                    $tokenPayload = array(
                        'iat' => time(),
                        'iss' => 'PHP_MINI_REST_API', //!!Modify:: Modify this to come from a constant
                        "exp" => strtotime($tokenExp + ' 7 Days'),
                        "user_id" => $UserData['data']['user_id']
                    );
                    $Jwt = JWT::encode($tokenPayload, $tokenSecret);

                    // Save JWT Token...
                    $TokenModel = new TokenModel();
                    $TokenModel::createToken([
                        'user_id' => $UserData['data']['user_id'],
                        'jwt_token'=> $Jwt
                    ]);
                    $UserData['data']['token'] = $Jwt;
                    
                    // Return Response............
                    $Response['status'] = 201;
                    $Response['message'] = '';
                    $Response['data'] = $UserData;

                    $response->code(201)->json($Response);
                    return;
                }

                $Response['status'] = 500;
                $Response['message'] = 'An unexpected error occurred and your account could not be created. Please, try again later.';
                $Response['data'] = [];

                $response->code(500)->json($Response);
                return;
            } catch (Exception $e) {
                $Response['status'] = 500;
                $Response['message'] = $e->getMessage();
                $Response['data'] = [];
                
                $response->code(500)->json($Response);
                return;
            }
        }
    }
?>
