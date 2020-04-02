<?php namespace Pixel\Shop\Models;

use Mail;
use Log;
use Auth;
use Model;
use Backend;
use Currency;
use CurrencyShop;
use Pixel\Shop\Models\Item;
use Pixel\Shop\Models\SalesSettings;

/**
 * Model
 */
class Order extends Model
{
    // VALIDACIONES
	use \October\Rain\Database\Traits\Validation;
    
    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $attributeNames = [
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'pixel_shop_orders';

    protected $dates = ['paid_at'];

    protected $jsonable = [
    	'response', 
    	'history', 
    	'items',
    	'billing_address',
        'shipping_address',
        'custom_fields'
    ];

    protected $casts = [
		'is_paid' => 'boolean',
		'is_confirmed' => 'boolean',
        'is_fulfill' => 'boolean'
    ];

    protected $guarded = ['id'];

    // RELACIONES
	public $belongsTo = [
		'user' => ['RainLab\User\Models\User'],
		'coupon' => ['Pixel\Shop\Models\Coupon'],
		// 'carrier' => ['Pixel\Shop\Models\Carrier'],
	];

	// CUSOM ATTRIBUTES
	public function getCustomerFullnameAttribute(){
		return $this->getCustomerFullName();
	}

	public function getStatusLabelAttribute(){
		return '<span class="btn btn-default btn-xs " style="background-color: #c0392b">' .
					'<b>'.$this->status.'</b>' . 
				'</span>';
	}

	public function getOrderID(){
		return str_pad($this->id, 8, 0, STR_PAD_LEFT);
	}

	public function getFormatedTotal(){
		return CurrencyShop::getCurrency($this->total, $this->currency);
	}

	// CUSTOMER METHODS
    public function getCustomerFullName(){
    	$names = array();

    	if($first = $this->getCustomerFirstName())
    		$names[] = $first;

    	if($last = $this->getCustomerLastName())
    		$names[] = $last;

    	return implode(' ', $names);
    }

    public function getCustomerFirstName(){
    	if($this->user != null)
    		return $this->user->name;

    	return $this->customer_first_name;
    }

    public function getCustomerLastName(){
    	if($this->user != null)
    		return $this->user->surname;

    	return $this->customer_last_name;
    }

    public function getShopName(){
    	return SalesSettings::get('shop_name');
    }

    public function getShopEmail(){
    	return SalesSettings::get('shop_email');
    }

    public function getShopAddress(){
    	return SalesSettings::get('shop_address');
	}
	
	public function getBankTransferDetails(){
    	return GatewaysSettings::get('bank_transfer_customer_information');
    }

    public function sendNotification(){
        $order = $this;
        $vars = [ 
            'order' => $order,
            'backend_url' => Backend::url('pixel/shop/orders')
        ];

		if (!$order->is_paid && !$order->is_confirmed && $order->status == "await_pay"){
			Mail::send('pixel.shop::mail.order_awaitpay', $vars, function($message) use ($order) {
				$message->to($order->customer_email, $order->customer_fullname);
				$message->subject(trans('pixel.shop::mail.receipt'));
            });
            
            if(SalesSettings::get('new_order_notify')){
                Mail::send('pixel.shop::mail.new_order', $vars, function($message){
                    $message->to(SalesSettings::get('new_order_notify'));
                });
            }
		}
		else if ($order->is_paid && $order->is_confirmed && !$order->is_fulfill && $order->status == "await_fulfill" ){
			Mail::send('pixel.shop::mail.order_payed', $vars, function($message) use ($order) {
				$message->to($order->customer_email, $order->customer_fullname);
				$message->subject(trans('pixel.shop::mail.payed'));
			});

            if(SalesSettings::get('new_order_notify')){
                Mail::send('pixel.shop::mail.new_order', $vars, function($message){
                    $message->to(SalesSettings::get('new_order_notify'));
                });
            }
		}
		else if ($order->is_paid && $order->is_confirmed && $order->is_fulfill && $order->status == "await_fulfill" ){
			Mail::send('pixel.shop::mail.order_completed', $vars, function($message) use ($order) {
				$message->to($order->customer_email, $order->customer_fullname);
				$message->subject(trans('pixel.shop::mail.shipped'));
			});
		}
		else if ($order->status == "cancelled"){
			Mail::send('pixel.shop::mail.order_cancelled', $vars, function($message) use ($order) {
				$message->to($order->customer_email, $order->customer_fullname);
				$message->subject(trans('pixel.shop::mail.cancelled'));
			});
		}
		else {
			Log::info('Notification Failure: unable to get order status: ' . json_encode([
                'OrderID' => $order->id,
                'is_paid' => $order->is_paid,
                'is_confirmed' => $order->is_confirmed,
                'is_fulfill' => $order->is_fulfill,
                'status' => $order->status,
                'gateway' => $order->gateway,
            ]));
		}	
        
    }

    public function reduceInventory($increment = false){
    	if(!$this->is_paid)
    		return;

    	foreach ($this->items as $indexItem => $item) {
			$product = Item::find($item['id']);

    		if(!$product)
				continue;

    		if(!array_key_exists('index', $item))
				continue;

    		$reducer = $item['quantity'];
			$product->increment('sales_count', $reducer);

			if($product->is_with_variants) {
				$options = $this->parseOptions($item['index']);

				if(empty($options))
					continue;

				$variants = $product->variants;

				foreach ($variants as $indexVariant => $variant) {
					if(array_key_exists($variant['id'], $options) && !empty($options[$variant['id']])){
						$i = $this->getVariantIndex($variant, $options[$variant['id']]);

						if($i === null)
							continue;

						$rawQty = $variants[$indexVariant]['items'][$i]['quantity'];

						if($rawQty == "" && $rawQty === null) {
							continue;
						}

						$quantity = intval($rawQty);

						if($increment)
							$quantity += intval($reducer);
						else
							$quantity -= intval($reducer);

						$variants[$indexVariant]['items'][$i]['quantity'] = $quantity;
					}
				}

				$product->variants = $variants;
			} else {
				if($product->quantity == "" && $product->quantity === null) {
					continue;
				}

				$quantity = intval($product->quantity);

				if($increment)
					$quantity += intval($reducer);
				else
					$quantity -= intval($reducer);

				$product->quantity = $quantity;
			}
			
    		$product->save();
    	}
    }

    private function getVariantIndex($variant, $ref){
    	if(array_key_exists('items', $variant)){
    		foreach ($variant['items'] as $key => $item) {
    			if(array_key_exists('ref', $item) && $ref == $item['ref'])
    				return $key;
    		}
    	}

    	return null;
    }

    private function parseOptions($option_index){
    	$option_index = explode(',', $option_index);
    	$options = [];

    	if(empty($option_index))
    		return null;

    	foreach ($option_index as $el) {
    		if(empty($el))
    			continue;

    		$el = explode('::', $el);
    		$options[$el[0]] = $el[1];
    	}

    	return $options;
    }
}
