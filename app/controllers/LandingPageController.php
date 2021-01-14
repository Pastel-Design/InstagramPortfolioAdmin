<?php

namespace app\controllers;

use app\forms\LandingPageForm;
use app\models\ProfileInfoManager;
use app\models\UploadManager;
use app\router\Router;
use Nette\Http\FileUpload;

/**
 * Controller AlbumController
 *
 * @package app\controllers
 */
class LandingPageController extends Controller
{
    private ProfileInfoManager $profileInfoManager;

    public function __construct()
    {
        parent::__construct();
        $this->profileInfoManager = new ProfileInfoManager();
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
        $landingPageForm = new LandingPageForm;
        $this->data["landingPageForm"] = $landingPageForm->create(function ($values) {
            if ($filename = UploadManager::UploadSingle($values["filename"])) {
                $this->profileInfoManager->updateLandingPage($filename);
            }
            Router::reroute("landing-page");
        });
        $this->data["landingPageImage"] = $this->profileInfoManager->getLandingPageImage();
        $this->head['page_title'] = "";
        $this->head['page_keywords'] = "";
        $this->head['page_description'] = "";
        $this->setView('default');
    }
}
