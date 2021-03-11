<?php namespace Pixel\Shop\Models;

use Model;
use CurrencyShop;
use Event;
use Carbon\Carbon;
use Pixel\Shop\Models\SalesSettings;
use Responsiv\Currency\Models\Currency as CurrencyModel;
use Responsiv\Currency\Classes\Converter as CurrencyConverter;

class Item extends Model{

	// VALIDACIONES
	use \October\Rain\Database\Traits\Validation;
	// SLUG MODEL
	use \October\Rain\Database\Traits\Sluggable;
	
	public $rules = [
		'name' => 'required|min:3|max:180',
		'code' => 'min:3|max:180',
		'price' => 'required',
		'variants.*.variant' => 'required_if:is_with_variants,1|max:60',
		'variants.*.items.*.val' => 'required_if:is_with_variants,1|max:60',
	];

	public $attributeNames = [
		'name' => 'pixel.shop::lang.fields.name',
		'code' => 'pixel.shop::lang.fields.code',
		'price' => 'pixel.shop::lang.fields.price',
		'currency' => 'pixel.shop::lang.fields.currency',
		'variants.*.variant' => 'pixel.shop::lang.fields.option_title',
		'variants.*.items.*.val' => 'pixel.shop::lang.fields.option_value'
	];

	protected $slugs = [
		'slug' => 'name'
	];

	// SOFT DELETE
	use \October\Rain\Database\Traits\SoftDelete;
	protected $dates = ['deleted_at'];
	
	// PROPIEDADES
	public $timestamps = true;
	public $table = 'pixel_shop_items';
	public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];
	public $translatable = ['name', 'short_description', 'description'];
	protected $jsonable = ['tax', 'variants'];

	// RELACIONES
	public $belongsTo = [
		'brand' => ['Pixel\Shop\Models\Brand']
	];
	public $belongsToMany = [
		'categories' => [
			'Pixel\Shop\Models\Category',
			'table' => 'pixel_shop_cat_by_item',
			'order' => 'name',
			'key' => 'item_id'
		]
	];
	public $hasMany = [ ];

	// ADJUNTOS Y ARCHIVOS
	public $attachMany = [
		'gallery' => ['System\Models\File'],
		'attachments' => ['System\Models\File'],
	];

	public function getImageAttribute(){
		if($this->gallery->count())
            return $this->gallery->first();

        $settings = SalesSettings::instance();
        return $settings->default_image;
	}

	// SCOPES
	public function scopeCategories($query, $category){
		if(!$category)
			return $query;

		if($child = $category->children)
			$categories = $category->children->lists('slug');

		$categories[] = $category->slug;

		return $query->whereHas('categories', function($q) use ($categories) {
			$q->whereIn('slug', $categories);
		});
	}

	public function scopeShowItems($query){
		return $query->where("is_visible", 1);
	}

	public function scopeOnSale($query){
		return $query->where("is_visible", 1)->where("is_on_sale", 1);
	}

	public function scopeNewItems($query){
		$dateQuery = Carbon::now()->subDays(30);
		return $query->where('created_at', '>', $dateQuery);
	}

	// EVENTS
	public function beforeSave(){
		
	}

	// GET OPTIONS
	public function getTaxOptions(){
		$options = [];

		if(SalesSettings::get('taxes')){
			foreach (SalesSettings::get('taxes') as $value)
				$options[$value['percent']] = $value['name'].' ('.$value['percent'].'%)';
        }
        
		return $options;
	}
	
	public function getCurrencyOptions(){
		return CurrencyModel::isEnabled()->lists('currency_code','currency_code');
	}

	// FILTERS
	public function scopeFilterCategories($query, $categories){
		return $query->whereHas('categories', function($q) use ($categories) {
			$q->whereIn('id', $categories);
		});
	}

	// STATIC
	public static function getLowStockProducts(){
		return self::where('quantity', '<', 3)->count();
	}

	// METHODS
	public function setUrl($pageName, $controller, $param = 'slug'){
		$params = [ $param => $this->slug ];

		return $this->url = $controller->pageUrl($pageName, $params);
	}

	public function isInfiniteQuantity(){
		return $this->getOriginal('quantity') === null;
	}

	public function isFavorite(){
		if($user = \RainLab\User\Facades\Auth::getUser()){
			if($favorite = Favorite::where('user_id', $user->id)
				->where('item_id', $this->id)->first())
				return $favorite->is_favorite;
			return;
		}
		return;
	}

	public function checkIfOutStock($value){
		$isInfinite = $value === null;

		return !$isInfinite && $value < 1;
	}

	public function getPrice($with_tax = null){
		$newPrice = Event::fire('pixel.shop.getPriceProperty', [$this]);
        $this->price = !empty($newPrice) > 0 ? $newPrice[0]['price'] : $this->price;
		$code = CurrencyShop::default();
		$tax = 0.00;

		if($with_tax === null)
			$with_tax = SalesSettings::get('price_with_tax');

		if($with_tax && $this->tax){
			foreach ($this->tax as $inTax) {
				if($inTax > 0)
					$tax += ($inTax/100) * $this->price;
			}
		}

		if($code == $this->currency)
			return ($this->price + $tax);		
		

		return CurrencyConverter::instance()->convert(($this->price + $tax), $this->currency, $code, 2);
	}

	public function getOldPrice(){
		$code = CurrencyShop::default();
		$tax = 0.00;

		if(SalesSettings::get('price_with_tax') && $this->tax){
			foreach ($this->tax as $inTax) {
				if($inTax > 0)
					$tax += ($inTax/100) * $this->old_price;
			}
		}

		if($code == $this->currency)
			return ($this->old_price + $tax);

		return CurrencyConverter::instance()->convert(($this->old_price + $tax), $this->currency, $code, 2);
	}

	public function getPriceWithTax(){
		$tax = 0.00;
		if($this->tax){
			foreach ($this->tax as $inTax) {
				if($inTax > 0)
					$tax += ($inTax/100) * $this->price;
			}
		}

		return ($this->price + $tax);
	}

	public function getPriceOption($price){
		$code = CurrencyShop::default();

		if($code == $this->currency)
			return $price;

		return CurrencyConverter::instance()->convert($price, $this->currency, $code, 2);
	}

	public function getTaxOption($price){
		$code = CurrencyShop::default();
		$tax = 0.00;

		if(SalesSettings::get('price_with_tax') && $this->tax){
			foreach ($this->tax as $inTax) {
				if($inTax > 0)
					$tax += ($inTax/100) * $price;
			}
		}

		if($code == $this->currency)
			return $tax;

		return CurrencyConverter::instance()->convert($tax, $this->currency, $code, 2);
	}

	public function getCurrencySymbol(){
		if(!$currency = CurrencyModel::where('currency_code', $this->currency)->first())
			return $this->currency;

		return $currency->currency_symbol;
	}

	public function getDefaultCurrencySymbol(){
		if(!$currency = CurrencyModel::where('currency_code', CurrencyShop::default())->first())
			return $this->currency;

		return $currency->currency_symbol;
	}

	public function getOptionIndex($option, $i){
		if(!array_key_exists($i, $this->variants))
			return -1;

		foreach($this->variants[$i]['items'] as $key => $option){
			if($option['val'] == $option)
				return $key;
		}
	}

	public function slugOption($text){
		return str_slug($text);
	}

	public function getCategoriesNames(){
		$categories = $this->categories->lists('name');

		return implode(',' , $categories);
	}
}