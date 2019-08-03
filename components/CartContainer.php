<?php namespace Pixel\Shop\Components;

use Auth;
use Flash;
use Request;
use Redirect;
use Validator;
use Carbon\Carbon;
use Cms\Classes\Page;
use Pixel\Shop\Classes\Cart;
use Pixel\Shop\Models\Order;
use Cms\Classes\ComponentBase;
use RainLab\Location\Models\State;
use RainLab\Location\Models\Country;
use Pixel\Shop\Components\CartTrait;
use Pixel\Shop\Components\PaymentTrait;
use Pixel\Shop\Models\GatewaysSettings;

class CartContainer extends ComponentBase{

	use CartTrait;
	use PaymentTrait;

	public function componentDetails(){
		return [
			'name'        => 'Cart',
			'description' => 'All operation from add to basket to finish order'
		];
	}

	public function defineProperties(){
		return [
			'productPage' => [
				'title'       => 'Product page',
				'description' => 'Product detail page',
				'type'        => 'dropdown',
				'default'     => 'product',
			],
			'returnPage' => [
				'title'       => 'Return Payment Page',
				'type'        => 'dropdown',
				'default'     => 'return',
			],
		];
	}

	public function getProductPageOptions(){
		return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
	}

	public function getReturnPageOptions(){
		return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
	}

	public function onRun(){
		$billing_country = null;

		if(input('cart_id'))
			Cart::load(input('cart_id'));

		if(input('order_id')){
			if($order = Order::find(input('order_id'))){
				if(input('cancel')){
					$order->status = 'cancelled';
					$order->save();
				}

				if(input('paymentHash')){
					$hash = [
						$order->id,
						GatewaysSettings::get('pixelpay_app'),
						GatewaysSettings::get('pixelpay_hash')
					];

					$hash = implode('|', $hash);
					$hash = md5($hash);

					if($hash == input('paymentHash')){
						$order->status = 'await_fulfill';
						$order->is_paid = true;
						$order->paid_at = Carbon::now();
						$order->save();

						$order->reduceInventory();
						$order->sendNotification();
						Cart::clear();
					}else{
						$this->page['validationFailed'] = true;
					}
				}
				
				$this->page['order'] = $order;
			}
		}

		$this->page['cart'] = $cart = Cart::load();
		$this->page['user'] = $user = $this->user();

		$this->page['product_page'] = $this->property('productPage');
		$this->page['return_page'] = $this->property('returnPage');
		$this->page['cancel_page'] = $this->property('cancelPage');

		$this->page['countries'] = Country::isEnabled()->orderBy('is_pinned', 'desc')->get();
		$this->page['settings'] = GatewaysSettings::instance();

		if(isset($user->billing_address['state'])){
			if($state = State::where('code', $user->billing_address['state'])->first())
				$this->page['billing_states'] = $state->country->states;
		}

		if(isset($user->shipping_address['state'])){
			if($state = State::where('code', $user->shipping_address['state'])->first())
				$this->page['shipping_states'] = $state->country->states;
		}
        
        if(isset($cart->shipping_address['state'])){
            $thisCountry = Country::isEnabled()->where('code', $cart->shipping_address['country'])->first();
            
            if($state = State::where([['code', $cart->shipping_address['state']], ['country_id', $thisCountry->id]])->first()) 
                $this->page['shipping_states'] = $state->country->states;
        }


		if($user){
			if($user->is_ship_same_bill || $user->is_ship_same_bill == null){
				if(isset($user->shipping_address['country']) && !empty($user->shipping_address['country']))
					$billing_country = $user->shipping_address['country'];
			}else{
				if(isset($user->billing_address['country']) && !empty($user->billing_address['country']))
					$billing_country = $user->billing_address['country'];
			}
		}else{
			if(isset($cart->billing_address['country']) && !empty($cart->billing_address['country'])){
				$billing_country = $cart->billing_address['country'];
			}else{
				if(isset($cart->shipping_address['country']) && !empty($cart->shipping_address['country']))
					$billing_country = $cart->shipping_address['country'];
			}
		}

		$this->page['methods_list'] = $this->getPaymentMethodsList($billing_country);

		$this->addCss('/plugins/pixel/shop/assets/css/cart.css');
        $this->addJs('/plugins/pixel/shop/assets/js/jquery.mask.min.js');
        $this->addJs('/plugins/pixel/shop/assets/js/jquery.steps.min.js');
		$this->addJs('/plugins/pixel/shop/assets/js/cart.js');
	}

	public function user(){
		if (!$user = Auth::getUser())
			return null;

		return $user;
	}

	public function onShippingCountrySelect(){
		if($country = Country::where('code', input('shipping_address.country'))->first()){
			$return = ['[name="shipping_address[state]"]' => $this->renderPartial('@shipping_states', [
				'shipping_states' => $country->states
			]), 'code' => $country->code];

			if(input('is_ship_same_bill'))
				$return['.shop__methods-list'] = $this->renderPartial('@methods', [
					'methods_list' => $this->getPaymentMethodsList(input('shipping_address.country'))
				]);

			return $return;
		}
	}

	public function onBillingCountrySelect(){
		if($country = Country::where('code', input('billing_address.country'))->first()){
			$return = ['[name="billing_address[state]"]' => $this->renderPartial('@billing_states', [
				'billing_states' => $country->states
			]), 'code' => $country->code];

			$return['.shop__methods-list'] = $this->renderPartial('@methods', [
				'methods_list' => $this->getPaymentMethodsList(input('billing_address.country'))
			]);

			return $return;
		}
	}

	public function onShippingStateSelect(){
		$cart = Cart::load();
		$cart->shipping_address['state'] = input('shipping_address.state');
		$cart->shipping_address['country'] = input('shipping_address.country');
		$cart->updateTotals();
		$cart->save();

		return [ '#shop__cart-partial' => $this->renderPartial('@cart', [ 'cart' => $cart ]) ];
	}
}
