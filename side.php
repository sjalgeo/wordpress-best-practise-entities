<?php

function sja_register_sub_menus() {
	add_submenu_page( 'options-general.php', 'title', 'title',
		'manage_options', 'page-slug', 'display_page' );
}

//add_action( 'admin_menu', 'sja_register_sub_menus' );

use Fresh_Core_WP_Test\Product_Manager;

use FreshAmazonClient\Request\Search;

function fetch_from_amazon() {

//	Product_Manager::delete_all();
//	return;

	$api_key = get_option('FRESH_AMAZON_KEY');
	$api_secret = get_option('FRESH_AMAZON_SECRET');
	$api_tag = get_option('FRESH_AMAZON_TAG');

	$credentials = new \FreshAmazonClient\Credentials($api_key, $api_secret, $api_tag);

	$locale = \FreshAmazonClient\Locale\LocaleFactory::getUSA();
	$client = new \FreshAmazonClient\Client\WordpressClient();

	$search = new Search($credentials, $locale,
		\FreshAmazonClient\ResponseGroup\ResponseGroupFactory::getStandardResponseGroup(), $client);


	$results = $search->itemSearch('funko game of thrones', rand(1, 5) );
//	sja_debug($results);
	$results = $results->getBodyAsArray();
	$results = $results['Items']['Item'];
//	sja_debug($results);

	foreach ($results as $product)
	{
		Product_Manager::insert_from_amazon($product, $locale);
	}



//	sja_debug($results);

}


function display_page()
{

	fetch_from_amazon();




	echo '<h1>Summary</h1>';

	$terms = get_terms( array(
		'taxonomy' => array('fresh_vendor', 'fresh_product_group'),
		'hide_empty' => false,
	) );



	foreach ($terms as $term)
	{
		$posts_array = get_posts(
			array(
				'posts_per_page' => -1,
				'post_type' => 'fresh_product',
				'post_status' => 'any',
				'tax_query' => array(
					array(
						'taxonomy' => $term->taxonomy,
						'field' => 'term_id',
						'terms' => $term->term_id,
					)
				)
			)
		);

		if (sizeof($posts_array) === 0) continue;

//		sja_debug($posts_array);

		echo '<h3>'.$term->slug.'(' . sizeof($posts_array) . ')</h3>';

		foreach ($posts_array as $product)
		{
			echo '<li>';
			echo $product->ID . ' - ' . $product->post_title;
			echo get_post_meta($product->ID, 'price', true);
			echo '<a href="' . get_post_meta($product->ID, 'detail_page_url', true) . '" target="_blank">link</a>';
			echo "\r\n".'<img src="' . get_post_meta($product->ID, 'large_image', true) . '" />';
			echo '</li>';
		}
	}
}


