<?php namespace Pixel\Shop\Behaviors;

use Lang;
use Flash;
use Str;

use Backend\Classes\ControllerBehavior;
use Pixel\Shop\Models\Shipping;

class ModalContext extends ControllerBehavior {

	protected $controller;
	protected $requiredConfig = ['modelClass', 'form'];
    protected $formWidget;


    public function __construct($controller){
		parent::__construct($controller);
        $this->config = $this->makeConfig($controller->formConfig, $this->requiredConfig);
		$this->config->modelClass = Str::normalizeClassName($this->config->modelClass);

	}
	

    public function onCreateForm(){
		$this->controller->asExtension('FormController')->create(post('record_id'));
		$this->controller->vars['recordId'] = post('record_id');
		$this->controller->vars['layout'] = $layout = post('layout');
		$this->controller->vars['sidebar'] = $layout = post('sidebar');
		return $this->controller->makePartial('$/pixel/shop/partials/_create.htm');		
	}
	public function onCreateFormZones(){
		$this->controller->asExtension('FormController')->create(post('record_id'));
		$this->controller->vars['recordId'] = post('record_id');
		$this->controller->vars['recordIdUpdated'] = null;

		return $this->controller->makePartial('$/pixel/shop/partials/_updateZones.htm');
		// $this->controller->vars['layout'] = $layout = post('layout');
		// $this->controller->vars['sidebar'] = $layout = post('sidebar');

	}
	public function onCreateModal(){
		$this->controller->asExtension('FormController')->create_onSave();
		$model = $this->controller->asExtension('FormController')->formCreateModelObject();
		return $this->controller->listRefresh();
	}
	public function onUpdateFormRate(){
		$this->controller->asExtension('FormController')->update(post('record_id'));
		$this->controller->vars['recordId'] = post('record_id');

		

		// $this->controller->vars['layout'] = $layout = post('layout');
		// $this->controller->vars['sidebar'] = $layout = post('sidebar');

		return $this->controller->makePartial('$/pixel/shop/partials/_updateRate.htm');
	}
	public function onUpdateForm(){
		$this->controller->asExtension('FormController')->update(post('record_id'));
		$this->controller->vars['recordId'] = post('record_id');
		$this->controller->vars['layout'] = $layout = post('layout');
		$this->controller->vars['sidebar'] = $layout = post('sidebar');
		//$this->initForm( new \Pixel\Shop\Models\Shipping,  "update");
		return $this->controller->makePartial('$/pixel/shop/partials/_update.htm');
	}
	public function onUpdateFormZones(){
		$this->controller->asExtension('FormController')->update(post('record_id'));
		$this->controller->vars['recordId'] = post('record_id');
		// $this->controller->vars['layout'] = $layout = post('layout');
		// $this->controller->vars['sidebar'] = $layout = post('sidebar');
		//$this->initForm( new \Pixel\Shop\Models\Shipping,  "update");
		return $this->controller->makePartial('$/pixel/shop/partials/_updateZones.htm');
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
	public function getWidget(){
        $dashboard_id = post('dashboard_id');
        $dashboardModel = Shipping::find(1);
        $config = $this->makeConfig();
        $config->model = new \Pixel\Shop\Models\Shipping;
        $config->arrayName = 'Dashboard';
        $widget = $this->makeWidget('Backend\Widgets\Form', $config);
        $widget->bindToController();
        return $widget;
    }
}
