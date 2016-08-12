<?php

function sja_register_sub_menus() {
	add_submenu_page( 'options-general.php', 'title', 'title',
		'manage_options', 'page-slug', 'display_page' );
}

add_action( 'admin_menu', 'sja_register_sub_menus' );

use Fresh_Core_WP_Test\Product_Manager;

function display_page()
{


	$id = Product_Manager::insert_from_amazon('ipad');
	Product_Manager::assign_to_group_by_slug($id, 'ipad');

	$usa = get_term_by( 'slug', 'amazon-usa', 'fresh_vendor');

	$posts_array = get_posts(
		array(
			'posts_per_page' => -1,
			'post_type' => 'fresh_product',
			'post_status' => 'any',
			'tax_query' => array(
				array(
					'taxonomy' => 'fresh_vendor',
					'field' => 'term_id',
					'terms' => $usa->term_id,
				)
			)
		)
	);

	?>

	<h1>Amazon USA</h1>

<?php
	foreach ($posts_array as $product)
	{ ?>

		<li><?php echo $product->ID . ' - ' . $product->post_title; ?></li>

	<?php }


	$usa = get_term_by( 'slug', 'amazon-uk', 'fresh_vendor');

	$posts_array = get_posts(
		array(
			'posts_per_page' => -1,
			'post_type' => 'fresh_product',
			'post_status' => 'any',
			'tax_query' => array(
				array(
					'taxonomy' => 'fresh_vendor',
					'field' => 'term_id',
					'terms' => $usa->term_id,
				)
			)
		)
	);

	?>

	<h1>Amazon UK</h1>

	<?php
	foreach ($posts_array as $product)
	{ ?>

		<li><?php echo $product->ID . ' - ' . $product->post_title; ?></li>

	<?php }

	$bravia = get_term_by( 'slug', 'sony-bravia-tv', 'fresh_product_group');

	$posts_array = get_posts(
		array(
			'posts_per_page' => -1,
			'post_type' => 'fresh_product',
			'post_status' => 'any',
			'tax_query' => array(
				array(
					'taxonomy' => 'fresh_product_group',
					'field' => 'term_id',
					'terms' => $bravia->term_id,
				)
			)
		)
	);

	?>

	<h1>Sony Bravia</h1>

	<?php
	foreach ($posts_array as $product)
	{ ?>

		<li><?php echo $product->ID . ' - ' . $product->post_title; ?></li>

	<?php }

	$ipad = get_term_by( 'slug', 'ipad', 'fresh_product_group');

	$posts_array = get_posts(
		array(
			'posts_per_page' => -1,
			'post_type' => 'fresh_product',
			'post_status' => 'any',
			'tax_query' => array(
				array(
					'taxonomy' => 'fresh_product_group',
					'field' => 'term_id',
					'terms' => $ipad->term_id,
				)
			)
		)
	);

	?>

	<h1>Ipad</h1>

	<?php
	foreach ($posts_array as $product)
	{ ?>

		<li><?php echo $product->ID . ' - ' . $product->post_title; ?></li>

	<?php }

}