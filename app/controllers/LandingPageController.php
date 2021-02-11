<?php

namespace app\controllers;

use app\forms\LandingPageForm;
use app\forms\LandingPageFormMobile;
use app\forms\ProfilePictureForm;
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
        $landingPageFormMobile = new LandingPageFormMobile;
        $this->data["landingPageForm"] = $landingPageForm->create(function ($values) {
            if ($filename = UploadManager::UploadSingle($values["filename"])) {
                $this->profileInfoManager->updateLandingPage($filename);
            }
            Router::reroute("landing-page");
        });
        $this->data["landingPageFormMobile"] = $landingPageFormMobile->create(function ($values) {
            if ($filename = UploadManager::UploadSingle($values["filename"])) {
                $this->profileInfoManager->updateLandingPageMobile($filename);
            }
            Router::reroute("landing-page");
        });
        $this->data["landingPageImage"] = $this->profileInfoManager->getLandingPageImage();

        $profilePictureForm = new ProfilePictureForm;
        $this->data["profilePictureForm"] = $profilePictureForm->create(function ($values) {
            if ($filename = UploadManager::UploadSingle($values["filename"])) {
                $this->profileInfoManager->updateProfilePicture($filename);
            }
            Router::reroute("landing-page");
        });
        $this->data["profilePicture"] = $this->profileInfoManager->getProfilePicture();
        $this->head['page_title'] = "";
        $this->head['page_keywords'] = "";
        $this->head['page_description'] = "";
        $this->setView('default');
    }
}
