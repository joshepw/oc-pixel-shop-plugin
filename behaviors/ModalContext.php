<?php namespace Pixel\Shop\Behaviors;

use Lang;
use Flash;
use Backend\Classes\ControllerBehavior;

class ModalContext extends ControllerBehavior {

    protected $controller;

    public function __construct($controller){
        parent::__construct($controller);

        $this->controller = $controller;

        $this->setConfig($controller->listConfig, ['modelClass']);
    }

    public function onCreateForm(){
		$this->controller->asExtension('FormController')->create(post('record_id'));
		$this->controller->vars['recordId'] = post('record_id');
		$this->controller->vars['layout'] = $layout = post('layout');
		$this->controller->vars['sidebar'] = $layout = post('sidebar');

		return $this->controller->makePartial('$/pixel/shop/partials/_create.htm');
	}

	public function onCreateModal(){
		$this->controller->asExtension('FormController')->create_onSave();
		$model = $this->controller->asExtension('FormController')->formCreateModelObject();
		return $this->controller->listRefresh();
	}

	public function onUpdateForm(){
		$this->controller->asExtension('FormController')->update(post('record_id'));
		$this->controller->vars['recordId'] = post('record_id');
		$this->controller->vars['layout'] = $layout = post('layout');
		$this->controller->vars['sidebar'] = $layout = post('sidebar');

		return $this->controller->makePartial('$/pixel/shop/partials/_update.htm');
	}

	public function onUpdateModal(){
		$this->controller->asExtension('FormController')->update_onSave(post('record_id'));
		return $this->controller->listRefresh();
	}

	public function onPreviewForm(){
		$this->controller->asExtension('FormController')->preview(post('record_id'));
		$this->controller->vars['recordId'] = post('record_id');
		$this->controller->vars['layout'] = $layout = post('layout');
		$this->controller->vars['sidebar'] = $layout = post('sidebar');

		return $this->controller->makePartial('$/pixel/shop/partials/_preview.htm');
	}

	public function onDeleteModal(){
		$this->controller->asExtension('FormController')->update_onDelete(post('record_id'));
		return $this->controller->listRefresh();
	}
}
