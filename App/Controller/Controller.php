<?php 
    namespace App;
    use App\UserModel;
    class Controller {
        protected static function validation($payloads)
        {   
            $i = -1;
            $response = [];
            foreach($payloads as $payload) {
                $i++;
                if ($payload->validator == 'required') {
                    if ($payload->data == null || $payload->data = '' || !isset($payload->data)) {
                        array_push($response, [
                            'key' => $payload->key,
                            'message' => "The {$payload->key} field is required"
                        ]);
                    }
                }

                if ($payload->validator == 'string') {
                    if (preg_match('/[^A-Za-z]/', $payload->data)) {
                        array_push($response, [
                            'key' => $payload->key,
                            'message' => "Sorry {$payload->key} expects an Alphabet."
                        ]);
                    }
                }

                if ($payload->validator == 'numeric') {
                    if (preg_match('/[^0-9_]/', $payload->data)) {
                        array_push($response, [
                            'key' => $payload->key,
                            'message' => "Sorry {$payload->key} expects a Number."
                        ]);
                    }
                }

                if ($payload->validator == 'boolean') {
                    if (strtolower(gettype($payload->data)) !== 'boolean') {
                        array_push($response, [
                            'key' => $payload->key,
                            'message' => "Sorry {$payload->key} expects a Boolean."
                        ]);
                    }
                }

                if (stristr($payload->validator, ':')) {
                    $operationName = explode(':', $payload->validator)[0];
                    $operationChecks = (int) explode(':', $payload->validator)[1];

                    if (strtolower($operationName) == 'min' && $operationChecks > strlen($payload->data)) {
                        array_push($response, [
                            'key' => $payload->key,
                            'message' => "Sorry {$payload->key} is supposed to be less than " . strlen($payload->data)
                        ]);
                    }


                    if (strtolower($operationName) == 'max' && $operationChecks < strlen($payload->data)) {
                        array_push($response, [
                            'key' => $payload->key,
                            'message' => "Sorry {$payload->key} is supposed to be greather than " . strlen($payload->data)
                        ]);
                    }


                    if (strtolower($operationName) == 'between') {
                        $operationChecksTwo = (int) explode(':', $payload->validator)[2];
                        array_push($response, [
                            'key' => $payload->key,
                            'message' => "Sorry {$payload->key} is supposed to be between " . $operationChecks . ' and ' . $operationChecksTwo
                        ]);
                    }

                }

                if ($payload->validator == 'emailExists') {
                    try {
                        $UserModel = new UserModel();
                        $checkEmail = $UserModel::checkEmail($payload->data);

                        if ($checkEmail['status']) {
                            array_push($response, [
                                'key' => $payload->key,
                                'message' => "Sorry {$payload->key} already exists. Please try with a different Email."
                            ]);
                        }
                    } catch (Exception $e) {  }
                }

            }

            if (count($response) < 1) {
                $validationErrors = new \stdClass();
                $validationErrors->status = false;
                $validationErrors->errors = [];

                return $validationErrors;
            }

            $validationErrors = new \stdClass();
            $validationErrors->status = true;
            $validationErrors->errors = $response;
            return $validationErrors;
        }
    }
?>