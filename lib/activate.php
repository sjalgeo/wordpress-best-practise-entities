<?php

namespace Fresh_Core_WP_Test\Controllers;

class Setup_Controller {

	public function run() {
		add_action( 'init', array( $this, 'setup_vendor_taxonomy' ) );
		add_action( 'init', array( $this, 'setup_product_group_taxonomy' ) );
		add_action( 'init', array( $this, 'create_product_post_type' ) );
		add_action( 'init', array( $this, 'create_terms' ) );
	}

	function setup_vendor_taxonomy() {

		if ( taxonomy_exists('fresh_vendor') ) return;

		// create a new taxonomy
		register_taxonomy(
			'fresh_vendor',
			'fresh_vendor',
			array(
				'label' => __( 'Fresh Vendors' ),
				'rewrite' => array( 'slug' => 'fresh_vendor' ),
				'capabilities' => array(
					'assign_terms' => 'edit_guides',
					'edit_terms' => 'publish_guides'
				)
			)
		);
	}


	function setup_product_group_taxonomy() {

		if ( taxonomy_exists('fresh_product_group') ) return;

		// create a new taxonomy
		register_taxonomy(
			'fresh_product_group',
			'fresh_product_group',
			array(
				'label' => __( 'Fresh Product Groups' ),
				'rewrite' => array( 'slug' => 'fresh_product_group' ),
				'capabilities' => array(
					'assign_terms' => 'edit_guides',
					'edit_terms' => 'publish_guides'
				)
			)
		);
	}


	function create_product_post_type() {
		register_post_type( 'fresh_product',
			array(
				'labels' => array(
					'name' => __( 'Products' ),
					'singular_name' => __( 'Product' )
				),
				'public' => true,
				'has_archive' => false,
			)
		);
	}

	function create_terms()
	{
		$term_exists = term_exists( 'amazon-usa', 'fresh_vendor' );

//		if ($term_exists) sja_debug('term_exists');
		$id = wp_insert_term(
			'Amazon USA', // the term
			'fresh_vendor' // the taxonomy
		);

		$id = wp_insert_term(
			'eBay', // the term
			'fresh_vendor' // the taxonomy
		);

		$id = wp_insert_term(
			'Amazon UK', // the term
			'fresh_vendor' // the taxonomy
		);

		$id = wp_insert_term(
			'Sony Bravia TV', // the term
			'fresh_product_group' // the taxonomy
		);

		$id = wp_insert_term(
			'iPad', // the term
			'fresh_product_group' // the taxonomy
		);
	}

}