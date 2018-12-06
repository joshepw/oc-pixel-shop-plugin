<?php namespace Pixel\Shop\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

class Coupons extends Controller
{
    public $implement = [
    	'Backend\Behaviors\ListController',
    	'Backend\Behaviors\FormController',
    	'Pixel\Shop\Behaviors\ModalContext'
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'pixel.shop.coupons' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Pixel.Shop', 'shop', 'coupons');
    }
}