<?php namespace Pixel\Shop\Controllers;

use Flash;
use Redirect;
use BackendMenu;
use Carbon\Carbon;
use Pixel\Shop\Models\Order;
use Backend\Classes\Controller;

class Orders extends Controller
{
    public $implement = [
    	'Backend\Behaviors\ListController',
    	'Backend\Behaviors\FormController',
    	'Pixel\Shop\Behaviors\ModalContext'
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'pixel.shop.orders' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Pixel.Shop', 'shop', 'orders');
    }

    public function onChangeStatus(){
    	if(!$order = Order::find(input('order_id')))
    		Flash::error(trans('pixel.shop::lang.messages.order_not_exist'));

    	if(input('status') == 'await_fulfill'){
    		$order->status = 'await_fulfill';
    		$order->is_paid = true;
    		$order->is_confirmed = true;
    		$order->paid_at = Carbon::now();

    		$order->reduceInventory();
    		$order->sendNotification();
    	}

    	if(input('status') == 'completed'){
    		$order->is_fulfill = true;
    		$order->sendNotification();
    	}

    	if(input('status') == 'cancelled'){
			$order->status = input('status');
    		$order->reduceInventory(true);
    		$order->sendNotification();
    	}

    	$order->status = input('status');
    	$order->save();

    	Flash::success(trans('pixel.shop::lang.messages.order_status_updated'));

    	return Redirect::refresh();
    }
}
