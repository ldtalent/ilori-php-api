<?php
    namespace App;
    use App\UserModel;
    use App\CatalogModel;
    use App\ProductModel;

    /**
     * Controller - The Base Controller for all other Controllers.... All Other Controllers extends this Controller.
     *
     * @author      Ilori Stephen A <stephenilori458@gmail.com>
     * @link        https://github.com/learningdollars/php-rest-api/App/Controller/Controller.php
     * @license     MIT
     */
    class Controller {

        /**
         * validation
         *
         * Validates an array of objects using defined rules...
         *
         * @param array $Payload  Contains an array of Objects that will be validated.
         * @return array $response
         */
        protected static function validation($payloads)
        {
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
                    } catch (Exception $e) { /** */ }
                }

                if ($payload->validator == 'catalogExists') {
                    try {
                        $CatalogModel = new CatalogModel();
                        $checkCatalog = $CatalogModel::fetchCatalogByID($payload->data);

                        if (!$checkCatalog['status']) {
                            array_push($response, [
                                'key' => $payload->key,
                                'message' => "Sorry, The catalog with this ID could not be found in our database."
                            ]);
                        }
                    } catch (Exception $e) { /** */ }
                }

                if ($payload->validator == 'productExists') {
                    try {
                        $ProductModel = new ProductModel();
                        $checkProduct = $ProductModel::findProductById((int) $payload->data);

                        if (!$checkProduct['status']) {
                            array_push($response, [
                                'key' => $payload->key,
                                'message' => "Sorry, The product with this ID could not be found in our database."
                            ]);
                        }
                    } catch (Exception $e) { /** */ }
                }

                if ($payload->validator == 'img') {
                    try {
                        $files = $payload->data;
                        if ($files) {
                            $fileName = $files['name'];

                            $targetDir = '../../public/img/';
                            $targetFile = $targetDir . basename($files['name']);

                            $fileSize = $files['size'];
                            $fileExtension = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                            if  (!in_array($fileExtension, $payload->acceptedExtension)) {
                                array_push($response, [
                                    'key' => $payload->key,
                                    'message' => "Sorry, {$payload->key} only accepts the following extensions; " . implode(", ", $payload->acceptedExtension)
                                ]);
                            }

                            if ($fileSize > $payload->maxSize) {
                                array_push($response, [
                                    'key' => $payload->key,
                                    'message' => "Sorry, {$payload->key} File size should be less than " . $payload->maxSize
                                ]);
                            }
                        }
                    } catch (Exception $e) { /** */ }
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

        /**
         * JWTSecret
         *
         * Returns a JWT Secret....
         *
         * @param void
         * @return string Annonymous
         */
        protected static function JWTSecret()
        {
            return 'K-lyniEXe8Gm-WOA7IhUd5xMrqCBSPzZFpv02Q6sJcVtaYD41wfHRL3';
        }
    }
?>
