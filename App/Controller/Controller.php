<?php 
    namespace App;

    class Controller {
        protected function validation($payloads, $initialData)
        {   
            $i = -1;
            $response = [];
            foreach($payloads as $data => $validator) {
                $i++;
                if ($validator == 'required') {
                    if ($data == null || $data = '' || !isset($data)) {
                        array_push($response, [
                            index => $i,
                            message => 'The %%PLACE_HOLDER%% field is required'
                        ]);
                    }
                }

                if ($validator == 'string') {
                    if (preg_match('/[^A-Za-z]/', $data)) {
                        array_push($response, [
                            index => $i,
                            message => 'Sorry %%PLACE_HOLDER%% expects an Alphabet.'
                        ]);
                    }
                }

                if ($validator == 'numeric') {
                    if (preg_match('/[^0-9_]/', $data)) {
                        array_push($response, [
                            index => $i,
                            message => 'Sorry %%PLACE_HOLDER%% expects a Number.'
                        ]);
                    }
                }

                if ($validator == 'boolean') {
                    if (strtolower(gettype($data)) !== 'boolean') {
                        array_push($response, [
                            index => $i,
                            message => 'Sorry %%PLACE_HOLDER%% expects a Boolean.'
                        ]);
                    }
                }

                if (stristr($validator, ':')) {
                    $operationName = explode(':', $validator)[0];
                    $operationChecks = (int) explode(':', $validator)[1];

                    if (strtolower($operationName) == 'min' && $operationChecks > strlen($data)) {
                        array_push($response, [
                            index => $i,
                            message => 'Sorry %%PLACE_HOLDER%% is supposed to be less than ' . strlen($data)
                        ]);
                    }


                    if (strtolower($operationName) == 'max' && $operationChecks < strlen($data)) {
                        array_push($response, [
                            index => $i,
                            message => 'Sorry %%PLACE_HOLDER%% is supposed to be greather than ' . strlen($data)
                        ]);
                    }


                    if (strtolower($operationName) == 'between') {
                        $operationChecksTwo = (int) explode(':', $validator)[2];
                        array_push($response, [
                            index => $i,
                            message => 'Sorry %%PLACE_HOLDER%% is supposed to be between ' . $operationChecks . ' and ' . $operationChecksTwo
                        ]);
                    }

                }

            }

            if (count($response) < 0) {
                $validationErrors = new \stdClass();
                $validationErrors->status = false;
                $validationErrors->data = [];

                return $validationErrors;
            }
        }
    }
?>