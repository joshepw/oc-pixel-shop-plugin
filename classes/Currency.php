<?php namespace Pixel\Shop\Classes;

use Responsiv\Currency\Models\Currency as CurrencyModel;
use Responsiv\Currency\Helpers\Currency as CurrencyHelper;

class Currency extends CurrencyHelper{
	public static function getCurrency($value, $code){
		return CurrencyModel::findByCode($code)->formatCurrency($value,2);
	}

	public static function default(){
		$currency = new CurrencyHelper();
		$code = $currency->primaryCode();

		return $code;
	}
}

?>