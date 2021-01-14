<?php

namespace app\forms;

require("../vendor/autoload.php");

use Exception;
use Nette\Forms\Form;

/**
 * Form FullSignInForm
 *
 * @package app\forms
 */
final class UploadAlbumImagesForm extends FormFactory
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
        $this->form = parent::getBootstrapForm("UploadAlbumImagesForm");
    }

    /**
     * @param callable $onSuccess
     *
     * @return Form
     */
    public function create(callable $onSuccess): Form
    {
        $this->form->addMultiUpload('albumImages', 'Upload album Images')
            ->addRule($this->form::MAX_LENGTH, 'You can upload max %d images.', 22)
            ->addRule($this->form::IMAGE, "You can upload only images");
        $this->form->addSubmit("submit", "Upload");

        //var_dump($this->form);
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
