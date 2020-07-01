<?php
    namespace App;
    use Exception;

    use App\Controller;
    use App\CatalogModel;
    use App\JwtMiddleware;
    use App\RequestMiddleware;

    class  CatalogController extends Controller {
        
        public function createNewCategory($request, $response)
        {   
            $Response = [];
            // Call the JSON Middleware
            $JsonMiddleware = new RequestMiddleware();
            $acceptsJson = $JsonMiddleware::acceptsJson();

            if (!$acceptsJson) {
                array_push($Response, [
                    'status' => 400,
                    'message' => 'Sorry, Only JSON Contents are allowed to access this Endpoint.',
                    'data' => []
                ]);

                $response->code(400)->json($Response);
                return;
            }

            $JwtMiddleware = new JwtMiddleware();
            $jwtMiddleware = $JwtMiddleware::getAndDecodeToken();
            if (isset($jwtMiddleware) && $jwtMiddleware == false) {
                return array(
                    'status' => 401,
                    'message' => 'Sorry, the authenticity of this token could not be verified.',
                    'data' => []
                );
            }

            $Data = json_decode($request->body(), true);
            $validationObject = array(
                (Object) [
                    'validator' => 'required',
                    'data' => isset($Data['name']) ? $Data['name'] : '',
                    'key' => 'Catalog Name'
                ]
            );
            
            $validationBag = Parent::validation($validationObject);                    
            if ($validationBag->status) {              
                $response->code(400)->json($validationBag);  
                return;
            }

            try {
                $CatalogModel = new CatalogModel();
                $Payload = [
                    'name' => $Data['name'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $catalog = $CatalogModel::createCatalog($Payload);
                if ($catalog['status']) {
                    
                    $Response['status'] = 201;
                    $Response['data'] = $catalog['data'];
                    $Response['message'] = '';

                    $response->code(201)->json($Response);
                    return;
                }
            } catch (Exception $e) {
                $Response['status'] = 500;
                $Response['message'] = $e->getMessage();
                $Response['data'] = [];
                
                $response->code(500)->json($Response);
                return;
            }
            return;
        }

        public function updateCatalog($request, $response)
        {
            $Response = [];
            // Call the JSON Middleware
            $JsonMiddleware = new RequestMiddleware();
            $acceptsJson = $JsonMiddleware::acceptsJson();

            if (!$acceptsJson) {
                array_push($Response, [
                    'status' => 400,
                    'message' => 'Sorry, Only JSON Contents are allowed to access this Endpoint.',
                    'data' => []
                ]);

                $response->code(400)->json($Response);
                return;
            }

            $JwtMiddleware = new JwtMiddleware();
            $jwtMiddleware = $JwtMiddleware::getAndDecodeToken();
            if (isset($jwtMiddleware) && $jwtMiddleware == false) {
                return array(
                    'status' => 401,
                    'message' => 'Sorry, the authenticity of this token could not be verified.',
                    'data' => []
                );
            }

            $Data = json_decode($request->body(), true);
            $validationObject = array(
                (Object) [
                    'validator' => 'required',
                    'data' => isset($Data['name']) ? $Data['name'] : '',
                    'key' => 'Catalog Name'
                ]
            );
            
            $validationBag = Parent::validation($validationObject);                    
            if ($validationBag->status) {              
                $response->code(400)->json($validationBag);  
                return;
            }

            try {
                $Payload = [
                    'id' => $request->id,
                    'name' => $Data['name'],
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                $CatalogModel = new CatalogModel();
                $catalog = $CatalogModel::updateCatalog($Payload);

                if ($catalog['status']) {
                    // fetch the updated catalog...
                    $Response['status'] = 200;
                    $Response['data'] = $CatalogModel::fetchCatalogByID($Payload['id'])['data'];
                    $Response['message'] = '';

                    $response->code(200)->json($Response);
                    return;
                }

                $Response['status'] = 500;
                $Response['message'] = 'An unexpected error occurred and your Catalog could not be updated at the moment. Please, try again later.';
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

        public function fetchCatalogById($request, $response)
        {
            $Response = [];
            $JwtMiddleware = new JwtMiddleware();
            $jwtMiddleware = $JwtMiddleware::getAndDecodeToken();
            if (isset($jwtMiddleware) && $jwtMiddleware == false) {                 
                $response->code(401)->json([
                    'status' => 401,
                    'message' => 'Sorry, the authenticity of this token could not be verified.',
                    'data' => []
                ]);  
                return;
            }

            $validationObject = array(
                (Object) [
                    'validator' => 'required',
                    'data' => isset($request->id) ? $request->id : '',
                    'key' => 'Catalog Name'
                ],
                (Object) [
                    'validator' => 'numeric',
                    'data' => isset($request->id) ? $request->id : '',
                    'key' => 'Catalog ID'
                ]
            );
            
            $validationBag = Parent::validation($validationObject);                    
            if ($validationBag->status) {              
                $response->code(400)->json($validationBag);  
                return;
            }

            try {
                $CatalogModel = new CatalogModel();
                $catalog = $CatalogModel::fetchCatalogByID($request->id);
                
                if ($catalog['status']) {

                    $Response['status'] = true;
                    $Response['data'] = $catalog['data'];
                    $Response['message'] = '';

                    $response->code(200)->json($Response);
                    return;
                }
                
                $Response['status'] = 500;
                $Response['message'] = 'Sorry, An unexpected error occured and your catalog could be retrieved.';
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

        public function fetchCatalogByName($request, $response)
        {
            $Response = [];
            $JwtMiddleware = new JwtMiddleware();
            $jwtMiddleware = $JwtMiddleware::getAndDecodeToken();
            if (isset($jwtMiddleware) && $jwtMiddleware == false) {
                $response->code(401)->json([
                    'status' => 401,
                    'message' => 'Sorry, the authenticity of this token could not be verified.',
                    'data' => []
                ]);  
                return;
            }

            $validationObject = array(
                (Object) [
                    'validator' => 'required',
                    'data' => isset($request->name) ? $request->name : '',
                    'key' => 'Catalog Name'
                ],
                (Object) [
                    'validator' => 'string',
                    'data' => isset($request->name) ? $request->name : '',
                    'key' => 'Catalog Name'
                ]
            );
            
            $validationBag = Parent::validation($validationObject);                    
            if ($validationBag->status) {              
                $response->code(400)->json($validationBag);  
                return;
            }

            try {
                $CatalogModel = new CatalogModel();
                $catalog = $CatalogModel::fetchCatalogByName($request->name);

                if ($catalog['status']) {
                    $Response['status'] = true;
                    $Response['data'] = $catalog['data'];
                    $Response['message'] = '';

                    $response->code(200)->json($Response);
                    return;
                }

                $Response['status'] = 500;
                $Response['message'] = 'Sorry, An unexpected error occured and your catalog could be retrieved.';
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
            
            return;
        }

        public function fetchCatalogs($request, $response)
        {
            $Response = [];
            $JwtMiddleware = new JwtMiddleware();
            $jwtMiddleware = $JwtMiddleware::getAndDecodeToken();

            if (isset($jwtMiddleware) && $jwtMiddleware == false) {
                $response->code(401)->json([
                    'status' => 401,
                    'message' => 'Sorry, the authenticity of this token could not be verified.',
                    'data' => []
                ]);  
                return;
            }

            try {
                $CatalogModel = new CatalogModel();
                $catalogs = $CatalogModel::fetchCatalogs();

                if ($catalogs['status']) {
                    $Response['status'] = true;
                    $Response['data'] = $catalogs['data'];
                    $Response['message'] = '';

                    $response->code(200)->json($Response);
                    return;
                }

                $Response['status'] = 500;
                $Response['message'] = 'Sorry, An unexpected error occured and your catalogs could be retrieved.';
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
            
            return;
        }
    }

?>