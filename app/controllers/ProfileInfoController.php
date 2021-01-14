<?php

namespace app\controllers;

use app\forms\ProfileInfoForm;
use app\forms\UpdateLoginForm;
use app\models\ProfileInfoManager;

/**
 * Controller AlbumController
 *
 * @package app\controllers
 */
class ProfileInfoController extends Controller
{

    private ProfileInfoManager $profileInfoManager;

    public function __construct()
    {
        $this->profileInfoManager = new ProfileInfoManager();
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
        $profileInfo = $this->profileInfoManager->getProfileInfo();
        $profileInfoForm = new ProfileInfoForm($profileInfo);
        $this->data["profileInfoForm"] = $profileInfoForm->create(function ($values) {
            $this->profileInfoManager->updateProfileInfo($values);
        });
        $updateLoginForm = new UpdateLoginForm();
        $this->data["updateLoginForm"] = $updateLoginForm->create(function ($values) {
            $this->profileInfoManager->updateLogin($values);
        });
        $this->head['page_title'] = "";
        $this->head['page_keywords'] = "";
        $this->head['page_description'] = "";
        $this->setView('default');
    }
}
