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
        return DbManager::requestAffect("SELECT dash_title FROM album WHERE dash_title=?", [$albumTitle]) == 1;
    }

    /**
     * @param $values
     *
     * @return bool|Exception|PDOException
     * @throws Exception
     */
    public function createAlbum($values)
    {
        if (($order = DbManager::requestUnit("SELECT `order` FROM album ORDER BY `order` DESC LIMIT 1")) == null) {
            $order = 0;
        }
        if (DbManager::requestAffect("SELECT title FROM album WHERE title=?", [$values["albumTitle"]]) > 0) {
            throw new Exception("Name already exists");
        }
        return DbManager::requestInsert('
            INSERT INTO album (id, title, dash_title, description, keywords, no_photos, added, edited, `order`, visible, cover_photo) 
            VALUES(Null,?,?,?,?,0,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,?,1,Null)
            ', [$values["albumTitle"], $values["albumDashtitle"], $values["albumDescription"], $values["albumKeywords"],$order+1]);


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
        if (($order = DbManager::requestUnit("SELECT `order` FROM image WHERE album_id = ? ORDER BY `order` DESC LIMIT 1", [$albumId])) == null) {
            $order = 0;
        }
        for ($i = 0; $i < sizeof($images["filenames"]); $i++) {
            DbManager::requestInsert("INSERT INTO image(filename, data_type, added, edited, title, description, `order`, album_id) 
                                      VALUES(?,?,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,null,null,?,?)",
                [$images["filenames"][$i], explode(".", $images["filenames"][$i])[1], $order + $i + 1, $albumId]);
        }
        if (DbManager::requestUnit("SELECT cover_photo FROM album WHERE id=?", [$albumId]) == Null) {
            $coverImage = DbManager::requestUnit("SELECT id FROM image WHERE album_id = ? ORDER BY id LIMIT 1", [$albumId]);
            DbManager::requestInsert("UPDATE album SET cover_photo=? WHERE id=?", [$coverImage, $albumId]);
        }
        $currentNoPhotos = DbManager::requestUnit("SELECT no_photos FROM album WHERE id=?", [$albumId]);
        DbManager::requestInsert("UPDATE album SET no_photos = ? WHERE id=?", [($i + $currentNoPhotos), $albumId]);
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
        $images = DbManager::requestMultiple("SELECT * FROM image WHERE album_id = ? ORDER BY `order`", [$albumId]);
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
        return DbManager::requestAffect("UPDATE album SET cover_photo=? WHERE id=?", [$imageId, $albumId]);
    }

    /**
     * @param $imageId
     * @param $albumId
     *
     * @return mixed|void|null
     */
    public function deleteImage($imageId, $albumId)
    {
        $filename = DbManager::requestUnit("SELECT filename FROM image WHERE id = ?", [$imageId]);
        if ($albumId == null) {
            DbManager::requestAffect("DELETE FROM image WHERE id = ?", [$imageId]);
            $newCover = null;
        } else {

            if (DbManager::requestUnit("SELECT cover_photo FROM album WHERE id = ?", [$albumId]) == $imageId) {
                $newCover = DbManager::requestUnit("SELECT id FROM image WHERE album_id = ? AND id <> ? ORDER BY id LIMIT 1", [$albumId, $imageId]);
                $this->setCoverPhoto($newCover, $albumId);
                DbManager::requestAffect("DELETE FROM image WHERE id = ?", [$imageId]);
            } else {
                DbManager::requestAffect("DELETE FROM image WHERE id = ?", [$imageId]);
                $newCover = null;
            }
            $currentNoPhotos = DbManager::requestUnit("SELECT no_photos FROM album WHERE id=?", [$albumId]);
            DbManager::requestInsert("UPDATE album SET no_photos = ? WHERE id=?", [($currentNoPhotos - 1), $albumId]);
        }
        unlink("images/fullview/" . $filename);
        unlink("images/thumbnail/" . $filename);
        return $newCover;
    }

    /**
     * @param $albumId
     */
    public function deleteAlbum($albumId)
    {
        $images = DbManager::requestMultiple("SELECT filename,id FROM image WHERE album_id = ?", [$albumId]);
        $this->setCoverPhoto(null, $albumId);
        foreach ($images as $image) {
            DbManager::requestAffect("DELETE FROM image WHERE id = ?", [$image["id"]]);
            unlink("images/fullView/" . $image["filename"]);
            unlink("images/thumbnail/" . $image["filename"]);
        }
        DbManager::requestAffect("DELETE FROM album WHERE id = ?", [$albumId]);
    }

    public function reorderAlbum($imagesOrder)
    {
        try {
            foreach ($imagesOrder as $imageOrder) {
                DbManager::requestAffect("UPDATE image SET `order` = ? WHERE id = ?", [$imageOrder[0], $imageOrder[1]]);
            }
        } catch (PDOException $exception) {
            return false;
        }
        return true;

    }
}