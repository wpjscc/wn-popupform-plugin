<?php

namespace Wpjscc\PopupForm\Behaviors;

use Backend;
use Backend\Classes\ControllerBehavior;

class PopupFormController extends ControllerBehavior
{


    /**
     * Behavior constructor
     * @param \Backend\Classes\Controller $controller
     */
    public function __construct($controller)
    {
        parent::__construct($controller);

        if (!$this->controller->isClassExtendedWith(\Backend\Behaviors\FormController::class)) {
            throw new \ApplicationException('You must use the \Backend\Behaviors\FormController behavior class in order to use the PopupFormController behavior class.');
        }
    }

    public function onCreateForm_($context = null)
    {
        $this->controller->create($context ?: post('context'));
        return $this->makePartial('create_form');
    }

    public function onUpdateForm_($recordId = null, $context = null)
    {
        $this->controller->update($recordId, $context ?: post('context'));
        return $this->makePartial('update_form');
    }
}
