<?php namespace Pixel\Shop\Components;

use Flash;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;

use Pixel\Shop\Models\Item;
use Pixel\Shop\Models\Category;
use Pixel\Shop\Models\Favorite;
use Pixel\Shop\Models\SalesSettings;
use Pixel\Shop\Components\CartTrait;

class ProductList extends ComponentBase
{
    use CartTrait;
    
    public $products = array();
	public $settings = array();

    public function componentDetails()
    {
        return [
            'name'        => 'Products',
			'description' => 'Display list of products'
        ];
    }

    public function defineProperties()
    {
        return [
        	'categoryFilter' => [
				'title'       => 'Category Filter',
				'description' => 'Look up the blog category using the supplied slug value. This property is used by the default component partial for marking the currently active category.',
				'type'        => 'dropdown',
				'default'     => '{{ :category }}',
			],
			'scope' => [
				'title'       => 'Scope by',
				'default'     => 'showItems',
				'type'        => 'dropdown',
				'options'     => [
					'showItems' => 'No scope (Show All)', 
					'onSale' => 'On Sale Only',
				]
			],
			'limitType' => [
				'title'       => 'Paginate or only Take',
				'default'     => 'paginate',
				'type'        => 'dropdown',
				'options' => [
					'paginate'	=> 'Paginate Items',
					'take'		=> 'Only Take a limit'
				]
			],
			'take' => [
				'title'       => 'Limit of products',
				'description' => 'Take X products only or paginate',
				'default'     => '20',
				'type'        => 'string',
				'validationPattern' => '^[0-9]+$',
				'validationMessage' => 'The Max Items property can contain only numeric symbols'                
			],
			'orderBy' => [
				'title'       => 'Order By',
				'description' => 'Order By',
				'default'     => 'name',
				'type'        => 'dropdown',
				'options'     => [
					'name' => 'Name', 
					'price' => 'Price with Tax', 
					'is_on_sale' => 'On Sale',
					'id' => 'ID',
					'code' => 'Code or SKU',
					'quantity' => 'Quantity',	
					'updated_at' => 'Updated At',
					'created_at' => 'Created At',
					'sales_count' => 'Sales count',
					'views_count' => 'Views count'
				]
			],   
			'sort' => [
				'title'       => 'Sort',
				'description' => 'Sort ASC or DESC',
				'default'     => 'asc',
				'type'        => 'dropdown',
				'options'     => ['asc'=>'Ascending', 'desc'=>'Descending']
			],              
			'productPage' => [
				'title'       => 'Product page',
				'description' => 'Product detail page',
				'type'        => 'dropdown',
				'default'     => 'product',
			],
			'categoryPage' => [
				'title'       => 'Products page by category',
				'type'        => 'dropdown',
				'default'     => 'products',
			],
			'showCategoriesFilter' => [
				'title'		  => 'Show categories filter',
				'type'		  => 'checkbox',
				'default'     => false,
			],
			'showSearchBar' => [
				'title'		  => 'Show search bar',
				'type'		  => 'checkbox',
				'default'     => false,
			],
			'showQuickAdd' => [
				'title'		  => 'Quick "add to cart"',
				'type'		  => 'checkbox',
				'default'     => true,
            ],
            'typeCategoriesFilter' => [
				'title'       => 'Categories Filter Type',
				'default'     => 'dropdown',
				'type'        => 'dropdown',
				'options'     => ['dropdown'=>'Dropdown', 'buttons'=>'Buttons Group']
			], 
        ];
    }

    public function onRun()
	{
		$this->addCss('/plugins/pixel/shop/assets/css/products.css');

		$this->products = $this->page['products'] = $this->loadProducts();
		$this->settings = $this->page['shopSetting'] = SalesSettings::instance();

        $this->page['showCategoriesFilter'] = $this->property('showCategoriesFilter');
		$this->page['showSearchBar'] = $this->property('showSearchBar');
		$this->page['showQuickAdd'] = $this->property('showQuickAdd');
        $this->page['typeCategoriesFilter'] = $this->property('typeCategoriesFilter');
	}

    // LOAD MODELS
    protected function loadCategory(){
		if (!$categoryId = $this->property('categoryFilter'))
			return null;

		if (!$category = Category::whereSlug($categoryId)->first())
			return null;

		return $category;
	}

	protected function loadProducts()
	{
		$page = $this->property('productPage');
		$scope = $this->property('scope');
		$category = $this->page['activeCategory'] = $this->loadCategory();
		$take = $this->property('take');
		$orderBy = $this->property('orderBy');
		$orderSort = $this->property('sort');
	$products = null;

		$this->page['categoryList'] = $this->getCategoryList();

		$query = Item::select();

		if(input('search')){
			$query->where(function($query){
				$words = explode(' ', input('search'));

				foreach ($words as $word)
					$query->orWhere('name', 'LIKE', '%'.$word.'%');
			});
		}

		if($scope)
			$query->$scope();

		if($category)
			$query->categories($category);

		if($orderBy)
			$query->orderBy($orderBy, $orderSort);

		if($this->property('limitType') == 'take')
			$products = $query->take($take)->get();
		else
			$products = $query->paginate($take);
		
		$products->each(function($product) use ($page) {
			$product->setUrl($page, $this->controller);
		});

		return $products;
	}

    // OPTIONS
    public function getProductPageOptions(){
		return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
	} 

	public function getCategoryPageOptions(){
		return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
	}

	public function getCategoryFilterOptions(){
		$list = Category::lists('name', 'slug');
		array_unshift($list, 'All Categories');
		return $list;
	}


	// METHODS
	public function getCategoryList(){
		if(!$param = $this->paramName('categoryFilter'))
			return;

		$categories = Category::all();
		$page = $this->property('categoryPage');
		$list = array();
		
		$empty = new Category();
		$empty->name = 'All Categories';
		$empty->setUrl($page, $this->controller, $param);

		$list[] = $empty;
		
		$categories->each(function($item) use ($page, $param, &$list) {
			$item->setUrl($page, $this->controller, $param);

			if($item->items->count() > 0)
				$list[] = $item;
		});

		return $list;
	}

	public function onSetFavorite(){
		$item_id = post('id');
		
		if (class_exists("\RainLab\User\Models\User")){
			$user = \RainLab\User\Facades\Auth::getUser();
		}else{
			Flash::error('The plugin RainLab.User not found, please install first');
			return;
		}

		if($user){
			if($item_id){
				if($fav = Favorite::where('user_id', $user->id)->where('item_id', $item_id)->first()){
					$fav->is_favorite = !$fav->is_favorite;
					$fav->save();
				}else{
					$fav = Favorite::create([
						'user_id' => $user->id,
						'item_id' => $item_id,
						'is_favorite' => 1
					]);
				}

				$item = Item::find($item_id);

					return ['#favorite-product-'.$item_id => $this->renderPartial('@favorite', [
						'userLogged' => $user,
						'product' => $item
					])];
			}
		}else{
			Flash::error('Please login first to save favorites.');
			return;
		}
	}
}
