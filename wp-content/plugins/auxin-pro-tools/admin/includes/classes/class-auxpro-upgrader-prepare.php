<?php

class AUXPRO_Upgrader_Prepare extends Auxin_Upgrader_Prepare {

    /**
     * Instance of this class.
     *
     * @var      object
     */
    protected static $instance = null;

    protected $api = 'https://api.averta.net/envato/items/';

    protected $api_request = null;


    public function __construct(){

        add_filter( 'site_transient_update_plugins', array( $this, 'disable_update_plugins' ) );
        add_filter( 'site_transient_update_themes',  array( $this, 'disable_update_themes'  ) );
        
        add_action( 'admin_init', [ $this, 'init_api_request_instance' ] );
        add_filter ( 'pre_set_site_transient_update_themes', [ $this, 'pre_set_transient_update_theme' ] );
        add_filter( 'auxin_before_setting_update_themes_transient', [ $this, 'update_themes_transient' ], 10, 2 );
        add_filter( 'auxin_before_setting_update_plugins_transient', [ $this, 'update_plugins_transient' ], 10, 2 );

        add_filter( 'auxin_modify_package_before_upgrade', [ $this, 'modify_package' ] );
    }

    /**
     * Return an instance of this class.
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Remove auxin plugins from wp auto update
     *
     * @return object
     */
    public function disable_update_plugins( $transient ) {
        // Pass plugins list with their slug e.g. array( 'auxin-elements' )
        $plugins = apply_filters( 'auxin_disable_plugins_updates', array() );
        if ( isset($transient) && is_object($transient) && ! empty( $plugins ) ) {
            foreach ( $plugins as $key => $plugin ) {
                $plugin_path = $plugin . '/' . $plugin . '.php';
                if ( isset( $transient->response[$plugin_path] ) ) {
                    unset( $transient->response[$plugin_path] );
                }
            }
        }
        return $transient;
    }

    /**
     * Remove auxin themes from wp auto update
     *
     * @return object
     */
    public function disable_update_themes( $transient ) {
        // Pass themes list with their slug e.g. array( 'phlox' )
        $themes = apply_filters( 'auxin_disable_themes_updates', array() );
        if ( isset($transient) && is_object($transient) && ! empty( $themes  ) ) {
            foreach ( $themes as $theme ) {
                if ( isset( $transient->response[ $theme ] ) ) {
                    unset( $transient->response[ $theme ] );
                }
            }
        }
        return $transient;
    }

    /**
     * Initialize api request upgrader
     *
     * @return void
     */
    public function init_api_request_instance() {
        $this->api_request = new AUXPRO_Upgrader_Http_Api();
    }

    /**
     * General remote get function
     *
     * @param array $request_args
     * @return void
     */
    public function remote_get( $request_args ){
        $url = add_query_arg(
            $request_args,
            $this->api
        );

        $request = wp_remote_get( $url );

        if ( is_wp_error( $request ) ) {
            return false;
        }

        return wp_remote_retrieve_body( $request );
    }

    /**
     * Check theme versions against the latest versions hosted on Averta API
     *
     * @return object $new_option
     */
    public function update_themes_transient( $new_option, $themes ) {
        foreach ( $themes as $slug => $data ) {

            if( !$data->isOfficial ) {
                // Get version number of our api
                $new_version = $this->remote_get( array(
                    'cat'       => 'version-check',
                    'action'    => 'final',
                    'item-name' => sanitize_key( $slug )
                ) );

                if( ! empty( $new_version ) && version_compare( $new_version, $data->get( 'Version' ), '>' ) ){
                    $new_option->response[ $data->get_stylesheet() ] = array(
                        'slug'    => esc_sql($slug),
                        'version' => esc_sql($new_version),
                        'package' => 'AUXIN_GET_DOWNLOAD_URL'
                    );
                }
            }

        }

        return $new_option;
    }

    /**
     * Check plugin versions against the latest versions hosted on Averta API
     *
     * @return object $new_option
     */
    public function update_plugins_transient( $new_option, $plugins ) {
        
        foreach ( $plugins as $path => $data ) {
            $slug = dirname( $path );

            if( !$data['isOfficial'] ) {
                
                // Get version number of our api
                $new_version = $this->remote_get( array(
                    'cat'       => 'version-check',
                    'action'    => 'final',
                    'item-name' => sanitize_key( $slug )
                ) );

                if( ! empty( $new_version ) && version_compare( $new_version, $data['Version'], '>' ) ){
                    $new_option->response[ $path ] = array(
                        'slug'    => esc_sql($slug),
                        'version' => esc_sql($new_version),
                        'package' => 'AUXIN_GET_DOWNLOAD_URL'
                    );
                }
            }
        }

        return $new_option;
    }

    /**
     * Upgrade theme through wordpress built in upgrader system
     *
     * @param object $transient
     * @return object $transient
     */
    public function pre_set_transient_update_theme( $transient ) {
        
        if( empty( $transient->checked ) ) {
            return $transient;
        }

        $get_themes   = $this->get_themes();
        $api_request  = new AUXPRO_Upgrader_Http_Api;
        foreach ( $get_themes as $slug => $data ) {

            if( !$data->isOfficial ) {

                // Get version number of our api
                $new_version = $this->remote_get( array(
                    'cat'       => 'version-check',
                    'action'    => 'final',
                    'item-name' => sanitize_key( $slug )
                ) );

                if( ! empty( $new_version ) && version_compare( $new_version, $data->get( 'Version' ), '>' ) ){
                    $downlaod_link = $api_request->get_download_link( $slug );
                    if( is_wp_error( $downlaod_link ) ){
                        continue;
                    }
                    $transient->response[ $data->get_stylesheet() ] = array(
                        'slug'    => esc_sql($slug),
                        'version' => $data->get( 'Version' ),
                        'new_version' => esc_sql($new_version),
                        'package' => $downlaod_link
                    );
                }
            }

        }
    
        return $transient;
    }


    /**
     * Modify package url of premium plugins
     *
     * @param array $r
     * @return array $r
     */
    public function modify_package( $r ) {

        if( ! wp_http_validate_url( $r['package'] ) && $r['package'] == 'AUXIN_GET_DOWNLOAD_URL' ){
            $r['slug'] =  ( $r['slug'] == 'masterslider' ) ? 'masterslider-wp' : $r['slug'];
            $downlaod_link = $this->api_request->get_download_link( $r['slug'] );
            if( ! is_wp_error( $downlaod_link ) ){
                $r['package'] = $downlaod_link;
            }
        }

        return $r;
    }
}