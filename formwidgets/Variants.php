<?php namespace Pixel\Shop\FormWidgets;

use Lang;
use Config;
use Exception;
use Backend\Classes\FormField;
use Backend\Classes\FormWidgetBase;

class Variants extends FormWidgetBase{

	public $variants = array();
	public $quantity;
	public $min;
	public $max;

	// PROPERTIES
	protected $defaultAlias = 'variants';

	// ON INIT
	public function init(){
		parent::init();

		if(!empty($this->getLoadValue()))
			$this->variants = $this->getLoadValue();
		else
			$this->variants = array([
				"title" => null,
				"multiple" => false,
				"items" => [[]]
			]);
	}

	// PREPARE VARIABLES
	public function prepareVars(){
		$this->vars['variants'] = $this->variants;
	}

	// RENDER
	public function render(){
		$this->prepareVars();
        $this->vars['name'] = $this->getFieldName();
        $this->vars['id'] = $this->getId();
		return $this->makePartial('variants');
	}

	// ASSETS
	public function loadAssets(){
		$this->addJs('table.drag.js');
		$this->addJs('mask.jquery.js');
		$this->addCss('variants.css');
	}

	public function getSaveValue($value){
		if (!$input = input($this->getFieldName()))
			return null;

		$input = urldecode($input);
		$input = json_decode($input, true);

		return $input;
	}

    // REQUEST
	public function onAddVariant(){
		$empty = [
			'variant' => [
				'items' => [
					array()
				]
			]
		];

        $this->vars['name'] = $this->getFieldName();

		return [
			'success' => true,
			'render' => $this->makePartial('variant-partial', $empty)
		];
	}

	public function onAddOption(){
        $this->vars['name'] = $this->getFieldName();
		return [
			'success' => true,
			'render' => $this->makePartial('option-partial')
		];
	}
}
