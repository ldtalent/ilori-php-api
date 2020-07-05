<?php
    namespace App;

    use App\Model;

    /**
     * ProductModel - This Model is consumed basically by the ProductController and is also consumed by other controllers...
     *
     * @author      Ilori Stephen A <stephenilori458@gmail.com>
     * @link        https://github.com/learningdollars/php-rest-api/App/Model/ProductModel.php
     * @license     MIT
     */
    class ProductModel extends Model {

        /**
         * createProduct
         *
         * creates a new product
         *
         * @param array $payload  Contains all the fields that will be created.
         * @return array Anonymos
         */
        public static function createProduct($payload)
        {
            $Sql = "INSERT INTO db_products (name, catalog_id, price, color, size, banner, created_at, updated_at) VALUES (:name, :catalog_id, :price, :color, :size, :banner, :created_at, :updated_at)";

            Parent::query($Sql);
            Parent::bindParams('name', $payload['name']);
            Parent::bindParams('catalog_id', $payload['catalog_id']);
            Parent::bindParams('price', $payload['price']);
            Parent::bindParams('color', $payload['color']);
            Parent::bindParams('size', $payload['size']);
            Parent::bindParams('banner', $payload['banner']);
            Parent::bindParams('created_at', $payload['created_at']);
            Parent::bindParams('updated_at', $payload['updated_at']);

            $product = Parent::execute();
            if ($product) {
                $product_id = Parent::lastInsertedId();
                $payload['product_id'] = $product_id;

                return array(
                    'status' => true,
                    'data' => $payload
                );
            }

            return array(
                'status' => false,
                'data' => []
            );
        }

        /**
         * findProductById
         *
         * fetches a product by it's ID
         *
         * @param int $Id  The Id of the row to be returned...
         * @return array Anonymos
         */
        public static function findProductById($Id)
        {
            $Sql = "SELECT * FROM `db_products` WHERE id = :id";
            Parent::query($Sql);
            Parent::bindParams('id', $Id);
            $product = Parent::fetch();
            if (!empty($product)) {
                return array(
                    'status' => true,
                    'data' => $product
                );
            }

            return array(
                'status' => false,
                'data' => []
            );
        }

        /**
         * fetchProducts
         *
         * fetches a list of products..
         *
         * @param void
         * @return array Anonymos
         */
        public static function fetchProducts()
        {
            $Sql = "SELECT * FROM `db_products`";
            Parent::query($Sql);
            $products = Parent::fetchAll();
            if (!empty($products)) {
                return array(
                    'status' => true,
                    'data' => $products
                );
            }

            return array(
                'status' => false,
                'data' => []
            );
        }

        /**
         * updateProduct
         *
         * update a product based on the product ID
         *
         * @param array  $Payload  An array of values to be updated...
         * @return array Anonymos
         */
        public static function updateProduct($Payload)
        {
            $Sql = "UPDATE `db_products` SET name = :name, catalog_id = :catalog_id, price = :price, color = :color, size = :size, price = :price, banner = :banner, updated_at = :updated_at WHERE id = :id";
            Parent::query($Sql);

            Parent::bindParams('id', $Payload['id']);
            Parent::bindParams('name', $Payload['name']);
            Parent::bindParams('catalog_id', $Payload['catalog_id']);
            Parent::bindParams('price', $Payload['price']);
            Parent::bindParams('color', $Payload['color']);
            Parent::bindParams('size', $Payload['size']);
            Parent::bindParams('price', $Payload['price']);
            Parent::bindParams('banner', $Payload['banner']);
            Parent::bindParams('updated_at', $Payload['updated_at']);

            $product = Parent::execute();
            if ($product) {
                return array(
                    'status' => true,
                    'data' => $Payload,
                );
            }

            return array(
                'status' => false,
                'data' => []
            );
        }

        /**
         * deleteProduct
         *
         * deletes a product based on the product ID
         *
         * @param int $Id  An array of values to be deleted...
         * @return array Anonymos
         */
        public static function deleteProduct($Id)
        {
            $Sql = "DELETE FROM `db_products` WHERE id = :id";
            Parent::query($Sql);
            Parent::bindParams('id', $Id);
            $product = Parent::execute();
            if (!empty($product)) {
                return array(
                    'status' => true,
                    'data' => []
                );
            }

            return array(
                'status' => false,
                'data' => []
            );
        }
    }
?>
