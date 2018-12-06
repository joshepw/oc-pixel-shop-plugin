<?php namespace Pixel\Shop\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Items extends Controller
{
    public $implement = [
    	'Backend\Behaviors\ListController',
    	'Backend\Behaviors\FormController'
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $bodyClass = 'compact-container';

    public $requiredPermissions = [
        'pixel.shop.items' 
    ];

    public function __construct(){
        parent::__construct();
        BackendMenu::setContext('Pixel.Shop', 'shop', 'items');

        $this->addJs('/plugins/pixel/shop/assets/js/items.js');
    }
}