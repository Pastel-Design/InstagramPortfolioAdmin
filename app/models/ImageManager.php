<?php


namespace app\models;


use Exception;
use PDOException;

class ImageManager
{
    /**
     * @param $formValues
     * @param $filename
     *
     * @return Exception|false|int|PDOException
     */
    public function uploadImage($formValues, $filename)
    {
        if (($order = DbManager::requestUnit("SELECT `order` FROM image WHERE album_id IS NULL ORDER BY `order` DESC LIMIT 1")) == null) {
            $order = 0;
        }
        $formValues["imageDescription"] = ($formValues["imageDescription"] == "" ? null : $formValues["imageDescription"]);
        $formValues["imageTitle"] = ($formValues["imageTitle"] == "" ? null : $formValues["imageTitle"]);
        return DbManager::requestAffect('
            INSERT INTO image(filename, data_type, added, edited, title, description, `order`, album_id) 
            VALUES(?,?,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,?,?,?,null)',
            [$filename, explode(".", $filename)[1], $formValues["imageTitle"], $formValues["imageDescription"], $order]);
    }

    /**
     * @return array|void
     */

    public function getAllImages()
    {
        return DbManager::requestMultiple("SELECT * FROM image WHERE album_id IS NULL ORDER BY `order`");
    }
}