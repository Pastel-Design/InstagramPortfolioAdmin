<?php

namespace app\controllers;

use app\models\AlbumManager;
use Exception;

/**
 * Controller HandleController
 *
 * @package app\controllers
 */
class HandleController extends Controller
{
    public function __construct()
    {
        $this->albumManager = new AlbumManager();
        parent::__construct();
    }

    private AlbumManager $albumManager;
    protected array $data = [];
    protected array $head = [];

    /**
     * Handles ajax requests
     *
     * @param array      $params
     * @param array|null $gets
     *
     * @return void
     * @throws Exception
     */
    public function process(array $params, array $gets = null)
    {
        if (isset($params[0])) {
            $function = str_replace("-", "", ucfirst(strtolower($params[0])));
            array_shift($params);
            try {
                call_user_func(array($this, $function), $params, $gets);
            } catch (Exception $e) {
                header($e->getMessage());
                http_response_code(404);
            }
        } else {
            http_response_code(404);
        }
    }

    /**
     * @return void
     */
    public function writeView(): void
    {
        $return = array_merge($this->head, $this->data);
        echo(json_encode($return));
    }

    /**
     * @param mixed $params
     *
     * @param mixed $gets
     *
     * @return void
     */
    public function editImage($params, $gets)
    {
        $imageId = $params[0];
        $data = $gets;
        if ((string)(int)$imageId != $imageId) {
            http_response_code(404);
        }
        if ($this->albumManager->imageExists((int)$imageId)) {
            if (count($data) != 2) {
                http_response_code(404);
            } elseif (array_key_exists("title", $data) && array_key_exists("description", $data)) {
                $data["description"] = ($data["description"] == "" ? null : $data["description"]);
                $data["title"] = ($data["title"] == "" ? null : $data["title"]);
                $this->data["response"] = $this->albumManager->editImage($data, $imageId);
                http_response_code(200);
            } else {
                http_response_code(404);
            }
        } else {
            http_response_code(404);
        }
    }

    /**
     * @param $params
     * @param $gets
     */
    public function setCoverPhoto($params, $gets)
    {
        if (count($gets) != 2) {
            http_response_code(404);
        } elseif (array_key_exists("albumId", $gets) && array_key_exists("imageId", $gets)) {
            $this->data["response"] = $this->albumManager->setCoverPhoto($gets["imageId"], $gets["albumId"]);
            http_response_code(200);
        } else {
            http_response_code(404);
        }
    }

    /**
     * @param $params
     * @param $gets
     */
    public function deleteImage($params, $gets)
    {
        if (count($gets) != 2) {
            http_response_code(404);
        } elseif (array_key_exists("albumId", $gets) && array_key_exists("imageId", $gets)) {
            $newCover = $this->albumManager->deleteImage($gets["imageId"], $gets["albumId"]);
            if ($newCover) {
                $this->data["response"] = $newCover;
            } else {
                $this->data["response"] = true;
            }
            http_response_code(200);
        } else {
            http_response_code(404);
        }
    }

    /**
     * @param $params
     * @param $gets
     */
    public function deleteAlbum($params, $gets)
    {
        if (count($gets) != 1) {
            http_response_code(404);
        } elseif (array_key_exists("albumId", $gets)) {
            $this->albumManager->deleteAlbum($gets["albumId"]);
            $this->data["response"] = true;
            http_response_code(200);
        } else {
            http_response_code(404);
        }
    }

    /**
     * @param $params
     * @param $gets
     */
    public function reorderAlbum($params, $gets)
    {
        if (count($gets) != 1) {
            http_response_code(404);
        } elseif (array_key_exists("imagesOrder", $gets)) {
            $imagesOrder = array();
            for ($i = 0; $i < sizeof($gets["imagesOrder"]); $i += 2) {
                $imagesOrder[($i / 2)] = [$gets["imagesOrder"][$i], $gets["imagesOrder"][($i + 1)]];
            }
            $this->data["response"] = $this->albumManager->reorderAlbum($imagesOrder);;
            http_response_code(200);
        } else {
            http_response_code(404);
        }
    }
}
