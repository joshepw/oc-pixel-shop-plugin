<?php namespace Pixel\Shop\Models;

use Model;
use RainLab\Location\Models\Country;

class GatewaysSettings extends Model{
	// PROPERTIES
    public $implement = [
        'System.Behaviors.SettingsModel', 
        '@RainLab.Translate.Behaviors.TranslatableModel'
    ];

	public $settingsCode = 'pixel_shop_gateways_settings';
    public $settingsFields = 'fields.yaml';
    
    public $translatable = [
        'bank_transfer_details',
        'bank_transfer_customer_information',
        'cash_on_delivery_customer_information'
    ];

	public function getCcCountriesOptions(){
		return Country::where('is_enabled', 1)->lists('name', 'code');
	}

	public function getPaypalCountriesOptions(){
		return Country::where('is_enabled', 1)->lists('name', 'code');
	}

	public function getBankTransferCountriesOptions(){
		return Country::where('is_enabled', 1)->lists('name', 'code');
	}

	public function getCashOnDeliveryCountriesOptions(){
		return Country::where('is_enabled', 1)->lists('name', 'code');
	}

	public function getPixelpayCountriesOptions(){
		return Country::where('is_enabled', 1)->lists('name', 'code');
	}

	public function checkIsAllowed($country, $type){
		return self::get($type.'_is_active') && $this->checkCountryIsAllowed($country, $type);
	}

	private function checkCountryIsAllowed($country, $type){
		if(self::get($type.'_countries') === null)
			return true;

		if(!is_array(self::get($type.'_countries')))
			return true;

		if(is_array(self::get($type.'_countries')) && 
			!empty(self::get($type.'_countries')) && 
			in_array($country, self::get($type.'_countries')))
			return true;

		return;
	}
}

?>