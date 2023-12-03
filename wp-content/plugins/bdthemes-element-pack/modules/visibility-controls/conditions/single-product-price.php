<?php
	
	namespace ElementPack\Modules\VisibilityControls\Conditions;
	
	use ElementPack\Base\Condition;
	use Elementor\Controls_Manager;
	use ElementPack\Includes\Controls\SelectInput\Dynamic_Select;
	
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly
	}
	
	class Single_Product_Price extends Condition {
		
		/**
		 * Get the name of condition
		 * @return string as per our condition control name
		 * @since  6.7.1
		 */
		public function get_name() {
			return 'single_product_price';
		}
		
		/**
		 * Get the title of condition
		 * @return string as per condition control title
		 * @since  6.7.1
		 */
		public function get_title() {
			return esc_html__( 'Single Product Price', 'bdthemes-element-pack' );
		}

		/**
		 * Get the group of condition
		 * @return string as per our condition control name
		 * @since  6.11.3
		 */
		public function get_group() {
			return 'woocommerce';
		}
		
		/* *
		 * Get the control value
		 * @return array as per condition control value
		 * @since  6.7.1
		 */
		public function get_control_value() {
			return [
				'label'			=> __( 'Equal or Heigher Than', 'bdthemes-element-pack' ),
				'type'			=> Controls_Manager::NUMBER,
				'min'			=> 0,
				'default'		=> 50,
				'description'	=> __( 'Set zero(0) to check empty price.', 'bdthemes-element-pack' ),
			];
		}

		
		/**
		 * Check the condition
		 * @param string $relation Comparison operator for compare function
		 * @param mixed $val will check the control value as per condition needs
		 * @since 6.7.1
		 */
		public function check( $relation, $val ) {
			
			$post_id	= get_queried_object_id();
			$post_type	= get_post_type();

			if (( '' === $val ) or ( 'product' !== $post_type ) or ( ! $post_id )) {
				return false;
			}

			$product = wc_get_product( $post_id );

			$price = $product->get_price();

			if ( 0 === (int) $val ) {
				$show = (int) $val === $price ? true : false;
			} else {
				$show = (int) $val <= $price ? true : false;
			}
			
			return $this->compare( $show, true, $relation );
		}
	}

		
