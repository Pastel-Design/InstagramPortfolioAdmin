<?php

namespace app\forms;

require("../vendor/autoload.php");

use app\exceptions\SignException;
use app\models\SignManager;
use Exception;
use Nette\Forms\Form;

/**
 * Form FullSignIn
 *
 * @package app\forms
 */
final class UploadAlbumImages extends FormFactory
{

    /**
     * @var Form $form
     */
    private Form $form;

    /**
     * FullSignUp constructor.
     */
    public function __construct()
    {
        $this->form = parent::getBootstrapForm("UploadAlbumImages");
    }

    /**
     * @param callable $onSuccess
     *
     * @return Form
     */
    public function create(callable $onSuccess): Form
    {
        $this->form->addMultiUpload('albumImages', 'Album Images')
            ->addRule($this->form::MAX_LENGTH, 'You can upload max %d images.', 50)
            ->addRule($this->form::MAX_FILE_SIZE, "Files can have max size of 10MB", 10000000)
            ->addRule($this->form::IMAGE,"You can upload only images");
        $this->form->addUpload('albumImage', 'Album Image')
            ->addRule($this->form::MAX_FILE_SIZE, "Files can have max size of 10MB", 10000000)
            ->addRule($this->form::IMAGE,"You can upload only images");
        $this->form->addSubmit("submit", "Upload");

        if ($this->form->isSuccess()) {
            $values = $this->form->getValues("array");
            try {
                $onSuccess($values);
            } catch (Exception $exception) {
                $this->form->addError($exception->getMessage());
            }
        }

        return $this->form;
    }
}
