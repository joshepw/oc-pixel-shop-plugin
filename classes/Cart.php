<?php 

namespace Pixel\Shop\Classes;

use Auth;
use Session;
use CurrencyShop;
use Pixel\Shop\Models\Item;
use Pixel\Shop\Models\Order;
use RainLab\Location\Models\State;
use RainLab\Location\Models\Country;

class Cart{
	
	public $user;
	public $order;
	public $items;

	public $shipping_address;
    public $billing_address;
    public $custom_fields;

	public $carrier;
	public $coupon;
	public $gateway;
	public $currency;

	public $customer_first_name;
	public $customer_last_name;
	public $customer_email;
	public $customer_phone;

	public $notes;

	public $subtotal;
	public $shipping_total;
	public $tax_total;
	public $total;

	function __construct(){
		$this->items = array();
		$this->shipping_address = array();
		$this->billing_address = array();

		$this->currency = CurrencyShop::default();

		$this->subtotal = 0.00;
		$this->shipping_total = 0.00;
		$this->tax_total = 0.00;
		$this->total = 0.00;
	}

	public static function load($order_id = null){
		if($order_id && $order = Order::find($order_id))
			return self::getCartFromOrder($order);

		return Session::get('cart', new Cart());
	}

	public static function clear(){
		Session::forget('cart');
		return new Cart();
	}

	public function addItem($item, $description, $price, $quantity, $index = null){
		$tax = 0.00;
		$total = floatval($price) * intval($quantity);

		if($item->tax && count($item->tax) > 0){
			foreach ($item->tax as $inTax) {
				if($inTax > 0)
					$tax += ($inTax / 100) * $total;
			}
		}

		if($item->additional_shipping_fees)
			$this->shipping_total += floatval($item->additional_shipping_fees);

		$data = [
			"id" => $item->id,
			"index" => $index,
			"title" => $item->name,
			"description" => $description,
			"tax" => $tax,
			"price" => floatval($price),
			"quantity" => intval($quantity),
			"total" => $total
		];

		if($item->image)
			$data['thumb'] = $item->image->getThumb(42, 42, 'crop');

		$this->items[] = $data;

		return $data;
	}

	public function deleteItem($index){
		if(array_key_exists($index, $this->items))
			unset($this->items[$index]);
	}

	public function updateTotals(){
		$shipping_total = 0.00;
		$tax_total = 0.00;
		$total = 0.00;
        $state = null;
        $country = null;

		if(array_key_exists('state', $this->shipping_address))
            $state = State::where('code', $this->shipping_address['state'])->first();
            
        if(array_key_exists('country', $this->shipping_address))
			$country = Country::where('code', $this->shipping_address['country'])->first();

		foreach ($this->items as $item) {
			$id = Item::find($item['id']);

			if($id && $id->additional_shipping_fees > 0)
				$shipping_total += floatval($id->additional_shipping_fees);

			if($item['tax'] > 0)
				$tax_total += floatval($item['tax']);

			if($item['total'] > 0)
				$total += floatval($item['total']);
		}
		/** 
		 * METODO QUE CALCULA EL ENVIO DEPENDIENDO EL ESTADO
		*/
		//dd($country);
		
		if($state && floatval($state->shipping_fee) > 0.00){
			$shipping_total += floatval($state->shipping_fee);
		}
		elseif($country && floatval($country->shipping_fee) > 0.00)
		{
			$shipping_total += floatval($country->shipping_fee);
		}		





		if(!empty($this->coupon) && array_key_exists('amount', $this->coupon))
			$total -= floatval($this->coupon['amount']);


		$this->subtotal = $total;

		$this->shipping_total = $shipping_total;
		$this->tax_total = $tax_total;
		$this->total = $shipping_total + $tax_total + $total;
	}

	public function save(){
		return Session::put('cart', $this);
	}

	public function createOrderFromCart(){
		$order = Order::find($this->order) ?? new Order();
		$order->shipping_address = $this->shipping_address;
        $order->billing_address = $this->billing_address;
        $order->custom_fields = $this->custom_fields;
        
        $order->is_paid = $order->is_paid ?? false;
        $order->is_confirmed = $order->is_confirmed ?? false;
        $order->is_fulfill = $order->is_fulfill ?? false;

		if(!empty($this->user))
			$order->user_id = $this->user;

		if(!empty($this->coupon) && array_key_exists('id', $this->coupon))
			$order->coupon_id = $this->coupon['id'];

		$order->customer_first_name = $this->customer_first_name;
		$order->customer_last_name = $this->customer_last_name;
		$order->customer_email = $this->customer_email;
		$order->customer_phone = $this->customer_phone;

		$order->items = $this->items;
		$order->gateway = $this->gateway;
		$order->currency = $this->currency;

		$order->shipping_total = $this->shipping_total;
		$order->tax_total = $this->tax_total;
		$order->total = $this->total;

		return $order;
	}

	public static function getCartFromOrder($order){
		$cart = new Cart();

		if($order->is_paid)
			return $cart;

		$cart->order = $order->id;
		$cart->shipping_address = $order->shipping_address;
		$cart->billing_address = $order->billing_address;

		if(!empty($order->user_id))
			$cart->user_id = $order->user_id;

		$cart->customer_first_name = $order->customer_first_name;
		$cart->customer_last_name = $order->customer_last_name;
		$cart->customer_email = $order->customer_email;
		$cart->customer_phone = $order->customer_phone;

		$cart->items = $order->items;
		$cart->gateway = $order->gateway;
		$cart->currency = $order->currency;

		if(!empty($order->coupon_id) && $order->coupon){
			$cart->updateTotals();

			$cart->coupon = [
				'id' => $order->coupon->id,
				'code' => $order->coupon->code,
				'discount' => $order->coupon->getValueLabel(),
				'amount' => $order->coupon->getFinalDiscount($cart->subtotal)
			];
		}

		$cart->shipping_total = $order->shipping_total;
		$cart->tax_total = $order->tax_total;
		$cart->total = $order->total;

		$cart->save();

		return $cart;
	}

	public function __toString(){
		return json_encode($this, JSON_PRETTY_PRINT);
	}
}

 ?>