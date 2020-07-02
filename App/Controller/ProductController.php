<?php
    namespace App;

    use App\UserModel;
    use App\Controller;
    use App\ProductModel;
    use App\JwtMiddleware;
    use App\RequestMiddleware;
    use App\CatalogController;

    /**
     * ProductController - The ProductController. This Controller makes use of a few Models for creating, updating, fetching and deleting Products.
     *
     * @author      Ilori Stephen A <stephenilori458@gmail.com>
     * @link        https://github.com/learningdollars/php-rest-api/App/Controller/ProductController.php
     * @license     MIT
     */
    class ProductController extends Controller {

         /**
         * createProduct
         *
         * Creates a new Product.
         *
         * @param mixed $request $response Contains the Request and Respons Object from the router.
         * @return mixed Annonymous
        */
        public function createProduct($request, $response)
        {
            $Response = [];
            // Call the JSON Middleware
            $FormDataMiddleware = new RequestMiddleware();
            $formData = $FormDataMiddleware::acceptsFormData();
        
            if (!$formData) {
                array_push($Response, [
                    'status' => 400,
                    'message' => 'Sorry, Only Multipart Form Data Contents are allowed to access this Endpoint.',
                    'data' => []
                ]);

                $response->code(400)->json($Response);
                return;
            }

            $JwtMiddleware = new JwtMiddleware();
            $jwtMiddleware = $JwtMiddleware::getAndDecodeToken();
            if (isset($jwtMiddleware) && $jwtMiddleware == false) {
                $response->code(400)->json([
                    'status' => 401,
                    'message' => 'Sorry, the authenticity of this token could not be verified.',
                    'data' => []
                ]);
                return;
            }

            $Data = $request->paramsPost();
            $validationObject = array(
                (Object) [
                    'validator' => 'required',
                    'data' => isset($Data->name) ? $Data->name : '',
                    'key' => 'Product Name'
                ],
                (Object) [
                    'validator' => 'required',
                    'data' => isset($Data->catalog_id) ? $Data->catalog_id : '',
                    'key' => 'Product Catalog'
                ],
                (Object) [
                    'validator' => 'catalogExists',
                    'data' => isset($Data->catalog_id) ? $Data->catalog_id : '',
                    'key' => 'Product Catalog'
                ],
                (Object) [
                    'validator' => 'required',
                    'data' => isset($Data->price) ? $Data->price : '',
                    'key' => 'Product Price'
                ],
                (Object) [
                    'validator' => 'numeric',
                    'data' => isset($Data->price) ? $Data->price : '',
                    'key' => 'Product Price'
                ],
                (Object) [
                    'validator' => 'required',
                    'data' => isset($Data->color) ? $Data->color : '',
                    'key' => 'Product Color'
                ],
                (Object) [
                    'validator' => 'string',
                    'data' => isset($Data->color) ? $Data->color : '',
                    'key' => 'Product Color'
                ],
                (Object) [
                    'validator' => 'required',
                    'data' => isset($Data->size) ? $Data->size : '',
                    'key' => 'Product Size'
                ],
                (Object) [
                    'validator' => 'required',
                    'data' => !empty($request->files()->banner) ? $request->files()->banner : '',
                    'key' => 'Product Banner',
                ],
                (Object) [
                    'validator' => 'img',
                    'data' => !empty($request->files()->banner) ? $request->files()->banner : '',
                    'key' => 'Product Banner',
                    'file_name' => 'banner',
                    'acceptedExtension' => ['jpg', 'png', 'gif', 'jpeg'],
                    'maxSize' => 5000000
                ],
            );
            
            $validationBag = Parent::validation($validationObject);                    
            if ($validationBag->status) {              
                $response->code(400)->json($validationBag);  
                return;
            }

            // Work the banner image...
            $bannerPath = './public/img/';
            $bannerName = time() . '_' . basename($request->files()->banner['name']);
            if (!move_uploaded_file($request->files()->banner['tmp_name'], $bannerPath . $bannerName)) {
                
                $Response['status'] = 400;
                $Response['data'] = [];
                $Response['message'] = 'An unexpected error occuured and your file could not be uploaded. Please, try again later.';

                $response->code(400)->json($Response);  
                return;
            }
            // create the product...
            $Payload = array(
                'name' => htmlentities(stripcslashes(strip_tags($Data->name))),
                'catalog_id' => (int) htmlentities(stripcslashes(strip_tags($Data->catalog_id))),
                'color' => htmlentities(stripcslashes(strip_tags($Data->color))),
                'price' => (float) htmlentities(stripcslashes(strip_tags($Data->price))),
                'size' => \htmlentities(\stripcslashes(strip_tags($Data->size))),
                'banner' => 'public/img/' . $bannerName,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );

            try {
                $ProductModel = new ProductModel();
                $product = $ProductModel::createProduct($Payload);
                if ($product['status']) {
                    $Response['status'] = 201;
                    $Response['data'] = $product['data'];
                    $Response['message'] = '';

                    $response->code(201)->json($Response);
                    return;
                }

                $Response['status'] = 400;
                $Response['data'] = [];
                $Response['message'] = 'An unexpected error occurred and your product could not be created. Please, try again later.';
                
                $response->code(400)->json($Response);
                return;
            } catch (Exception $e) {  
                $Response['status'] = 500;
                $Response['message'] = $e->getMessage();
                $Response['data'] = [];
                
                $response->code(500)->json($Response);
                return;
            }
        }

        /**
         * updateProduct
         *
         * Updates a Product.
         *
         * @param mixed $request $response Contains the Request and Respons Object from the router.
         * @return mixed Annonymous
        */
        public function updateProduct($request, $response)
        {
            $Response = [];
            // Call the JSON Middleware
            $ProductModel = new ProductModel();
            $FormDataMiddleware = new RequestMiddleware();
            $formData = $FormDataMiddleware::acceptsFormData();
        
            if (!$formData) {
                array_push($Response, [
                    'status' => 400,
                    'message' => 'Sorry, Only Multipart Form Data Contents are allowed to access this Endpoint.',
                    'data' => []
                ]);

                $response->code(400)->json($Response);
                return;
            }

            $JwtMiddleware = new JwtMiddleware();
            $jwtMiddleware = $JwtMiddleware::getAndDecodeToken();
            if (isset($jwtMiddleware) && $jwtMiddleware == false) {
                $response->code(400)->json([
                    'status' => 401,
                    'message' => 'Sorry, the authenticity of this token could not be verified.',
                    'data' => []
                ]);
                return;
            }

            $Data = $request->paramsPost();
            $validationObject = array(
                (Object) [
                    'validator' => 'required',
                    'data' => isset($request->id) ? $request->id : '',
                    'key' => 'Product ID'
                ],
                (Object) [
                    'validator' => 'productExists',
                    'data' => isset($request->id) ? $request->id : '',
                    'key' => 'Product Id'
                ],
                (Object) [
                    'validator' => 'required',
                    'data' => isset($Data->name) ? $Data->name : '',
                    'key' => 'Product Name'
                ],
                (Object) [
                    'validator' => 'required',
                    'data' => isset($Data->catalog_id) ? $Data->catalog_id : '',
                    'key' => 'Product Catalog'
                ],
                (Object) [
                    'validator' => 'catalogExists',
                    'data' => isset($Data->catalog_id) ? $Data->catalog_id : '',
                    'key' => 'Product Catalog'
                ],
                (Object) [
                    'validator' => 'required',
                    'data' => isset($Data->price) ? $Data->price : '',
                    'key' => 'Product Price'
                ],
                (Object) [
                    'validator' => 'numeric',
                    'data' => isset($Data->price) ? $Data->price : '',
                    'key' => 'Product Price'
                ],
                (Object) [
                    'validator' => 'required',
                    'data' => isset($Data->color) ? $Data->color : '',
                    'key' => 'Product Color'
                ],
                (Object) [
                    'validator' => 'string',
                    'data' => isset($Data->color) ? $Data->color : '',
                    'key' => 'Product Color'
                ],
                (Object) [
                    'validator' => 'required',
                    'data' => isset($Data->size) ? $Data->size : '',
                    'key' => 'Product Size'
                ],
                (Object) [
                    'validator' => !empty($request->files()->banner) ? 'img' : 'nullable',
                    'data' => !empty($request->files()->banner) ? $request->files()->banner : '',
                    'key' => 'Product Banner',
                    'file_name' => 'banner',
                    'acceptedExtension' => ['jpg', 'png', 'gif', 'jpeg'],
                    'maxSize' => 5000000
                ],
            );
            
            $validationBag = Parent::validation($validationObject);                    
            if ($validationBag->status) {              
                $response->code(400)->json($validationBag);  
                return;
            }

            // Work the banner image...
            $banner = 'public/img/';
            if (!empty($request->files()->banner)) {                            
                
                $product = $ProductModel::findProductById($request->id)['data'];
                if (file_exists($product['banner'])) {
                    unlink($product['banner']);
                }
                
                $bannerPath = './public/img/';
                $bannerName = time() . '_' . basename($request->files()->banner['name']);
                if (!move_uploaded_file($request->files()->banner['tmp_name'], $bannerPath . $bannerName)) {
                    
                    $Response['status'] = 400;
                    $Response['data'] = [];
                    $Response['message'] = 'An unexpected error occuured and your file could not be uploaded. Please, try again later.';

                    $response->code(400)->json($Response);  
                    return;
                }

                $banner .= $bannerName;
            }
            

             // create the product...
             $Payload = array(
                'id' => $request->id,
                'name' => htmlentities(stripcslashes(strip_tags($Data->name))),
                'catalog_id' => (int) htmlentities(stripcslashes(strip_tags($Data->catalog_id))),
                'color' => htmlentities(stripcslashes(strip_tags($Data->color))),
                'price' => (float) htmlentities(stripcslashes(strip_tags($Data->price))),
                'size' => \htmlentities(\stripcslashes(strip_tags($Data->size))),
                'banner' => $banner,
                'updated_at' => date('Y-m-d H:i:s')
            );

            try {                
                $product = $ProductModel::updateProduct($Payload);
                if ($product['status']) {
                    $product['data'] = $ProductModel::findProductById($request->id)['data'];
                    $Response['status'] = 200;
                    $Response['data'] = $product['data'];
                    $Response['message'] = '';

                    $response->code(200)->json($Response);
                    return;
                }

                $Response['status'] = 400;
                $Response['data'] = [];
                $Response['message'] = 'An unexpected error occurred and your product could not be updated. Please, try again later.';
                
                $response->code(400)->json($Response);
                return;
            } catch (Exception $e) {  
                $Response['status'] = 500;
                $Response['message'] = $e->getMessage();
                $Response['data'] = [];
                
                $response->code(500)->json($Response);
                return;
            }
        }

        /**
         * getProductById
         *
         * Gets a Product by it'd ID
         *
         * @param mixed $request $response Contains the Request and Respons Object from the router.
         * @return mixed Annonymous
        */
        public function getProductById($request, $response)
        {
            $Response = [];
            // Call the Middleware
            $ProductModel = new ProductModel();

            $JwtMiddleware = new JwtMiddleware();
            $jwtMiddleware = $JwtMiddleware::getAndDecodeToken();
            if (isset($jwtMiddleware) && $jwtMiddleware == false) {
                $response->code(400)->json([
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
                    'key' => 'Product ID'
                ],
                (Object) [
                    'validator' => 'productExists',
                    'data' => isset($request->id) ? $request->id : '',
                    'key' => 'Product Id'
                ],
            );

            $validationBag = Parent::validation($validationObject);                    
            if ($validationBag->status) {              
                $response->code(400)->json($validationBag);  
                return;
            }

            try {
                $ProductModel = new ProductModel();
                $product = $ProductModel::findProductById($request->id);

                if ($product['status']) {
                    $Response['status'] = 200;
                    $Response['data'] = $product['data'];
                    $Response['message'] = '';

                    $response->code(200)->json($Response);
                    return;
                }

                $Response['status'] = 400;
                $Response['data'] = [];
                $Response['message'] = 'An unexpected error occurred and your product could not be retrieved. Please, try again later.';
                
                $response->code(400)->json($Response);
                return;
            } catch (Exception $e) {
                $Response['status'] = 500;
                $Response['message'] = $e->getMessage();
                $Response['data'] = [];
                
                $response->code(500)->json($Response);
                return;
            }
        }

        /**
         * fetchProducts
         *
         * Fetches an Array of products....
         *
         * @param mixed $request $response Contains the Request and Respons Object from the router.
         * @return mixed Annonymous
        */
        public function fetchProducts($request, $response)
        {
            $Response = [];
            // Call the Middleware
            $ProductModel = new ProductModel();

            $JwtMiddleware = new JwtMiddleware();
            $jwtMiddleware = $JwtMiddleware::getAndDecodeToken();
            if (isset($jwtMiddleware) && $jwtMiddleware == false) {
                $response->code(400)->json([
                    'status' => 401,
                    'message' => 'Sorry, the authenticity of this token could not be verified.',
                    'data' => []
                ]);
                return;
            }

            try {
                $ProductModel = new ProductModel();
                $products = $ProductModel::fetchProducts();

                if ($products['status']) {
                    $Response['status'] = 200;
                    $Response['data'] = $products['data'];
                    $Response['message'] = '';

                    $response->code(200)->json($Response);
                    return;
                }

                $Response['status'] = 400;
                $Response['data'] = [];
                $Response['message'] = 'An unexpected error occurred and your product could not be retrieved. Please, try again later.';
                
                $response->code(400)->json($Response);
                return;
            } catch (Exception $e) {
                $Response['status'] = 500;
                $Response['message'] = $e->getMessage();
                $Response['data'] = [];
                
                $response->code(500)->json($Response);
                return;
            }
        }
        
         /**
         * deleteProduct
         *
         * Deletes a Product by it'd ID
         *
         * @param mixed $request $response Contains the Request and Respons Object from the router.
         * @return mixed Annonymous
         */
        public function deleteProduct($request, $response)
        {
            $Response = [];
            // Call the Middleware
            $ProductModel = new ProductModel();

            $JwtMiddleware = new JwtMiddleware();
            $jwtMiddleware = $JwtMiddleware::getAndDecodeToken();
            if (isset($jwtMiddleware) && $jwtMiddleware == false) {
                $response->code(400)->json([
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
                    'key' => 'Product ID'
                ],
                (Object) [
                    'validator' => 'productExists',
                    'data' => isset($request->id) ? $request->id : '',
                    'key' => 'Product Id'
                ],
            );

            $validationBag = Parent::validation($validationObject);                    
            if ($validationBag->status) {              
                $response->code(400)->json($validationBag);  
                return;
            }

            try {
                $ProductModel = new ProductModel();
                $product = $ProductModel::deleteProduct($request->id);

                if ($product['status']) {
                    $Response['status'] = 200;
                    $Response['data'] = [];
                    $Response['message'] = '';

                    $response->code(200)->json($Response);
                    return;
                }

                $Response['status'] = 400;
                $Response['data'] = [];
                $Response['message'] = 'An unexpected error occurred and your product could not be deleted. Please, try again later.';
                
                $response->code(400)->json($Response);
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