<?php namespace Pixel\Shop\Controllers;

// LIBRERIAS
use BackendMenu;
use Backend\Classes\Controller;

// CONTROLADOR
class Index extends Controller{

	// PROPRIEDADES
	public $implement = [];
	public $pageTitle = "pixel.shop::lang.menu.shop";
	
	public $requiredPermissions = [
		'pixel.shop.items',
		'pixel.shop.categories',
		'pixel.shop.brands',
        'pixel.shop.orders',
        'pixel.shop.coupons'
	];

	public function __construct()
	{
		parent::__construct();
		BackendMenu::setContext('Pixel.Shop', 'shop');
	}

	// METODOS
	public function index(){
		return '<div class="layout-cell layout-container" id="layout-body"> <div class="layout-relative"> <div class="layout"> <div class="layout-row"> <div class="layout"> <div class="layout-cell oc-logo-transparent"></div> </div> </div> </div> </div> </div>';
	}
}