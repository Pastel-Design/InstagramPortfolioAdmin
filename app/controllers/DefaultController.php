<?php

namespace app\controllers;

use app\models\AlbumManager;
use app\models\DbManager;
use app\models\ImageManager;
use app\models\UploadManager;

/**
 * Controller DefaultController
 *
 * @package app\controllers
 */
class DefaultController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Sets default homepage
     *
     * @param array      $params
     * @param array|null $gets
     *
     * @return void
     */
    public function process(array $params, array $gets = null)
    {
        /*
        set_time_limit(0);
        $albumManager = new AlbumManager();
        $albums = scandir("images/Gallery");
        array_shift($albums);
        array_shift($albums);
        $newAlbums = array();
        foreach ($albums as $album) {
            $images = scandir("images/Gallery/" . $album);
            array_shift($images);
            array_shift($images);
            $newAlbum = ["albumName" => $album, "images" => $images];
            array_push($newAlbums, $newAlbum);
        }
        $newAlbums = array_slice($newAlbums,20,112);
        foreach ($newAlbums as $targetAlbum) {
        $fileNames=array();
            foreach ($targetAlbum["images"] as $image) {
                array_push($fileNames,UploadManager::uploadFromFolder($image, $targetAlbum["albumName"]));
            }
            $albumName = $targetAlbum["albumName"];
            $albumDashName = $this->basicToDash($albumName);
            var_dump($albumName);
            var_dump($fileNames);
            if (($order = DbManager::requestUnit("SELECT `order` FROM album ORDER BY `order` DESC LIMIT 1")) == null) {
                $order = 0;
            }
            DbManager::requestInsert("
                INSERT INTO album(title, dash_title, description, keywords, no_photos, added, edited, `order`, cover_photo) 
                VALUES(?,?,'','',?,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,?,null)",
                [$albumName, $albumDashName, 0, $order+1]);
            $albumManager->uploadImages(["filenames"=>$fileNames],$albumDashName);
        }*/
        //FILE UPLOAD FOR IMAGES
        /*
              set_time_limit(0);
                $imageManager = new ImageManager();
                $images = scandir("images/Gallery/Instagram");
                array_shift($images);
                array_shift($images);
                $images = array_reverse($images);

                foreach ($images as $image){
                    $filename=UploadManager::uploadFromFolder($image,"Instagram");
                    $imageManager->uploadImage(["imageTitle"=>null,"imageDescription"=>null],$filename);
                }
        */
        $this->head['page_title'] = "";
        $this->head['page_keywords'] = "";
        $this->head['page_description'] = "";
        $this->setView('default');
    }
}
