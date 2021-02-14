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
final class LandingPageForm extends FormFactory
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
        $this->form = parent::getBootstrapForm("LandingPageForm");
    }

    /**
     * @param callable $onSuccess
     *
     * @return Form
     */
    public function create(callable $onSuccess): Form
    {
        $this->form->addUpload('filename')
            ->addRule($this->form::IMAGE, "You can upload only images")
            ->addRule($this->form::MAX_FILE_SIZE, "You can upload only maximum of 40MB", 40000000);
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
