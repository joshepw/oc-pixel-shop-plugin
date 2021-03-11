<?php namespace Pixel\Shop\Components;

use Auth;
use Flash;
use Request;
use Event;
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
use Pixel\Shop\Models\SalesSettings;

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
            ]
		];
    }
    
    public function getCustomFieldsSettings(){
        $fields = [
            'customer' => $this->prepareFields('customer'),
            'billing' => $this->prepareFields('billing'),
            'shipping' => $this->prepareFields('shipping')
        ];

        return $fields;
    }

    protected function prepareLang(){
        $lang = \Config::get('app.locale', 'en');

        if(\System\Models\PluginVersion::where('code', 'RainLab.Translate')->where('is_disabled', 0)->first()){
            $translator = \RainLab\Translate\Classes\Translator::instance();
            $activeLocale = $translator->getLocale();
            $lang = $activeLocale;
        }

        if(!empty(post('lang')))
            $lang = post('lang');

        \App::setLocale($lang);
    }

    public function prepareFields($key){
        $fields = SalesSettings::get($key . '_custom_fields');

        if(!$fields){
            return [];
		}

        foreach ($fields as $key => $field) {
            $fields[$key]['name'] = str_slug($field['label'], '_');

            if(!empty($field['rules'])){
                $attrs = '';
                $rules = explode('|', $field['rules']);
                
                foreach ($rules as $rule) {
                    $parts = explode(':', $rule);

                    if(strpos($rule, 'min:') !== false){
						$attrs .= ' minlength="'.$parts[1].'"';
					}

                    if(strpos($rule, 'max:') !== false){
						$attrs .= ' maxlength="'.$parts[1].'"';
					}

                    if(strpos($rule, 'required') !== false){
						$attrs .= ' required';
					}

                    if(strpos($rule, 'email') !== false){
						$fields[$key]['type'] = 'email';
					}
                }

                $fields[$key]['attributes'] = $attrs;
            }
        }

        return $fields;
    }

	public function getProductPageOptions(){
		return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
	}

	public function getReturnPageOptions(){
		return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
	}

	public function onRun(){
        $this->prepareLang();
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

						// $order->reduceInventory();
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
        
        $shippingCountry = null;
        $billingCountry = null;

		if(Country::isEnabled()->count() == 1){
            $onlyOneCountry = Country::isEnabled()->first();
            $shippingCountry = $billingCountry = $onlyOneCountry;
			$this->page['shipping_states'] = $this->page['billing_states'] = $onlyOneCountry->states ?? null;
        }
        
        if(!empty($cart->billing_address['country'])){
            $billingCountry = Country::isEnabled()->where('code', $cart->billing_address['country'])->first();
        }elseif($user){
            if($user->is_ship_same_bill && !empty($user->shipping_address['country']))
                $billingCountry = Country::isEnabled()->where('code', $user->shipping_address['country'])->first();
            elseif(!empty($user->billing_address['country']))
                $billingCountry = Country::isEnabled()->where('code', $user->billing_address['country'])->first();
        }

        if(!empty($cart->shipping_address['country'])){
            $shippingCountry = Country::isEnabled()->where('code', $cart->shipping_address['country'])->first();
        }elseif($user && !empty($user->shipping_address['country'])){
            $shippingCountry = Country::isEnabled()->where('code', $user->shipping_address['country'])->first();
        }

        $this->page['billing_country'] = $billingCountry->code ?? null;
        $this->page['shipping_country'] = $shippingCountry->code ?? null;

        $this->page['billing_states'] = $billingCountry ? $billingCountry->states : null;
        $this->page['shipping_states'] = $shippingCountry ? $shippingCountry->states : null;

        $this->page['methods_list'] = $billingCountry && property_exists($billingCountry, 'code') ? $this->getPaymentMethodsList($billingCountry->code) : null;
        $this->page['method_country_code'] = $billingCountry->code ?? null;

		$this->page['secure'] = GatewaysSettings::get('pixelpay_3ds');
		$this->page['is_tokenization_active'] = $this->isTokenizationActive();
		$this->page['config'] = 
		[
		'key'=> GatewaysSettings::get('pixelpay_app'),
		'hash' =>  md5(GatewaysSettings::get('pixelpay_hash')),
		'end_point' => $this->isTokenizationActive() ? \Tecnocomp\Tokenization\Components\PaymentTraitTecnocomp::getEndPoint(): null,
		];
		
		$this->page['config'] = json_encode($this->page['config']);


		$this->addCss('/plugins/pixel/shop/assets/css/cart.css');
        $this->addJs('/plugins/pixel/shop/assets/js/jquery.mask.min.js');
		$this->addJs('/plugins/pixel/shop/assets/js/jquery.validate.min.js');
        $this->addJs('/plugins/pixel/shop/assets/js/jquery.steps.min.js');
		$this->addJs('/plugins/pixel/shop/assets/js/cart.js');

		$this->addJs('https://unpkg.com/@pixelpay/sdk');
		$this->addJs('https://unpkg.com/axios/dist/axios.min.js');
		$this->addJs('/plugins/tecnocomp/tokenization/assets/js/pixel_3ds.js');

	}

	public function isTokenizationActive () {
		return count(Event::fire('pixel.shop.isTokenizationPlugin')) > 0;
	}
	public function user(){
		if (!$user = Auth::getUser())
		{
			return null;
		}

		return $user;
	}

	public function onShippingCountrySelect(){
		$this->prepareLang();
		if($country = Country::where('code', input('shipping_address.country'))->first()){
            $cart = Cart::load();
            $cart->shipping_address['country'] = input('shipping_address.country');
            $cart->updateTotals();
            $cart->save();

			$return = [
                '#shop__cart-partial' => $this->renderPartial('@cart', [ 'cart' => $cart ]),
                '.shippingStateContainer' => $this->renderPartial('@shipping_states', [
					'shipping_states' => $country->states,
					'user' => $this->user(),
					'cards' => Event::fire('pixel.shop.getCardsByUser', [$this]),
                ]), 
                'code' => $country->code
            ];

			if(input('is_ship_same_bill')){
				$return['.shop__methods-list'] = $this->renderPartial(  $this->isTokenizationActive() ?'@methodsWithToken' : '@methods', [
                    'methods_list' => $this->getPaymentMethodsList(input('shipping_address.country')),
					'method_country_code' => input('shipping_address.country'),
					'cards' => Event::fire('pixel.shop.getCardsByUser', [$this]),
					'user' => $this->user()
				]);
			}

			return $return;
		}
	}

	public function onBillingCountrySelect(){
        $this->prepareLang();

		if($country = Country::where('code', input('billing_address.country'))->first()){
			$return = ['.billingStateContainer' => $this->renderPartial('@billing_states', [
				'billing_states' => $country->states
			]), 'code' => $country->code];

			$return['.shop__methods-list'] = $this->renderPartial(  $this->isTokenizationActive() ?'@methodsWithToken' : '@methods', [
                'methods_list' => $this->getPaymentMethodsList(input('billing_address.country')),
				'method_country_code' => input('billing_address.country'),
				'cards' => Event::fire('pixel.shop.getCardsByUser', [$this]),
				'user' => $this->user()
			]);

			return $return;
		}
	}

	public function onShippingStateSelect(){
        $this->prepareLang();

		$cart = Cart::load();
		$cart->shipping_address['state'] = input('shipping_address.state');
		$cart->shipping_address['country'] = input('shipping_address.country');
		$cart->updateTotals();
		$cart->save();

		return [ '#shop__cart-partial' => $this->renderPartial('@cart', [ 'cart' => $cart ]) ];
    }
    
    public function onSameAddressChange(){
        $this->prepareLang();

        $cart = Cart::load();
		$cart->billing_address['state'] = input('shipping_address.state');
		$cart->billing_address['country'] = input('shipping_address.country');
		$cart->updateTotals();
        $cart->save();
        
        $methodCountry = input('is_ship_same_bill') ? 'shipping' : 'billing';

		return [ 
            '#shop__cart-partial' => $this->renderPartial('@cart', [ 'cart' => $cart ]),
            '.shop__methods-list' => $this->renderPartial(  $this->isTokenizationActive() ?'@methodsWithToken' : '@methods', [
                'methods_list' => $this->getPaymentMethodsList(input($methodCountry . '_address.country')),
				'method_country_code' => input($methodCountry . '_address.country'),
				'cards' => Event::fire('pixel.shop.getCardsByUser', [$this]),
				'user' => $this->user()
			])
        ];
    }
}
