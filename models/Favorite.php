<?php namespace Pixel\Shop\Models;

use DB;
use Model;

class Favorite extends Model{

	public $table = 'pixel_shop_favorites';
	public $timestamps = false;

	protected $fillable = [
		'user_id',
		'item_id',
		'is_favorite'
	];

	public $belongsTo = [
		'item' => 'Pixel\Shop\Models\Item'
	];
}

?>