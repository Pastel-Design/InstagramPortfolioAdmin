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
final class ProfileInfoForm extends FormFactory
{
    private string $defaultTitle;
    private string $defaultDescription;
    private string $defaultKeywords;
    private string $bioTitle;
    private string $bioDescription;
    private string $instagramLink;
    private string $twitterLink;
    private string $email;
    /**
     * @var Form $form
     */
    private Form $form;

    public function __construct($profileInfo)
    {
        $this->defaultTitle=$profileInfo["default_title"];
        $this->defaultDescription=$profileInfo["default_description"];
        $this->defaultKeywords=$profileInfo["default_keywords"];
        $this->bioTitle=$profileInfo["bio_title"];
        $this->bioDescription=$profileInfo["bio_description"];
        $this->instagramLink=$profileInfo["instagram_link"];
        $this->twitterLink=$profileInfo["twitter_link"];
        $this->email=$profileInfo["email"];
        $this->form = parent::getBootstrapForm("ProfileInfoForm");
    }

    /**
     * @param callable $onSuccess
     *
     * @return Form
     */
    public function create(callable $onSuccess): Form
    {
        $this->form->addText('defaultTitle', 'Default page title:')
            ->setDefaultValue($this->defaultTitle)
            ->setRequired(true);
        $this->form->addTextArea('defaultDescription', 'Default page description (meta data for SEO):')
            ->setDefaultValue($this->defaultDescription)
            ->setHtmlAttribute("class", "form-control")
            ->setRequired(true);
        $this->form->addText('defaultKeywords', 'Default page keywords (meta data for SEO):')
            ->setDefaultValue($this->defaultKeywords)
            ->setRequired(true);
        $this->form->addText('bioTitle', 'Profile bio title:')
            ->setDefaultValue($this->bioTitle)
            ->setRequired(true);
        $this->form->addTextArea('bioDescription', 'Profile bio description:')
            ->setDefaultValue($this->bioDescription)
            ->setHtmlAttribute("class", "form-control")
            ->setRequired(true);
        $this->form->addText('instagramLink', 'Instagram link:')
            ->setDefaultValue($this->instagramLink)
            ->setRequired(true);
        $this->form->addText('twitterLink', 'Twitter link:')
            ->setDefaultValue($this->twitterLink)
            ->setRequired(true);
        $this->form->addEmail("email", "Email for login and contact:")
            ->setDefaultValue($this->email)
            ->setRequired(true);
        $this->form->addSubmit("submit", "Edit profile info");

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
