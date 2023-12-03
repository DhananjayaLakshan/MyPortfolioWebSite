<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     
 * @link       https://phlox.pro
 * @copyright  (c) 2010-2023 
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit( 'No Naughty Business Please !' );
}
