<?php namespace Pixel\Shop\FormWidgets;

use Lang;
use Flash;
use Config;
use BackendAuth;
use Backend\Classes\FormField;
use Backend\Classes\FormWidgetBase;
use Backend\FormWidgets\Relation as RelationWidget;

class Selector extends RelationWidget{
	use \Backend\Traits\FormModelSaver;

	// PROPERTIES
	protected $defaultAlias = 'selector';

	public $form;
	public $user;
	public $modelClass;
	public $permission;
	public $formWidget;
	public $formTitle;

	// ON INIT
	public function init(){
		parent::init();

		$this->fillFromConfig([
			'modelClass',
			'form',
			'permission',
			'formTitle'
		]);

		$this->formWidget = $this->initForm();
	}

	// PREPARE VARIABLES
	public function prepareVars(){
		parent::prepareVars();

		$this->vars['fieldValue'] = $this->getLoadValue();
		$this->vars['formWidget'] = $this->formWidget;
		
		$this->user = BackendAuth::getUser();
	}

	// INIT MODAL FORM
	public function initForm(){
		$config = $this->makeConfig($this->form);
		$config->model = new $this->modelClass;

		$widget = $this->makeWidget('Backend\Widgets\Form', $config);
		$widget->bindToController();

		return $this->formWidget = $widget;
	}

	// CREATE MODAL RECORD
	public function onCreateFieldModal(){
		$data = post();
		$class = post('class');
		$model = new $class;
		$model->fill($data);

		if($model->validate()){
			$model->save();
			Flash::success(Lang::get('backend::lang.form.create_success', ['name' => trans(post('model_title'))]));
			$field = clone $this->makeRenderFormField();
			return ['#'.$this->getId() => $this->makePartial('~/modules/backend/widgets/form/partials/_field_'.$field->type.'.htm', ['field' => $field])];
		}           
	}

	// CREATE MODAL POPUP
	public function onCreateModal(){
		return $this->makePartial('create', [
			'widget' => $this->formWidget, 
			'fieldName' => $this->formField->label, 
			'class' => $this->modelClass,
			'formTitle' => $this->formTitle
		]);
	}
}