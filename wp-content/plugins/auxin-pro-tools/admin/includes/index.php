<?php // load admin related classes & functions


AUXPRO_Upgrader_Prepare::get_instance();

// load admin related functions
include_once( 'admin-the-functions.php' );

// load admin related classes
include_once( 'classes/class-auxpro-admin-assets.php'  );

do_action( 'auxpro_admin_classes_loaded' );

// load metaboxes here

// load admin related functions
include_once( 'admin-hooks.php' );
