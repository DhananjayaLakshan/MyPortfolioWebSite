<?php
namespace ElementPack\Modules\CharitableProfile;

use ElementPack\Base\Element_Pack_Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Element_Pack_Module_Base {

	public function get_name() {
		return 'charitable-profile';
	}

	public function get_widgets() {

		// $charitable_profile = element_pack_option('charitable-profile', 'element_pack_third_party_widget', 'off' );

		$widgets = ['Charitable_Profile'];

		// if ( 'on' === $charitable_profile ) {
		// 	$widgets[] = 'Charitable_Profile';
		// }

		return $widgets;
	}
}