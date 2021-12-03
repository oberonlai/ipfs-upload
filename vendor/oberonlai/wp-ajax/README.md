# WP Ajax v1.0

Simple WordPress Class for ajax which forked from [WP_AJAX](https://github.com/anthonybudd/WP_AJAX)

## Requirements

* PHP >=7.2
* [Composer](https://getcomposer.org/)
* [WordPress](https://wordpress.org) >=5.4

## Installation

#### Install with composer

Run the following in your terminal to install with [Composer](https://getcomposer.org/).

```
$ composer require oberonlai/wp-ajax
```

WP Ajax [PSR-4](https://www.php-fig.org/psr/psr-4/) autoloading and can be used with the Composer's autoloader. Below is a basic example of getting started, though your setup may be different depending on how you are using Composer.

```php
require __DIR__ . '/vendor/autoload.php';

use ODS\Ajax;

class MyAjax extends Ajax { ... }

```

See Composer's [basic usage](https://getcomposer.org/doc/01-basic-usage.md#autoloading) guide for details on working with Composer and autoloading.

## Basic Usage

Below is a basic example of setting up an ajax.

```php

require __DIR__ . '/vendor/autoload.php';

use ODS\Ajax;

Class ExampleAction extends WP_AJAX{

    protected $action = 'example-action';

    protected function run(){

    	// Your Code Here!
    	
    	update_option('name', $this->get('name'));

    }
}
ExampleAction::listen(); // Don't forget this line.

ExampleAction::url() // http://example.com/wp-admin/admin-ajax.php?action=example-action
ExampleAction::url(['name' => 'Anthony Budd']) // http://example.com/wp-admin/admin-ajax.php?action=example-action&name=Anthony%20Budd
```



## Helper Methods

```php
Example::url() // Returns the url of the ajax endpoint. Example http://ajax.local/wp/wp-admin/admin-ajax.php?action=example

$this->isLoggedIn(); // Returns TRUE or FALSE if the current visitor is a logged in user.

$this->has($key); // has() will return TRUE or FALSE if an element exists in the $_REQUEST array with a key of $key

$this->get($key, [ $default = NULL ]); // The get() method will return the specified HTTP request variable. If the variable does not exist it will return NULL by default. If you would like to set a custom string as the default, provide it as the second argument.

$this->requestType(); // Returns 'PUT', 'POST', 'GET', 'DELETE' depending on HTTP request type

$this->requestType('POST'); // Returns (bool) 

$this->requestType(['POST', 'PUT']); // Returns (bool)  
```

## Example

Nonce is very important parameter when using Ajax. Below is a practical example of updating post title via ajax.

```php

use ODS\Asset;
use ODS\Ajax;

Asset::addScript(
	array(
		'name'    => 'my_ajax', // It should be the same with Ajax action name.
		'url'     => ODS_PLUGIN_URL . 'assets/js/ajax.js',
		'deps'    => array( 'jquery' ),
		'version' => ODS_VERSION,
		'ajax'    => true,
		'params'  => array(
			'data1' => 'my_data_1',
			'data2' => 'my_data_2',
		),
	),
);

class MyAjax extends Ajax {
	protected $action = 'my_ajax'; // It should be the same with JS name.
	protected function run() {
		
        $nonce = $this->get( 'nonce' );
        $data1 = $this->get( 'data1' );
        $data2 = $this->get( 'data2' );
		
        // Don't forget verify nonce.
        if ( ! wp_verify_nonce( $nonce, 'my_ajax' ) ) {
			$this->JSONResponse( __( 'Invalid', 'my-plugin' ) );
			exit;
		}
		$url     = wp_get_referer();
		$post_id = url_to_postid( $url );
		$my_post = array(
			'ID'         => $post_id,
			'post_title' => 'This is the 123',
		);
		$book_id = wp_update_post( $my_post );
		$this->JSONResponse( $post_id );
	}
}
MyAjax::listen();
```

And the JS part:

```js
jQuery(function($){
    $(document).ready(function(){
        var btn = $('#btn')
        btn.on('click',function(){
			var data = {
				action: "my_ajax", // It should be the same with Ajax action name.
				nonce: my_ajax.ajax_nonce,
				data1: my_ajax.data1,
				data2: my_ajax.data2,
			};
			$.ajax({
				url: my_ajax.ajax_url,
				data: data,
				type: 'POST',
				dataType: "json",
				success: function(data){
					alert(data);
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

More infomation about class Asset -  https://github.com/oberonlai/wp-asset