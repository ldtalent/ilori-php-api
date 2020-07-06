<?php
    namespace App;

    use App\Model;

    /**
     * CatalogModel - A Model for the Catalog Controller.
     *
     * @author      Ilori Stephen A <stephenilori458@gmail.com>
     * @link        https://github.com/learningdollars/php-rest-api/App/Model/CatalogModel.php
     * @license     MIT
     */
    class CatalogModel extends Model {

        /**
         * createCatalog
         *
         * Creates a New Catalog
         *
         * @param array $Payload  Contains all the required data needed to create a Catalog.
         * @return array Anonymous
         */
        public static function createCatalog($Payload)
        {
            $Sql = "INSERT INTO `db_catalogs` (name, created_at, updated_at) VALUES (:name, :created_at, :updated_at)";
            Parent::query($Sql);

            Parent::bindParams('name', $Payload['name']);
            Parent::bindParams('created_at', $Payload['created_at']);
            Parent::bindParams('updated_at', $Payload['updated_at']);

            $catalog = Parent::execute();
            if ($catalog) {
                $catalog_id = Parent::lastInsertedId();
                $Payload['catalog_id'] = $catalog_id;
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
         * updateCatalog
         *
         * Updates a New Catalog
         *
         * @param array $Payload  Contains all the fields that will be updated.
         * @return array Anonymous
         */
        public static function updateCatalog($Payload)
        {
            $Sql = "UPDATE `db_catalogs` SET name = :name, updated_at = :updated_at WHERE id = :id";
            Parent::query($Sql);

            Parent::bindParams('id', $Payload['id']);
            Parent::bindParams('name', $Payload['name']);
            Parent::bindParams('updated_at', $Payload['updated_at']);

            $catalog = Parent::execute();
            if ($catalog) {
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
         * fetchCatalogByID
         *
         * Returns the first Catalog that matches the ID
         *
         * @param int $Id    The Id of the Row to be updated.
         * @return array Anonymous
         */
        public static function fetchCatalogByID($Id)
        {
            $Sql = "SELECT * FROM `db_catalogs` WHERE id = :id";
            Parent::query($Sql);

            Parent::bindParams('id', $Id);
            $catalog = Parent::fetch();
            if (empty($catalog)) {
                return array(
                    'status' => false,
                    'data' => []
                );
            }

            return array(
                'status' => true,
                'data' => $catalog
            );
        }

        /**
         * fetchCatalogByName
         *
         * Returns the first Catalog that matches the name
         *
         * @param string $name   The name of the row to be updated.
         * @return array Anonymous
         */
        public static function fetchCatalogByName($name)
        {
            $Sql = "SELECT * FROM `db_catalogs` WHERE name = :name";
            Parent::query($Sql);

            Parent::bindParams('name', $name);
            $catalog = Parent::fetch();
            if (empty($catalog)) {
                return array(
                    'status' => false,
                    'data' => []
                );
            }

            return array(
                'status' => true,
                'data' => $catalog
            );
        }

        /**
         * fetchCatalogs
         *
         * Returns a list of catalogs.
         *
         * @param void
         * @return array Anonymous
         */
        public static function fetchCatalogs()
        {
            $Sql = "SELECT * FROM `db_catalogs`";
            Parent::query($Sql);

            $catalogs = Parent::fetchAll();
            if (empty($catalogs)) {
                return array(
                    'status' => false,
                    'data' => []
                );
            }

            return array(
                'status' => true,
                'data' => $catalogs
            );
        }

        /**
         * deleteCatalog
         *
         * Deletes a catalog
         *
         * @param int $Id       The Id of the catalog to be deleted.
         * @return array Anonymous
         */
        public static function deleteCatalog($Id)
        {
            $Sql = "DELETE FROM `db_catalogs` WHERE id = :id";
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
