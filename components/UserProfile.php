<?php namespace Pixel\Shop\Components;

use Auth;
use Lang;
use Event;
use Flash;
use Input;
use Request;
use Exception;
use Validator;
use Cms\Classes\Page;
use ValidationException;
use ApplicationException;
use Pixel\Shop\Models\Order;
use Cms\Classes\ComponentBase;
use Mail;
use Pixel\Shop\Models\Favorite;
use RainLab\Location\Models\State;
use RainLab\Location\Models\Country;
use Pixel\Shop\Classes\PartialMaker;
use RainLab\User\Models\Settings as UserSettings;
use RainLab\User\Models\User;

class UserProfile extends ComponentBase{

    public $tabs;

    public function componentDetails(){
        return [
            'name'        => 'Account',
            'description' => 'User management form'
        ];
    }

    public function defineProperties(){
        return [
            'redirectOnLogin' => [
                'title'       => 'Redirect on login',
                'type'        => 'dropdown',
                'default'     => ''
            ],
            'redirectOnRegister' => [
                'title'       => 'Redirect on register',
                'type'        => 'dropdown',
                'default'     => ''
            ],
        	'productPage' => [
				'title'       => 'Product page',
				'description' => 'Product detail page',
				'type'        => 'dropdown',
				'default'     => 'product',
            ]
        ];
    }

    public function onRun(){
        if ($code = $this->activationCode()){
            $this->onActivate($code);
        }

        $this->prepareVars();
        $this->prepareLang();

        $this->addCss('/plugins/pixel/shop/assets/css/user.css');
        $this->addCss('/plugins/pixel/shop/assets/css/products.css');
        $this->addJs('/plugins/pixel/shop/assets/js/jquery.mask.min.js');
        $this->addJs('/plugins/pixel/shop/assets/js/user.js');

        // Extend Tabs and content
        $this->addComponentTab('orders', [
            'title' => 'pixel.shop::component.user.orders',
            'order' => 20,
            'content' => $this->renderPartial('@orders')
        ]);
            
        $this->addComponentTab('favorites', [
            'title' => 'pixel.shop::component.user.favorites',
            'order' => 30,
            'content' => $this->renderPartial('@favorites')
        ]);

        $this->addComponentTab('account', [
            'title' => 'pixel.shop::component.user.account',
            'order' => 10,
            'content' => $this->renderPartial('@account')
        ]);

        Event::fire('pixel.shop.profile.extendtabs', [$this]);
    }

    protected function prepareLang(){
        $lang = \Config::get('app.locale', 'en');

        if(\System\Models\PluginVersion::where('code', 'RainLab.Translate')->where('is_disabled', 0)->first()){
            $translator = \RainLab\Translate\Classes\Translator::instance();
            $activeLocale = $translator->getLocale();
            $lang = $activeLocale;
        }

        if(!empty(post('lang'))){
            $lang = post('lang');
        }

        \App::setLocale($lang);
    }

    public function addComponentTab($id, $tab){
        $tab['id'] = $id;
        $this->tabs[] = $tab;

        usort($this->tabs, function($a, $b){
            if(array_key_exists('order', $a) && array_key_exists('order', $b)){
                if($a['order'] == $b['order']){
                    return 0;
                }

                return ($a['order'] < $b['order']) ? -1 : 1;
            }else{
                return 0;
            }
        });
    }

    public function prepareVars(){
    	$this->page['user'] = $user = $this->user();
    	$this->page['canRegister'] = $this->canRegister();
    	$this->page['countries'] = Country::isEnabled()->orderBy('is_pinned', 'desc')->get();
        $this->page['favorites'] = $this->loadFavorites($user);
        
        $this->tabs = array();

        if($user){
            if($user->billing_address && is_array($user->billing_address) && array_key_exists('country', $user->billing_address)){
                if($thisCountry = Country::isEnabled()->where('code', $user->billing_address['country'])->first()){
                    $this->page['billing_states'] = $thisCountry->states;
                }
            }
    
            if($user->shipping_address && is_array($user->shipping_address) && array_key_exists('country', $user->shipping_address)){
                if($thisCountry = Country::isEnabled()->where('code', $user->shipping_address['country'])->first()){
                    $this->page['shipping_states'] = $thisCountry->states;
                }
            }
        }
    }

    public function user(){
        if (!Auth::check()){
            return null;
        }

        return Auth::getUser();
    }

    public function canRegister(){
        return UserSettings::get('allow_registration', true);
    }

    public function activationCode(){
        $routeParameter = $this->property('paramCode');

        if ($code = $this->param($routeParameter)){
            return $code;
        }

        return get('activate');
    }

    public function onActivate($code = null){
        $this->prepareLang();

        try {
            $code = post('code', $code);
            $errorFields = ['code' => trans('rainlab.user::lang.account.invalid_activation_code')];

            $parts = explode('!', $code);
            if (count($parts) != 2){
                throw new ValidationException($errorFields);
            }

            list($userId, $code) = $parts;

            if (!strlen(trim($userId)) || !strlen(trim($code))){
                throw new ValidationException($errorFields);
            }

            if (!$user = Auth::findUserById($userId)){
                throw new ValidationException($errorFields);
            }

            if (!$user->attemptActivation($code)){
                throw new ValidationException($errorFields);
            }

            Flash::success(trans('rainlab.user::lang.account.success_activation'));

            Auth::login($user);
        }
        catch (Exception $ex) {
            if (Request::ajax()) {
                throw $ex;
            }
            else {
                Flash::error($ex->getMessage());
            }
        }
    }

    public function onShippingCountrySelect(){
        $this->prepareLang();
		if($country = Country::where('code', input('shipping_address.country'))->first()){
			$return = ['.shippingStateWrapper' => $this->renderPartial('@states', [
				'states' => $country->states
			]), 'code' => $country->code];

			return $return;
		}
	}

	public function onBillingCountrySelect(){
        $this->prepareLang();

		if($country = Country::where('code', input('billing_address.country'))->first()){
			$return = ['.billingStateWrapper' => $this->renderPartial('@states', [
				'states' => $country->states
			]), 'code' => $country->code];

			return $return;
		}    	
	}

	public function onSignin(){
        try {
            $this->prepareLang();

            $data = post();
            $rules = [
            	'username' => 'required|email|between:6,255',
            	'password' => 'required|between:4,255'
            ];

            $validation = Validator::make($data, $rules);

            if ($validation->fails()){
                throw new ValidationException($validation);
            }

            $credentials = [
                'login'    => array_get($data, 'username'),
                'password' => array_get($data, 'password')
            ];

            Event::fire('rainlab.user.beforeAuthenticate', [$this, $credentials]);
            $user = Auth::authenticate($credentials, true);

            if ($user->isBanned()) {
                Auth::logout();
                throw new Exception(trans('rainlab.user::lang.account.banned'));
            }

            if ($redirect = $this->makeRedirection('Login')){
                return $redirect;
            }
        }catch (Exception $ex) {
            if (Request::ajax()) {
                throw $ex;
            }
            else {
                Flash::error($ex->getMessage());
            }
        }
    }

    public function onRegister(){
    	try {
            $this->prepareLang();

            if (!$this->canRegister()){
                throw new Exception(trans('rainlab.user::lang.account.registration_disabled'));
            }

            $data = post();

            if (!array_key_exists('password_confirmation', $data)){
                $data['password_confirmation'] = post('password');
            }

            if (!array_key_exists('username', $data)){
                $data['username'] = post('email');
            }

            $rules = [
            	'name' => 'required|min:3|max:191',
                'email'    => 'required|email|between:6,255',
                'password' => 'required|between:4,255|confirmed'
            ];

            $validation = Validator::make($data, $rules);

            if ($validation->fails()){
                throw new ValidationException($validation);
            }

            Event::fire('rainlab.user.beforeRegister', [&$data]);

            $requireActivation = UserSettings::get('require_activation', true);
            $automaticActivation = UserSettings::get('activate_mode') == UserSettings::ACTIVATE_AUTO;
            $userActivation = UserSettings::get('activate_mode') == UserSettings::ACTIVATE_USER;
            $user = Auth::register($data, $automaticActivation);

            Event::fire('rainlab.user.register', [$user, $data]);

            if ($userActivation) {
                $this->sendActivationEmail($user);
                Flash::success(trans('rainlab.user::lang.account.activation_email_sent'));
            }

            if ($automaticActivation || !$requireActivation){
                Auth::login($user);
            }

            if ($redirect = $this->makeRedirection('Register')){
                return $redirect;
            }
        }catch (Exception $ex) {
            if (Request::ajax()) {
                throw $ex;
            }
            else {
                Flash::error($ex->getMessage());
            }
        }
	}
	
	public function onRecovery() {
		$this->prepareLang();
		$email = post('email');

		$user = User::where('email', $email)->first();

		if(!$user) {
			Flash::error('El usuario no existe');
			return;
		}

		$password = str_random(10);

		$user->password = $password;
        $user->password_confirmation = $password; 
		$user->save();

		Mail::sendTo($email, 'pixel.shop::mail.recovery_password', [
			'user' => $user->name,
			'password' => $password
		]);

		Flash::success('Su contraseña fue re-establecida, recibira un correo con su nueva contrseña');
		return;
	}

	public function onLogOut(){
		Auth::logout();
    }
    
    protected function makeRedirection($from = 'Login'){
        $property = trim((string) $this->property('redirectOn' . $from));
        
        if ($property === ''){
            return ['action' => 'refresh'];
        }

        $redirectUrl = $this->pageUrl($property) ?: $property;

        if (!empty(post('redirect'))){
            return ['action' => 'redirect', 'url' => post('redirect')];
        }

        return ['action' => 'redirect', 'url' => $redirectUrl];
    }

	public function onUpdate(){
        $this->prepareLang();

        if (!$user = $this->user()){
            return;
        }

        if (Input::hasFile('avatar')){
            $user->avatar = Input::file('avatar');
        }

        $user->fill(post());
        $user->is_ship_same_bill = $user->is_ship_same_bill ?? false;
        $user->save();

        if (strlen(post('password'))){
            Auth::login($user->reload(), true);
        }

        Flash::success(post('flash', trans('rainlab.user::lang.account.success_saved')));

        $this->prepareVars();
    }

    public function onDeactivate(){
        $this->prepareLang();
        
        if (!$user = $this->user()){
            return;
        }

        if (!$user->checkHashValue('password', post('password'))){
            throw new ValidationException(['password' => trans('rainlab.user::lang.account.invalid_deactivation_pass')]);
        }

        Auth::logout();
        $user->delete();

        Flash::success(post('flash', trans('rainlab.user::lang.account.success_deactivation')));
    }

	protected function loadFavorites($user){
		$page = $this->property('productPage');
		$favorites = null;

		if($user){
			$favorites = $user->favorites;

			$favorites->each(function($favorite) use ($page) {
                $favorite->item->setUrl($page, $this->controller);
			/**
			 * Quantity Event
			 */
			$newQuantity = Event::fire('pixel.shop.getQuantityProperty', [$this, $favorite]);
			$favorite->quantity = !empty($newQuantity) > 0 ? $newQuantity[0]['quantity'] : $favorite->quantity;
			});
		}

		return $favorites;
	}

	public function getProductPageOptions(){
		return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
	}

	public function onSetFavorite(){
        $this->prepareLang();

		$item_id = post('id');
		
		if (class_exists("\RainLab\User\Models\User")){
			$user = \RainLab\User\Facades\Auth::getUser();
		}else{
			Flash::error('The plugin RainLab.User not found, please install first');
			return;
		}

		if($user){
			if($item_id){
				if($fav = Favorite::where('user_id', $user->id)->where('item_id', $item_id)->first()){
					$fav->is_favorite = !$fav->is_favorite;
					$fav->save();
				}

				return ['#favorites-content' => $this->renderPartial('@favorites', [
					'favorites' => $this->loadFavorites($user)
				])];
			}
		}else{
			Flash::error(trans('pixel.shop::lang.components.pl_please_login'));
			return;
		}
	}

	public function onLoadOrder(){
        $this->prepareLang();

		$item_id = post('id');

		if(!$order = Order::find($item_id)){
            return;
        }

		return ['#orders-content' => $this->renderPartial('@order', [ 'order' => $order ])];
	}

	public function onLoadOrders(){
        $this->prepareLang();
		return ['#orders-content' => $this->renderPartial('@orders', [ 'user' => $this->user() ])];
    }
    
    public function getRedirectOnLoginOptions()
    {
        return [''=>'- refresh page -'] + Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getRedirectOnRegisterOptions()
    {
        return [''=>'- refresh page -'] + Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }
}
