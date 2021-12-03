# WP Asset v1.0

Simple WordPress class for including scripts and styles which is modifed from [WP_Register](https://github.com/Josantonius/WP_Register) 

<br>

## Requirements

* PHP >=7.2
* [Composer](https://getcomposer.org/)
* [WordPress](https://wordpress.org) >=5.4

<br>

## Installation

#### Install with composer

Run the following in your terminal to install with [Composer](https://getcomposer.org/).

```
$ composer require oberonlai/wp-asset
```

WP Option [PSR-4](https://www.php-fig.org/psr/psr-4/) autoloading and can be used with the Composer's autoloader. Below is a basic example of getting started, though your setup may be different depending on how you are using Composer.

```php
require __DIR__ . '/vendor/autoload.php';

use ODS\Asset;

Asset::addScript();

```

See Composer's [basic usage](https://getcomposer.org/doc/01-basic-usage.md#autoloading) guide for details on working with Composer and autoloading.

<br>

## Basic Usage

Below is a basic example of enqueue JavaScript and CSS.

```php
require __DIR__ . '/vendor/autoload.php';

use ODS\Asset;

Asset::addScript(
	array(
		'name'    => 'my_script',
		'url'     => YOUR_PLUGIN_URL . 'assets/js/script.js',
		'deps'    => array( 'jquery' ),
		'version' => YOUR_PLUGIN_VERSION,
		'footer'  => true,
		'ajax'    => false,
		'admin'   => false,
		'params'  => array()
	)
)

Asset::addStyle(
	array(
		'name'    => 'my_style',
		'url'     => YOUR_PLUGIN_URL . 'assets/css/style.css',
		'version' => YOUR_PLUGIN_VERSION,
		'deps'    => array(),
	)
)
```

<br>

## Avaiable Attributes

key | Description | Type | Required | Default
:--------------|:------------|:-----:|:----:|------------------------
name | Unique ID | string | Yes	
url	| Url to file |string | Yes	
admin | Attach in admin | boolean | No | false
deps | Dependences | array | No | 
version | Version | string | No | false
footer | Attach in footer ( scripts only ) | boolean | No | true
ajax | JS for Ajax ( scripts only ) | boolean | No | false
params | Params available in JS	array  ( scripts only ) | array | No |
media | Media ( styles only ) | string | No |

<br>

## Register JavaScript for Ajax

WP-Asset will help you generate variables ajax_url and ajax_nonce automatically when setting the ajax to true.

```php
Asset::addScript(
	array(
		'name'    => 'my_ajax',
		'url'     => YOUR_PLUGIN_URL . 'assets/js/ajax.js',
		'deps'    => array( 'jquery' ),
		'version' => YOUR_PLUGIN_VERSION,
		'footer'  => true,
		'ajax'    => true,
		'params'  => array(
			'data1'  => 'my_data_1',
			'data2'  => 'my_data_2',
		)
	)
)
```

<br>

After adding script above, you can see JS variables before the scripts.

```html
<script id="my_ajax-js-extra">
var my_ajax = {"data1":"my_data_1","data2":"my_data_2","ajax_url":"https:\/\/local.test\/wp-admin\/admin-ajax.php?action=my_ajax","ajax_nonce":"fead4137e4"};
</script>
```

<br>

You can use ajax_url and ajax_nonce directly in your scripts.

```js
jQuery(function($){
    $(document).ready(function(){ 
        $('#btn').on('click',function(){
			var data = {
				action: "my_ajax",
				nonce: my_ajax.ajax_nonce,
			};
			$.ajax({
				url: my_ajax.ajax_url,
				data: data,
				type: 'POST',
				dataType: "json",
				success: function(data){
					console.log(data);
				},
				error: function(data){
					console.log(data);
				}
			})
        })
    }) 
})
```