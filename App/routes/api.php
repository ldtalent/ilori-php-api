<?php
    namespace App;
    use App\UserController;
    use App\CatalogController;

    $Klein = new \Klein\Klein();

    // Routes.....
    $Klein->respond('POST', '/api/v1/user', [ new UserController(), 'createNewUser' ]);
    $Klein->respond('POST', '/api/v1/catalog', [ new CatalogController(), 'createNewCategory' ]);
    $Klein->respond(['PATCH', 'PUT'], '/api/v1/catalog/[:id]', [ new CatalogController(),  'updateCatalog']);
    $Klein->respond(['GET', 'HEAD'], '/api/v1/fetch-catalog-by-id/[:id]', [ new CatalogController(), 'fetchCatalogById' ]);
    $Klein->respond(['GET', 'HEAD'], '/api/v1/fetch-catalog-by-name/[:name]', [ new CatalogController(), 'fetchCatalogByName' ]);
    $Klein->respond(['GET', 'HEAD'], '/api/v1/catalogs', [ new CatalogController(), 'fetchCatalogs' ]);
    // Dispatch all routes....
    $Klein->dispatch();

?>
