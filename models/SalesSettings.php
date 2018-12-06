<?php namespace Pixel\Shop\Models;

use Model;
use RainLab\Location\Models\Country;

class SalesSettings extends Model{
	// PROPERTIES
	public $implement = ['System.Behaviors.SettingsModel'];
	public $settingsCode = 'pixel_shop_sale_settings';
	public $settingsFields = 'fields.yaml';

	public $attachOne = [
		'default_image' => ['System\Models\File']
	];

	public function getShopCountryOptions(){
		return Country::isEnabled()->orderBy('is_pinned', 'desc')->lists('name', 'code');
	}
}

?>