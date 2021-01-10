<?php

namespace app\controllers;

use app\forms\EditAlbum;
use app\forms\NewAlbum;
use app\forms\UploadAlbumImages;
use app\models\AlbumManager;
use app\router\Router;
use Exception;
use PDOException;

/**
 * Controller AlbumController
 *
 * @package app\controllers
 */
class AlbumsController extends Controller
{

    /**
     * @var AlbumManager $albuManager
     */
    private AlbumManager $albumManager;

    public function __construct()
    {
        parent::__construct();
        $this->albumManager = new AlbumManager();
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
        if (isset($params[0])) {
            switch ($params[0]) {
                case "all":
                    $this->allAlbums();
                    break;
                case "new":
                    $this->newAlbum();
                    break;
                case "edit":
                    if (isset($params[1])) {
                        if ($this->albumManager->albumExists($params[1])) {
                            $this->editAlbum($params[1]);
                        } else {
                            Router::reroute("albums/all");
                        }
                    } else {
                        Router::reroute("albums/all");
                    }
                    break;
                default:
                    Router::reroute("albums/all");
                    break;
            }
        } else {
            $this->defaultPage();
        }

    }

    private function newAlbum()
    {
        $newAlbumFactory = new NewAlbum();
        $this->data["form"] = $newAlbumFactory->create(function ($values) {
            $values["albumDashtitle"] = $this->basicToDash($values["albumTitle"]);
            try {
                $this->albumManager->createAlbum($values);
                Router::reroute("albums/edit/" . $values["albumDashtitle"]);
            } catch (Exception $exception) {
                $this->data["message"] = "Error occurred, try again!";
            }
        });
        $this->head['page_title'] = "";
        $this->head['page_keywords'] = "";
        $this->head['page_description'] = "";
        $this->setView('new');
    }

    private function allAlbums()
    {
        $this->data["albums"] = $this->albumManager->getAllAlbums();
        $this->head['page_title'] = "";
        $this->head['page_keywords'] = "";
        $this->head['page_description'] = "";
        $this->setView('all');
    }

    private function editAlbum($title)
    {
        $album = $this->albumManager->getAlbumInfo($title);
        $editAlbumFactory = new EditAlbum($album["title"], $album["description"], $album["keywords"], $album["visible"]);
        $uploadAlbumImages = new UploadAlbumImages();

        $this->data["editForm"] = $editAlbumFactory->create(function ($values) use ($title) {
            $values["oldDashtitle"] = $title;
            $values["albumDashtitle"] = $this->basicToDash($values["albumTitle"]);
            try {
                $this->albumManager->editAlbum($values);
                Router::reroute("albums/edit/" . $values["albumDashtitle"]);
            } catch (Exception $exception) {
                $this->data["message"] = "Error occurred, try again!";
            }
        });
        $this->data["uploadForm"] = $uploadAlbumImages->create(function ($values) {
            $_SESSION["images"]=$values;
            var_dump($_SESSION);
        });
        $this->data["album"] = $album;
        $this->head['page_title'] = "";
        $this->head['page_keywords'] = "";
        $this->head['page_description'] = "";
        $this->setView('edit');
    }

    private function defaultPage()
    {
        $this->head['page_title'] = "";
        $this->head['page_keywords'] = "";
        $this->head['page_description'] = "";
        $this->setView('default');
    }


}
