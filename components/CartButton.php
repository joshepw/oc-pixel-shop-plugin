<?php namespace Pixel\Shop\Components;

use Cms\Classes\Page;
use Pixel\Shop\Classes\Cart;
use Cms\Classes\ComponentBase;

class CartButton extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Cart Button',
            'description' => 'View the Cart Notifications and Items Count'
        ];
    }

    public function defineProperties()
    {
        return [
        	'cartPage' => [
				'title'       => 'Cart page',
				'description' => 'Cart resume page',
				'type'        => 'dropdown',
				'default'     => 'cart'
			],
			'colorBG' => [
				'title'       => 'Button background color',
				'description' => 'The button background color in HEX',
				'default'     => '#C43730',
				'type'        => 'string',
				'validationPattern' => '(^#[0-9A-F]{6}$|^#[0-9A-F]{3}$)',
				'validationMessage' => 'The color value is invalid'
			],
			'color' => [
				'title'       => 'Button text color',
				'description' => 'The button text color in HEX',
				'default'     => '#FFFFFF',
				'type'        => 'string',
				'validationPattern' => '(^#[0-9A-F]{6}$|^#[0-9A-F]{3}$)',
				'validationMessage' => 'The color value is invalid'
			],
			'position' => [
				'title'       => 'Button position',
				'type'        => 'dropdown',
				'default'     => 'bottom-right',
				'options'	=> [
					'top-left' => 'Top Left',
					'top-right' => 'Top right',
					'bottom-left' => 'Bottom left',
					'bottom-right' => 'Bottom right'
				]
            ],
            'hideOnEmpty' => [
                'title'       => 'Hide on empty',
                'type'        => 'checkbox',
                'default'     => false
            ]
        ];
    }

    public function getCartPageOptions(){
		return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
	}

	public function onRun(){
        $this->prepareLang();

		$this->page['cart'] = $cart = Cart::load();
		$this->page['cart_count'] = count($cart->items);

		$this->addCss('/plugins/pixel/shop/assets/css/button.css');
		$this->addJs('/plugins/pixel/shop/assets/js/button.js');

		$this->page['cart_position'] = $this->property('position');
		$this->page['cart_color_bg'] = $this->property('colorBG');
		$this->page['cart_color'] = $this->property('color');
        $this->page['cart_page'] = $this->property('cartPage');
        $this->page['hide_on_empty'] = $this->property('hideOnEmpty');
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
}
