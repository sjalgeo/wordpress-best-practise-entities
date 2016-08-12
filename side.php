<?php

function sja_register_sub_menus() {
	add_submenu_page( 'options-general.php', 'title', 'title',
		'manage_options', 'page-slug', 'display_page' );
}

add_action( 'admin_menu', 'sja_register_sub_menus' );

use Fresh_Core_WP_Test\Product_Manager;

use FreshAmazonClient\Request\Search;

function fetch_from_amazon() {


	Product_Manager::delete_all();

	$credentials = new \FreshAmazonClient\Credentials('AKIAJIBA25K5AQRIKYJQ', 'WiUnkQ3kZiRZwtBTPxwIu8Bhm15eV3WVu3sWvIs4', 'azn2020-20');


	$client = new \FreshAmazonClient\Client\WordpressClient();

	$search = new Search($credentials, \FreshAmazonClient\Locale\LocaleFactory::getUSA(),
		\FreshAmazonClient\ResponseGroup\ResponseGroupFactory::getStandardResponseGroup(), $client);


	$results = $search->itemSearch('funko');
	$results = $results->getBodyAsArray();
	$results = $results['Items']['Item'];

	foreach ($results as $product)
	{
		Product_Manager::insert_from_amazon($product);
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

		echo '<h3>'.$term->slug.'</h3>';

		foreach ($posts_array as $product)
		{
			echo '<li>';
			echo $product->ID . ' - ' . $product->post_title;
			echo '</li>';
		}
	}
}


