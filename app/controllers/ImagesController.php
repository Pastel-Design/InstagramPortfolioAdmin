<?php

namespace app\controllers;

use app\forms\UploadImageForm;
use app\models\DbManager;
use app\models\ImageManager;
use app\models\UploadManager;
use app\router\Router;
use Exception;

/**
 * Controller AlbumController
 *
 * @package app\controllers
 */
class ImagesController extends Controller
{
    private ImageManager $imageManager;

    public function __construct()
    {
        parent::__construct();
        $this->imageManager = new ImageManager();
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
        $images = $this->imageManager->getAllImages();
        $uploadImageFactory = new UploadImageForm();
        $this->data["newImageForm"] = $uploadImageFactory->create(function ($values) {
            if ($image = UploadManager::UploadSingle($values["imageFile"])) {
                $this->imageManager->uploadImage($values,$image);
            }
            Router::reroute("images");
        });
        if (isset($_SESSION["igLayout"])) {
            $igLayout = $_SESSION["igLayout"];
        } else {
            $igLayout = ($_SESSION["igLayout"] = false);
        }
        $this->data["igLayout"] = $igLayout;
        $this->data["images"] = $images;
        $this->head['page_title'] = "";
        $this->head['page_keywords'] = "";
        $this->head['page_description'] = "";
        $this->setView('default');
    }
}
