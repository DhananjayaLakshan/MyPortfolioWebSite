<?php

namespace Depicter\Rules\Conditions;

use WP_Post_Type;
use WP_Taxonomy;

class ListConditions
{


	public function wordpressConditions(): array{
		return [
			'label' => __( "Wordpress", 'depicter' ),
			"items" => [
				[
					"label" => __( "A WP Post", 'depicter' ),
					"slug"	=> "post_id",
					"query" => "wp:post:all",
					"control" => "multiselect",
					"value" => []
				],
				[
					"label" => __( "A WP Page", 'depicter' ),
					"slug"	=> "post_id",
					"query" => "wp:page:all",
					"control" => "multiselect",
					"value" => []
				],
				[
					"label" => __( "Any WP Post", 'depicter' ),
					"slug"	=> "wp:is_single",
					"description" => __( "When any single Post is being displayed. This condition is false if you are on a page.", 'depicter' ),
					"control" => "dropdown",
					"value" => [
						"label" => __( "Any WP Post", 'depicter' ),
						"value" => true
					]
				],
				[
					"label" => __( "Any WP Page", 'depicter' ),
					"slug"	=> "wp:is_page",
					"description" => __( "When any page is being displayed, not a blog post nor a generic page from any other custom post type.", 'depicter' ),
					"control" => "dropdown",
					"value" => [
						"label" => __( "Any WP Page", 'depicter' ),
						"value" => true
					]
				],
				[
					"label" => __( "WP Archive Page", 'depicter' ),
					"slug"	=> "wp:is_archive",
					"description" => __( "When any type of Archive page is being displayed. Category, Tag, Author and Date based pages are all types of Archives.", 'depicter' ),
					"control" => "dropdown",
					"value" => [
						"label" => __( "WP Archive", 'depicter' ),
						"value" => true
					]
				],
				[
					"label" => __( "WP Static Pages", 'depicter' ),
					"slug"	=> "wp:static",
					"description" => "",
					"control" => "multiselect",
					"value" => [
						[
							"label" => __( "Home Page", 'depicter' ),
							"value" => "is_home"
						],
						[
							"label" => __( "404 Page", 'depicter' ),
							"value" => "is_404"
						],
						[
							"label" => __( "Search Page", 'depicter' ),
							"value" => "is_search"
						],
						[
							"label" => __( "Blog Page", 'depicter' ),
							"value" => "is_blog"
						],
						[
							"label" => __( "Privacy Policy page", 'depicter' ),
							"value" => "is_privacy_policy"
						],
					]
				],
				[
					"label" => __( "WP Category Page", 'depicter' ),
					"slug"	=> "wp:is_category",
					"description" => __( "When a Category archive page is being displayed.", 'depicter' ),
					"control" => "multiselect",
					"query" => "wp:category:all",
					"value" => []
				],
				[
					"label" => __( "WP Tag Page", 'depicter' ),
					"slug"	=> "wp:is_tag",
					"description" => __( "When any Tag archive page is being displayed.", 'depicter' ),
					"control" => "multiselect",
					"query" => "wp:tag:all",
					"value" => []
				],
				[
					"label" => __( "In WP Category", 'depicter' ),
					"slug"	=> "wp:in_category",
					"description" => __( "If displayed post is in the specified category.", 'depicter' ),
					"control" => "multiselect",
					"query" => "wp:category:term_list",
					"value" => []
				],
				[
					"label" => __( "Has WP Tag", 'depicter' ),
					"slug"	=> "wp:has_tag",
					"description" => __( "If displayed post has a specified tag.", 'depicter' ),
					"control" => "multiselect",
					"query" => "wp:post_tag:term_list",
					"value" => []
				],
				[
					"label" => __( "WP Author Page", 'depicter' ),
					"slug"	=> "wp:is_author",
					"description" => __( "When any Author page is being displayed.", 'depicter' ),
					"control" => "multiselect",
					"query" => "wp:authors:all",
					"value" => []
				],
			]
		];
	}

	public function customPostTypeConditions(): array{
		/**
		 * @var WP_Post_Type[] $postTypes
		 */
		$postTypes = get_post_types([
			                            'public' => true,
			                            '_builtin' => false
		                            ], 'object');


		$items = [];
		$taxonomyItems = [];

		$singlePageValue = [
			[
				'label' => __( 'All Single Pages', 'depicter' ),
				'value' => 'all'
			]
		];
		$archivePageValue = [];
		$taxonomyPageValue = [
			[
				'label' => __( 'Any', 'depicter' ),
				'value' => ""
			]
		];

		foreach ( $postTypes as $postType ) {

			if ( $postType->name == 'product' ) {
				continue;
			}

			$singlePageValue[] = [
				'label' => sprintf( __( "%s Single Page", 'depicter' ), $postType->labels->singular_name  ),
				'value' => $postType->name
			];

			$items[] = [
				'label' => sprintf( __( "%s Page", 'depicter' ), $postType->labels->singular_name  ),
				'slug' => "wp:post_id",
				"query" => "wp:" . $postType->name . ":all",
				"control" => "multiselect",
				"value" => []
			];

			$archivePageValue[] = [
				'label' => sprintf( __( '%s Archive', 'depicter' ), $postType->labels->singular_name ),
				'value' => $postType->name
			];

			/**
			 * @var WP_Taxonomy[] $taxonomies
			 */
			$taxonomies = get_taxonomies([
				                             'object_type' => [ $postType->name ],
				                             'public'      => true,
				                             'show_ui'     => true,
			                             ], 'object');

			if ( ! empty( $taxonomies ) ) {
				foreach( $taxonomies as $taxonomy ) {
					$taxonomyPageValue[] = [
						'label' => $taxonomy->label,
						"value" => $taxonomy->name
					];

					$taxonomyItems[] = [
						"label" => $taxonomy->label,
						"slug" => "wp:term_id",
						"query" => "wp:" . $taxonomy->name . ":term_list",
						"control" => "multiselect",
						"value" => []
					];
				}
			}
		}

		array_unshift( $items, [
			"label" => __( "Single Page", 'depicter'),
			"slug" => "wp:is_singular",
			"control" => "multiselect",
			"value" => $singlePageValue
		]);


		$items[] = [
			'label' => __( "Archive Page", 'depicter' ),
			'slug' => "wp:is_post_type_archive",
			"control" => "multiselect",
			"value" => $archivePageValue
		];

		if ( !empty( $taxonomyItems ) ) {
			$items[] = [
				'label' => __( "Taxonomy Page", 'depicter' ),
				'slug' => "wp:is_tax",
				"description" => __( "When a Taxonomy archive page for specific taxonomy is being displayed.", 'depicter' ),
				"control" => "multiselect",
				"value" => $taxonomyPageValue
			];

			$items = array_merge( $items, $taxonomyItems );
		}

		return [
			"label" => __( "Custom Post Types", 'depicter' ),
			"items" => $items
		];
	}

	public function woocommerceConditions() {

	}
	public function list(): array{

		$conditions = [];

		$conditions['wordpress'] = $this->wordpressConditions();

		$conditions['customPostTypeConditions'] = $this->customPostTypeConditions();

		return $conditions;;
	}
}
