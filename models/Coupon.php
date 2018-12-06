<?php namespace Pixel\Shop\Models;

use Model;
use Carbon\Carbon;

class Coupon extends Model{

	const OK = 0;
	const NOT_ACTIVE = 1;
	const INVALID_DATE = 2;
	const MAX_USED_COUNT = 3;
	const NOT_MINIMAL_AMOUNT = 4;

	public $errorCode;

	// VALIDATION
	use \October\Rain\Database\Traits\Validation;

	public $rules = [
		'code' => 'required|unique:pixel_shop_coupons',
		'is_active' => 'nullable',
		'valid_from' => 'nullable',
		'valid_to' => 'nullable',
		'count' => 'nullable',
		'used_count' => 'nullable',
		'minimum_value_cart' => 'nullable',
		'value' => 'required',
		'type_value' => 'nullable',
	];
	public $attributeNames = [
		'code' => 'pixel.shop::lang.fields.code'
	];
	protected $dates = [
		'valid_from',
		'valid_to'
	];

	// PROPERTIES
	public $timestamps = true;
	protected $fillable = [
		'code',
		'is_active',
		'valid_from',
		'valid_to',
		'count',
		'used_count',
		'minimum_value_cart',
		'value',
		'type_value',
	];

	public $table = 'pixel_shop_coupons';

	public function beforeCreate(){
		$this->used_count = 0;
	}

	public function beforeSave() {
		$this->code = strtoupper($this->code);
	}

	public function getTypeValueOptions(){
		return [
			1 => trans("pixel.shop::lang.fields.coupon_type_value_1"),
			2 => trans("pixel.shop::lang.fields.coupon_type_value_2"),
		];
	}

	public function isValid($total = null){

		$this->errorCode = self::OK;

		// CHECK IS ACTIVE
		if ($this->is_active == false){
			$this->errorCode = self::NOT_ACTIVE;
			return false;
		}
		
		// CHECK DATE TO
		if ($this->valid_to != null) {
			if (Carbon::parse($this->valid_to)->isPast()) {
				$this->errorCode = self::INVALID_DATE;
				return false;
			}
		}
		if ($this->valid_from != null) {
			if (Carbon::parse($this->valid_from)->isFuture()) {
				$this->errorCode = self::INVALID_DATE;
				return false;
			}
		}
		
		// CHECK COUNT
		if ($this->count > 0) {
			if ($this->used_count >= $this->count) {
				$this->errorCode = self::MAX_USED_COUNT;
				return false;
			}
		}
		
		
		// CHECK MINIMUM_VALUE_BASKET
		if ($total != null && $this->minimum_value_cart > 0) {
			if ($total < $this->minimum_value_cart) {
				$this->errorCode = self::NOT_MINIMAL_AMOUNT;
				return false;
			}
		}
		
		return true;
	}

	public function getValueLabel() {
		if ($this->type_value==1)
			return $this->value."%";
		else
			return number_format($this->value, 2);
	}

	public function getFinalDiscount($subtotal) {
		if ($this->type_value==1)
			return (($subtotal / 100) * $this->value);
		else
			return $this->value;
	}
}