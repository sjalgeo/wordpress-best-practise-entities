<?php

namespace Fresh_Core_WP_Test;

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

	public static function insert_from_amazon($title)
	{
		$postarr = array(
			'post_content' => $title.time(),
			'post_type' => 'fresh_product',
			'post_title' => $title . date('Y-m-d H:i:s')
		);

		$post_id = wp_insert_post( $postarr, true );

		$term_vendor = get_term_by('slug', 'amazon-usa', 'fresh_vendor')->term_id;


		wp_set_object_terms( $post_id, $term_vendor, 'fresh_vendor' );

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
}