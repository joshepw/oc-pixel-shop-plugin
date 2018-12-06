<?php namespace Pixel\Shop\Models;

use URL;
use Model;
use Cms\Classes\Theme;
use Cms\Classes\Page as CmsPage;

/**
 * Model
 */
class Category extends Model
{
	// REORDER
	use \October\Rain\Database\Traits\NestedTree;
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

	public $belongsToMany = [
		'items' => [
			'Pixel\Shop\Models\Item',
			'table' => 'pixel_shop_cat_by_item',
			'order' => 'name',
			'key' => 'category_id'
		]
	];

	public $belongsTo = [
        'parent' => [
            'Pixel\Shop\Models\Category', 
            'key' => 'parent_id'
        ],
    ];

	// PROPERTIES
	public $timestamps = false;
	public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];
	public $table = 'pixel_shop_categories';
	public $fillable = ['name'];
	public $translatable = ['name'];

	// METHODS
	public function setUrl($pageName, $controller, $param = 'slug'){
		$params = [ $param => $this->slug ];

		return $this->url = $controller->pageUrl($pageName, $params);
	}

	// MENU METHODS
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
		$categories = self::where("nest_depth", 0)->get();

		foreach ($categories as $rootCategory) {
			self::recursiveMenuItems($item, $url, $rootCategory, $items);
			$result['items'] = $items;
		}

		return $result;
	}

	private static function recursiveMenuItems($menuItem, $currentUrl, $element, &$items) {
		if(!$element->show_in_menu)
        	return;
        
        if ($menuItem->cmsPage)
            $url = CmsPage::url($menuItem->cmsPage, ["category" => $element->slug]);
       	else
            $url = URL::to("/products/".$element->slug);

        $newId = count($items);
        
        $items[$newId] = [
            'url' => $url,
            'isActive' => ($currentUrl == $url),
            'title' => $element->name,
            'mtime' => $element->updated_at,
        ]; 
        
        $subitems = array();

        foreach ($element->children as $elementChild)
            self::recursiveMenuItems($menuItem, $currentUrl, $elementChild, $subitems);

        $items[$newId]["items"] = $subitems;
    }
}