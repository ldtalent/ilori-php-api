<?php
    namespace App;
    use App\UserController;
    use App\CatalogController;
    use App\ProductController;

    $Klein = new \Klein\Klein();

    // Routes.....
    $Klein->respond('POST', '/api/v1/user', [ new UserController(), 'createNewUser' ]);


    $Klein->respond('POST', '/api/v1/catalog', [ new CatalogController(), 'createNewCategory' ]);
    $Klein->respond(['PATCH', 'PUT'], '/api/v1/catalog/[:id]', [ new CatalogController(),  'updateCatalog']);
    $Klein->respond(['GET', 'HEAD'], '/api/v1/fetch-catalog-by-id/[:id]', [ new CatalogController(), 'fetchCatalogById' ]);
    $Klein->respond(['GET', 'HEAD'], '/api/v1/fetch-catalog-by-name/[:name]', [ new CatalogController(), 'fetchCatalogByName' ]);
    $Klein->respond(['GET', 'HEAD'], '/api/v1/catalogs', [ new CatalogController(), 'fetchCatalogs' ]);

    $Klein->respond('POST', '/api/v1/product', [ new ProductController(), 'createProduct' ]);
    $Klein->respond('POST', '/api/v1/product/[:id]', [ new ProductController(), 'updateProduct' ]);
    $Klein->respond('GET', '/api/v1/fetch/[:id]', [ new ProductController(), 'getProductById' ]);
    $Klein->respond('GET', '/api/v1/products', [ new ProductController(), 'fetchProducts' ]);
    // Dispatch all routes....
    $Klein->dispatch();

?>
