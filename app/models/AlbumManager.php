<?php


namespace app\models;


use Exception;
use PDOException;

class AlbumManager
{
    /**
     * @param $albumTitle
     *
     * @return bool
     */
    public function albumExists($albumTitle)
    {
        return DbManager::requestAffect("SELECT title FROM album WHERE title=?", [$albumTitle]) == 1;
    }

    /**
     * @param $values
     *
     * @return bool|Exception|PDOException
     * @throws Exception
     */
    public function createAlbum($values)
    {
        var_dump($values);

        if (DbManager::requestAffect("SELECT title FROM album WHERE title=?", [$values["albumTitle"]]) > 0) {
            throw new Exception("Name already exists");
        }
        return DbManager::requestInsert('
            INSERT INTO album (id, title, dash_title, description, keywords, no_photos, added, edited, `order`, visible, cover_photo) 
            VALUES(Null,?,?,?,?,0,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,0,1,Null)
            ', [$values["albumTitle"], $values["albumDashtitle"], $values["albumDescription"], $values["albumKeywords"]]);


    }

    /**
     * @param $dashtitle
     *
     * @return array|false|void
     */
    public function getAlbumInfo($dashtitle)
    {
        if (DbManager::requestAffect("SELECT dash_title FROM album WHERE dash_title=?", [$dashtitle]) === 1) {
            $album = DbManager::requestSingle("SELECT * FROM album WHERE album.dash_title=?", [$dashtitle]);
            $images = DbManager::requestMultiple("SELECT * FROM image WHERE album_id = ?", [$album["id"]]);
            $album["images"] = $images;
            return $album;
        } else {
            return false;
        }
    }

    /**
     * @param $values
     *
     * @return Exception|void
     */
    public function editAlbum($values)
    {
        if ($this->albumExists($values["oldDashtitle"]) || $this->albumExists($values["albumDashtitle"])) {
            DbManager::requestInsert("UPDATE album SET title = ?, dash_title = ?, description = ?, keywords = ?, edited = CURRENT_TIMESTAMP, visible = ? WHERE dash_title = ?",
                [$values["albumTitle"], $values["albumDashtitle"], $values["albumDescription"], $values["albumKeywords"], $values["albumVisible"], $values["oldDashtitle"]]);
        } else {
            return new Exception;
        }
    }

    /**
     * @return array
     */
    public function getAllAlbums()
    {
        $albums = DbManager::requestMultiple("SELECT id,title,dash_title,cover_photo,no_photos,visible FROM album");
        $newAlbums = array();
        foreach ($albums as $album) {
            if ($album["cover_photo"] == Null) {
                $album["cover_photo"] = DbManager::requestUnit("SELECT filename FROM image WHERE album_id = ? ORDER BY id LIMIT 1", [$album["id"]]);
            } else {
                $album["cover_photo"] = DbManager::requestUnit("SELECT filename FROM image WHERE id = ?", [$album["cover_photo"]]);
            }
            array_push($newAlbums, $album);
        }
        return $newAlbums;
    }

    /**
     * @param array  $images
     * @param string $albumTitle
     *
     * @return void
     */
    public function uploadImages(array $images, string $albumTitle): void
    {
        $albumId = DbManager::requestUnit("SELECT id FROM album WHERE dash_title = ?", [$albumTitle]);
        for ($i = 0; $i < sizeof($images["filenames"]); $i++) {
            DbManager::requestInsert("INSERT INTO image(filename, data_type, added, edited, title, description, `order`, album_id) 
                                      VALUES(?,?,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,?,'',0,?)",
                [$images["filenames"][$i], explode(".", $images["filenames"][$i])[1], explode(".", $images["file-names"][$i])[0], $albumId]);
        }
        if (DbManager::requestUnit("SELECT cover_photo FROM album WHERE id=?", [$albumId]) == Null) {
            $coverImage = DbManager::requestUnit("SELECT id FROM image WHERE album_id = ? ORDER BY id LIMIT 1", [$albumId]);
            DbManager::requestInsert("UPDATE album SET cover_photo=? WHERE id=?", [$coverImage, $albumId]);
        }
        $currentNoPhotos=DbManager::requestUnit("SELECT no_photos FROM album WHERE id=?",[$albumId]);
        DbManager::requestInsert("UPDATE album SET no_photos = ? WHERE id=?",[($i+$currentNoPhotos),$albumId]);
    }

    /**
     * @param $title
     *
     * @return array
     */
    public function getAlbumImages($title)
    {
        $newImages = array();
        $albumId = DbManager::requestUnit("SELECT id FROM album WHERE dash_title = ?", [$title]);
        $images = DbManager::requestMultiple("SELECT * FROM image WHERE album_id = ?", [$albumId]);
        foreach ($images as $image) {
            if (DbManager::requestUnit("SELECT cover_photo FROM album WHERE id=?", [$albumId]) == $image["id"]) {
                $image["cover_photo"] = true;
            } else {
                $image["cover_photo"] = false;
            }
            array_push($newImages, $image);
        }
        return $newImages;
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function imageExists(int $id)
    {
        return DbManager::requestAffect("SELECT id FROM image WHERE id=?", [$id]) == 1;
    }

    /**
     * @param $data
     * @param $imageId
     *
     * @return boolean
     *
     * @throws PDOException
     */
    public function editImage($data, $imageId)
    {
        if (DbManager::requestInsert("UPDATE image SET title=?, description=? WHERE id=?", [$data["title"], $data["description"], $imageId]) instanceof PDOException) {
            return false;
        } else {
            DbManager::requestInsert("UPDATE image SET edited=CURRENT_TIMESTAMP WHERE id = ?", [$imageId]);
            return true;
        }
    }

    /**
     * @param $imageId
     * @param $albumId
     *
     * @return Exception|false|int|PDOException
     */
    public function setCoverPhoto($imageId, $albumId)
    {
        return DbManager::requestAffect("UPDATE album SET cover_photo=? WHERE id=?",[$imageId,$albumId]);
    }

    public function deleteImage($imageId, $albumId)
    {
        if(DbManager::requestUnit("SELECT cover_photo FROM album WHERE id = ?",[$albumId])==$imageId){
            $newCover = DbManager::requestUnit("SELECT id FROM image WHERE album_id = ? AND id <> ? ORDER BY id LIMIT 1",[$albumId,$imageId]);
            $this->setCoverPhoto($newCover,$albumId);
            DbManager::requestAffect("DELETE FROM image WHERE id = ?",[$imageId]);
        }else{
            DbManager::requestAffect("DELETE FROM image WHERE id = ?",[$imageId]);
            $newCover=null;
        }
        $currentNoPhotos=DbManager::requestUnit("SELECT no_photos FROM album WHERE id=?",[$albumId]);
        DbManager::requestInsert("UPDATE album SET no_photos = ? WHERE id=?",[($currentNoPhotos-1),$albumId]);
        return $newCover;
    }
}