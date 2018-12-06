<?php namespace Pixel\Shop\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

class Categories extends Controller
{
    public $implement = [
    	'Backend\Behaviors\ListController',
    	'Backend\Behaviors\FormController',
    	'Backend\Behaviors\ReorderController',
    	'Pixel\Shop\Behaviors\ModalContext'
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public $requiredPermissions = [
        'pixel.shop.categories' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Pixel.Shop', 'shop', 'categories');
    }

    public function checkRecords(){
    	return $this->reorderGetModel()->count();
    }
}