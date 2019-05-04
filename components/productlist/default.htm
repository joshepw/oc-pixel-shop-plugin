{% if categoryList %}
<section class="products-navigation">
	<div class="container">
		{% if showCategoriesFilter %}
		
		{% if typeCategoriesFilter == 'dropdown' %}
		<div class="dropdown">
			<button id="btnGroupCategories" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fas fa-filter"></i> {{ 'pixel.shop::component.cart.filter_by_category'|trans }}
			</button>
			<ul class="dropdown-menu" aria-labelledby="btnGroupCategories">
				{% for category in categoryList if category.nest_depth == 0 %}
					<li class="{% if activeCategory.slug == category.slug %}active{% endif %}">
						<a href="{{ category.url }}" class="dropdown-item">{{ category.name }}</a>

						{% if category.children|length > 0 %}
						<ul class="sub-category">
							{% for item in categoryList if item.parent.id == category.id %}
							<li class="{% if activeCategory.slug == item.slug %}active{% endif %}">
								<a href="{{ item.url }}">{{ item.name }}</a>
							</li>
							{% endfor %}
						</ul>
						{% endif %}
					</li>
				{% endfor %}
			</ul>
		</div>
		{% else %}
		<div class="btn-group" role="group">
			{% for category in categoryList %}
				<a href="{{ category.url }}" class="btn btn-default {% if activeCategory.slug == category.slug %}active{% endif %}">{{ category.name }}</a>
			{% endfor %}
		</div>
		{% endif %}

		{% endif %}

		{% if showSearchBar %}
		<form method="get" class="products-search">
			<div class="input-group">
				<input name="search" type="text" class="form-control" placeholder="Search ..." aria-label="Search" value="{{ input('search') }}">
				<div class="input-group-append input-group-btn">
					<button class="btn btn-default" type="submit"><i class="fas fa-search"></i></button>
				</div>
			</div>
		</form>
		{% endif %}
	</div>
</section>
{% endif %}

<section class="products-container">
	<div class="container">

		{% for productGroup in __SELF__.products.chunk(4) %}
			<div class="row">
				{% for product in productGroup %}
					{% partial '@product' product=product %}
				{% endfor %}
			</div>
		{% else %}
			<div class="row">
				<div class="col-md-12">
					<div class="text-center">
						<i class="fas fa-search fa-6x"></i>
						<h1>{{ 'pixel.shop::component.cart.no_results'|trans }}</h1>
					</div>
				</div>
			</div>
		{% endfor %}
		
	</div>
</section>

<section class="products-pagination">
	<div class="container">
		{{ products.render|raw }}
	</div>
</section>
