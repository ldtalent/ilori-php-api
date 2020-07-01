<?php
    namespace App;

    use App\Model;
    class CatalogModel extends Model {
        
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
    }

?>