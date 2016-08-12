<?php

namespace Fresh_Core_WP_Test;

use \WP_Query;

class Product_Manager {

	public static function delete_all()
	{
		$posts_array = get_posts(
			array(
				'posts_per_page' => -1,
				'post_type' => 'fresh_product',
				'post_status' => 'any'
			)
		);

		foreach ($posts_array as $post)
		{
			wp_delete_post( $post->ID, true);
		}
	}

	public static function insert_from_amazon($raw_data, Locale $locale)
	{
		$data = Product_Manager::get_by_asin($raw_data['ASIN']);

		if ( sizeof($data->posts) ) return;

		$product = array(
//			'post_content' => $title.time(),
			'post_type' => 'fresh_product',
			'post_title' => $raw_data['ItemAttributes']['Title']
		);

		$post_id = wp_insert_post( $product, true );

		$term_vendor = get_term_by('slug', 'amazon-usa', 'fresh_vendor')->term_id;

		wp_set_object_terms( $post_id, $term_vendor, 'fresh_vendor' );

		add_post_meta( $post_id, 'vendor_id', $raw_data['ASIN'] );
		add_post_meta( $post_id, 'title', $raw_data['ItemAttributes']['Title']);
		add_post_meta( $post_id, 'detail_page_url', $raw_data['DetailPageURL']);

		return $post_id;
	}

	public static function assign_to_group_by_slug($product_post_id, $group_slug)
	{
		$term_group = get_term_by('slug', $group_slug, 'fresh_product_group')->term_id;
		$term_taxonomy_ids = wp_set_object_terms( $product_post_id, $term_group, 'fresh_product_group' );
	}

	public static function assign_to_group_by_id($product_post_id, $group_id)
	{
		$term_taxonomy_ids = wp_set_object_terms( $product_post_id, $group_id, 'fresh_product_group' );
	}

	public static function get_products_by_price()
	{
		$args = array(
			'post_type' => 'fresh_product',
					'order'     => 'DESC',
//			'order'     => 'ASC',
			'orderby'   => 'meta_value_num',
			'meta_key'  => 'price',
		);
		return new WP_Query( $args );
	}

	public static function get_by_asin($asin)
	{
		$args = array(
			'post_type'  => 'fresh_product',
//			'meta_key'   => 'age',
//			'orderby'    => 'meta_value_num',
//			'order'      => 'ASC',
			'meta_query' => array(
				array(
					'key'     => 'vendor_id',
					'value'   => $asin,
				),
			),
		);
		return new WP_Query( $args );
	}
}