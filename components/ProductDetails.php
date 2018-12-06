<?php namespace Pixel\Shop\Components;

use Response;
use Cms\Classes\Page;
use Pixel\Shop\Models\Item;
use Pixel\Shop\Classes\Cart;
use Pixel\Shop\Models\SalesSettings;
use Pixel\Shop\Components\CartTrait;

use Cms\Classes\ComponentBase;

class ProductDetails extends ComponentBase{
	use CartTrait;

	public function componentDetails()
	{
		return [
			'name'        => 'Product Detail',
			'description' => 'Display detail of product'
		];
	}

	public function defineProperties(){
		return [
			'slug' => [
				'title'       => 'URL Slug',
				'description' => 'URL Slug',
				'default'     => '{{ :slug }}',
				'type'        => 'string'
			],
			'productsPage' => [
				'title'       => 'Products page',
				'description' => 'Product list page',
				'type'        => 'dropdown',
				'default'     => 'products',
			],
		];
	}

	public function getProductPageOptions(){
		return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
	}

	public function onRun(){
    	$slug = $this->property('slug');
    	$product = Item::where('slug', $slug)->where("is_visible", 1)->first();

    	$this->addCss('/plugins/pixel/shop/assets/css/product.css');
		$this->addJs('/plugins/pixel/shop/assets/js/product.js');

		if ($product == null)
            return redirect($this->property('productsPage'));
        else
        	$product->increment('views_count');

        $this->page['product'] = $product;
        $this->page['shopSetting'] = SalesSettings::instance();

        $product->setUrl($this->page->code, $this->controller);

        if (isset($product)) {
            $this->page->meta_title = $product->meta_title;
            $this->page->meta_description = $product->meta_description;
            $this->page->meta_keywords = $product->meta_keywords;
        }
    }
}
