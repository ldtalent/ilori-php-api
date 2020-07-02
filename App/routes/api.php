<?php
    namespace App;
    use App\UserController;
    use App\CatalogController;
    use App\ProductController;

    $Klein = new \Klein\Klein();

    /******************** User Routes || Authentication Routes **********************/
    $Klein->respond('POST', '/api/v1/user', [ new UserController(), 'createNewUser' ]);
    $Klein->respond('POST', '/api/v1/user-auth', [ new UserController(), 'login' ]);

    /******************** Catalog Routes **********************/
    $Klein->respond('POST', '/api/v1/catalog', [ new CatalogController(), 'createNewCatalog' ]);
    $Klein->respond(['PATCH', 'PUT'], '/api/v1/catalog/[:id]', [ new CatalogController(),  'updateCatalog']);
    $Klein->respond(['GET', 'HEAD'], '/api/v1/fetch-catalog-by-id/[:id]', [ new CatalogController(), 'fetchCatalogById' ]);
    $Klein->respond(['GET', 'HEAD'], '/api/v1/fetch-catalog-by-name/[:name]', [ new CatalogController(), 'fetchCatalogByName' ]);
    $Klein->respond(['GET', 'HEAD'], '/api/v1/catalogs', [ new CatalogController(), 'fetchCatalogs' ]);
    $Klein->respond('DELETE', '/api/v1/del-catalog/[:id]', [ new CatalogController(), 'deleteCatalog' ]);

    /******************** Product Routes  **********************/
    $Klein->respond('POST', '/api/v1/product', [ new ProductController(), 'createProduct' ]);
    $Klein->respond('POST', '/api/v1/product/[:id]', [ new ProductController(), 'updateProduct' ]);
    $Klein->respond('GET', '/api/v1/fetch/[:id]', [ new ProductController(), 'getProductById' ]);
    $Klein->respond('GET', '/api/v1/products', [ new ProductController(), 'fetchProducts' ]);
    $Klein->respond('DELETE', '/api/v1/delete-product/[:id]', [ new ProductController(), 'deleteProduct' ]);
    
    // Dispatch all routes....
    $Klein->dispatch();

?>
