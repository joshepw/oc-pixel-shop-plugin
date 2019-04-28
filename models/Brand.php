<?php namespace Pixel\Shop\Models;

use URL;
use Model;
use Cms\Classes\Theme;
use Cms\Classes\Page as CmsPage;

class Brand extends Model{

	// VALIDATION
	use \October\Rain\Database\Traits\Validation;
	// SLUG MODEL
	use \October\Rain\Database\Traits\Sluggable;

	public $rules = [
		'name' => 'required|min:3|max:180'
	];
	public $attributeNames = [
		'name' => 'pixel.shop::lang.fields.name'
	];
	protected $slugs = [
		'slug' => 'name'
	];

	public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];
	public $translatable = ['name'];

	// PROPERTIES
	public $timestamps = false;
	protected $fillable = ['name'];
	public $table = 'pixel_shop_brands';

	public static function getMenuTypeInfo($type){
		$result = [ 'dynamicItems' => true ];
		
		$theme = Theme::getActiveTheme();
		$pages = CmsPage::listInTheme($theme, true);
		$cmsPages = [];

		foreach ($pages as $page)
			$cmsPages[] = $page;

		$result['cmsPages'] = $cmsPages;

		return $result;
	}

	public static function resolveMenuItem($item, $url, $theme){
        $result['items'] = array();
        $items = array();
        $brands = self::where("show_in_menu", 1)->orderBy("name", "asc")->get();

        foreach ($brands as $brand) {
            if ($item->cmsPage)
                $urlPage = CmsPage::url($item->cmsPage, ["brand" => $brand->slug]);
            else
                $urlPage = URL::to("/products/".$brand->slug);
            
            $items[] = [
                'url' => $urlPage,
                'isActive' => ($url == $urlPage),
                'title' => $brand->name,
                'mtime' => $brand->updated_at,
            ];              
        }

        $result['items'] = $items;

        return $result;   
    }
}