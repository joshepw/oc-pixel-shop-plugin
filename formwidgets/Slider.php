<?php namespace Pixel\Shop\FormWidgets;

use Lang;
use Config;
use Exception;
use Backend\Classes\FormField;
use Backend\Classes\FormWidgetBase;

class Slider extends FormWidgetBase{

	// PROPERTIES
	protected $defaultAlias = 'slider';

	public $start = 0;
	public $end = 100;
	public $step = 1;
	public $options;
	public $format;

	// ON INIT
	public function init(){
		parent::init();

		$this->fillFromConfig([
			'start',
			'end',
			'step',
			'options',
			'format'
		]);
	}

	// PREPARE VARIABLES
	public function prepareVars(){
		$this->vars['format'] = ($format = $this->format) ? $format : "value";
	}

	// RENDER
	public function render(){
		$this->prepareVars();
		return $this->makePartial('slider');
	}

	// ASSETS
	public function loadAssets(){
		$this->addJs('bootstrap-slider.min.js');
		$this->addCss('bootstrap-slider.min.css');
	}
}