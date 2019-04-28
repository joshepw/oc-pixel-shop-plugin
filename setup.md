## Installation

Before starting it is necessary to install the following plugins:

- [RainLab.User](https://octobercms.com/plugin/rainlab-user)
- [RainLab.Location](https://octobercms.com/plugin/rainlab-location)
- [Responsiv.Currency](https://octobercms.com/plugin/responsiv-currency)
- [RainLab.Pages (Recomendable)](https://octobercms.com/plugin/rainlab-pages)
- [RainLab.Translate (Recomendable)](https://octobercms.com/plugin/rainlab-translate)

Then we will begin to integrate the components to our current theme.

The integration of this plugin is very simple and we can finish it in a few steps. You can also download a sample theme from [here](http://demos.pixel.hn/october-plugins/shop/demo.zip)

## First Step

We make sure that in the layout we use always contain within the </ head> tag the twig tag `{% styles%}`, and before closing the <body /> tag the twig tags `{% framework extras%}` and `{% scripts %}`.


…
<head>
	…
	{% styles %}
</head>
<body>
	…
	 {% framework extras %}
	{% scripts %}
<body/>


Next we will integrate the `Cart Button` component in any part of the layout, this will help us to keep the button always present in any page of the site. We can also configure it to our liking, being able to change the text and background color of the botton.

![Layout](http://demos.pixel.hn/october-plugins/shop/layout.jpg)

## Second Step

We will create the following pages with their respective routes including the following components:

- `/profile` : Account
- `/cart/:step?` : Cart
- `/products/:category?` : Products
- `/product/:slug` : Product Detail

You can include the component inside a div container for better control.

The names of the routes are optional and can vary according to your taste but what can not vary are the names of the parameters of the routes (eg ... /: step?)

We make sure that all the configurations of each component are correct.
We have to take into account to link each component with its respective page.
Below is an example of the default settings of each component:

##### Account Component
Component that includes the login and registration functionalities. Once the user is logged in they can show all their data on this page.

![Account](http://demos.pixel.hn/october-plugins/shop/account.jpg)

##### Cart Component
This component includes all the functionalities of the final purchase, product summary, shipping information and billing.

![Cart](http://demos.pixel.hn/october-plugins/shop/cart.jpg)

##### Products Component
This component shows a list of all products, you can easily parameterize what products you want to display with a large number of filtering and sorting options.

![Products](http://demos.pixel.hn/october-plugins/shop/products.jpg)

##### Product Details Component
This component includes all the independent information and details of each product, including web libraries already integrated for gallery and zoom.

![Product Details](http://demos.pixel.hn/october-plugins/shop/product.jpg)

## Final Step

To finish that our shop is ready, the next thing is to go to our backend and configure the final details.

##### Sales Preferences
In this section we will make sure to configure the default image for the products, in case a product does not have images in its gallery.
We must make sure to enter all the details of our trade such as name, email and address as these will appear on the generated purchase receipts.
We will also enter all the existing tax types with their respective description, and then select these taxes when creating/modifying each product.

##### Payment Methods
Here we will configure the payment methods to accept, we can also limit different methods of payment per country. Be sure to enable the methods before you start using.

##### Countries & States
We will enable the countries and states where we will make shipments and configure shipping fees ​​depending on the place.

##### Currencies
Here we will enable the currencies to be used and we will define the default currency in our store.

## Start Selling
The next thing will be to start using the plugin by entering all the inventory in order to start making sales on our site and with that we have *finished*.
