<?php

namespace app\controllers;

use app\exceptions\UserException;
use app\models\AlbumManager;
use app\models\CartManager;
use app\models\ProductManager;
use app\models\UserManager;
use app\router\Router;
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
                $this->data["response"] = $this->albumManager->editImage($data, $imageId);
                http_response_code(200);
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
        }
    }
}
