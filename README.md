### PHP Mini Rest Api

Hi there, My name is Ilori Stephen Adejuwon and I am a Fullstack Developer (Backend Heavy) and this project demonstrates how to create a **REST** **API** using **PHP** and **JWT** for authentication. The term **REST** stands for **REPRESENTATIONAL STATE TRANSFER** and it is used for transferring data by exposing various endpoints via an HTTP Interface.

Without further ado, If you will like to find more information about this topic, I suggest you read my Article on learning dollars where I took my time to explain the term REST API and it also covers how you can create yours.

#### Steps....

1. Clone the project
2. Open the project in your terminal and run the command `composer install`. This will install all of the project dependencies.
3. Create your database and upload the sql file in the project's root dir.
4. Open the Model Folder and edit the Model.php file. You are editing the protected properties of this class. Replace them with your system environment variables.
5. You can now begin creating your own Controllers, Models or Middlewares inside of the already created directory called Controller, Model and Middleware respectively.
6. After doing the above instructions, you can open the project in your terminal or cmd and run the command `php -S 127.0.0.1:8000`
7. Well, your project is up and running now. you can now open [Postman](https://postman.io) and begin consuming endpoints.


#### EndPoints

By default, these are the list of endpoints available when you clone the project. Your router || `api.php` lives inside of the `router directory` inside of the `App folder`.

#### User Endpoints

1. Create New User **`/api/v1/user`**: **`HTTP POST`**

```php
    $Klein->respond('POST', '/api/v1/user', [ new UserController(), 'createNewUser' ]);
```

2. Login A User **`/api/v1/user-auth`**: **`HTTP POST`**

```php
    $Klein->respond('POST', '/api/v1/user-auth', [ new UserController(), 'login' ]);
```

#### Catalog Endpoints

1. Create New Catalog **`/api/v1/catalog`**: **` HTTP POST`**

```php
    $Klein->respond('POST', '/api/v1/catalog', [ new CatalogController(), 'createNewCatalog' ]);
```

2. Updates A Catalog **`/api/v1/catalog`**: **` HTTP PUT || HTTP PATCH`**

```php
    $Klein->respond(['PATCH', 'PUT'], '/api/v1/catalog/[:id]', [ new CatalogController(),  'updateCatalog']);
```

3. Fetches A Catalog By Id **`/api/v1/fetch-catalog-by-id/[:id]`**: **` HTTP GET || HTTP HEAD`**

```php
    $Klein->respond(['GET', 'HEAD'], '/api/v1/fetch-catalog-by-id/[:id]', [ new CatalogController(), 'fetchCatalogById' ]);
```

4. Fetches A Catalog By Id **`/api/v1/fetch-catalog-by-id/[:id]`**: **` HTTP GET || HTTP HEAD`**

```php
    $Klein->respond(['GET', 'HEAD'], '/api/v1/fetch-catalog-by-id/[:id]', [ new CatalogController(), 'fetchCatalogById' ]);
```

5. Deletes A Catalog By It's ID **`/api/v1/del-catalog/[:id]`**: **` HTTP DELETE`**

```php
    $Klein->respond('DELETE', '/api/v1/del-catalog/[:id]', [ new CatalogController(), 'deleteCatalog' ]);
```

#### Product Endpoints

1. Create A Product **`/api/v1/product/`**: **` HTTP POST`**

```php
    $Klein->respond('POST', '/api/v1/product', [ new ProductController(), 'createProduct' ]);    
```

2. Updates A Product **`/api/v1/product/[:id]`**: **` HTTP POST`**

```php
    $Klein->respond('POST', '/api/v1/product/[:id]', [ new ProductController(), 'updateProduct' ]);   
```

3. Get A Product By It's ID **`/api/v1/product/[:id]`**: **` HTTP GET || HTTP HEAD`**

```php
    $Klein->respond('GET', '/api/v1/fetch/[:id]', [ new ProductController(), 'getProductById' ]);
```

4. Get A List Of Products **`/api/v1/products`**: **` HTTP GET || HTTP HEAD`**

```php
    $Klein->respond('GET', '/api/v1/products', [ new ProductController(), 'fetchProducts' ]);
```

5. Delete A Product By It's ID **`/api/v1/products`**: **` HTTP GET || HTTP DELETE`**

```php
    $Klein->respond(['DELETE', 'GET'], '/api/v1/delete-product/[:id]', [ new ProductController(), 'deleteProduct' ]);
```

Feel free to give this a spin and if you feel like, You can also contribute to the Project. Thank you very much, I remain Ilori Stephen A!
