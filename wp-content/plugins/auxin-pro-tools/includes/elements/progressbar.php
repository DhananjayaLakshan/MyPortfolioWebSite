<?php
/**
 * Progressbar element
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     
 * @link       https://phlox.pro
 * @copyright  (c) 2010-2023 
 */
function auxin_get_progressbar_master_array( $master_array ) {

    $master_array['aux_progressbar'] = array(
        'name'                    => __('Progressbar ', PLUGIN_DOMAIN ),
        'auxin_output_callback'   => 'auxin_widget_progressbar_callback',
        'base'                    => 'aux_progressbar',
        'description'             => __('Progressbar Widget', PLUGIN_DOMAIN ),
        'class'                   => 'aux-widget-progressbar',
        'show_settings_on_create' => true,
        'weight'                  => 1,
        'is_widget'               => false,
        'is_shortcode'            => true,
        'category'                => THEME_NAME,
        'group'                   => '',
        'admin_enqueue_js'        => '',
        'admin_enqueue_css'       => '',
        'front_enqueue_js'        => '',
        'front_enqueue_css'       => '',
        'icon'                    => 'aux-element auxicon-loading',
        'custom_markup'           => '',
        'js_view'                 => '',
        'html_template'           => '',
        'deprecated'              => '',
        'content_element'         => '',
        'as_parent'               => '',
        'as_child'                => '',
        'params'                  => array(
            array(
                'heading'           => __('Title',PLUGIN_DOMAIN ),
                'description'       => __('Progressbar title, leave it empty if you don`t need title.', PLUGIN_DOMAIN),
                'param_name'        => 'title',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => 'textfield',
                'class'             => 'title',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Text',PLUGIN_DOMAIN ),
                'description'       => __('Progressbar text, leave it empty if you don`t need title.', PLUGIN_DOMAIN),
                'param_name'        => 'prog_text',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => 'textfield',
                'class'             => 'prog_text',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
           array(
                'heading'           => __('Display Icon', PLUGIN_DOMAIN ),
                'description'       => __('Display icon for text', PLUGIN_DOMAIN ),
                'param_name'        => 'display_icon',
                'type'              => 'checkbox',
                'def_value'         => '',
                'value'             => '',
                'class'             => 'display_icon',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Icon for text',PLUGIN_DOMAIN ),
                'description'       => '',
                'param_name'        => 'text_icon',
                'type'              => 'aux_iconpicker',
                'value'             => '',
                'class'             => 'icon-name',
                'admin_label'       => false,
                'dependency'        => array(
                    'element' => 'display_icon',
                    'value'   => array('1', 'true'),
                ),
                'weight'            => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Value',PLUGIN_DOMAIN),
                'description'       => __('Value of Progressbar in Percentage.', PLUGIN_DOMAIN),
                'param_name'        => 'prog_value',
                'type'              => 'textfield',
                'value'             => '50',
                'def_value'         => '50',
                'holder'            => '',
                'class'             => 'prog_value',
                'admin_label'       => false,
                'weight'            => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Progressbar Background Height',PLUGIN_DOMAIN),
                'description'       => __('Height of background progressbar in pixel.', PLUGIN_DOMAIN),
                'param_name'        => 'prog_bg_height',
                'type'              => 'textfield',
                'value'             => '4',
                'def_value'         => '4',
                'holder'            => '',
                'class'             => 'prog_bg_height',
                'admin_label'       => false,
                'weight'            => '',
                'edit_field_class'  => ''
            ),

            array(
                'heading'           => __('Progressbar Inner Height',PLUGIN_DOMAIN),
                'description'       => __('Height of Inner progressbar in pixel.', PLUGIN_DOMAIN),
                'param_name'        => 'prog_in_height',
                'type'              => 'textfield',
                'value'             => '4',
                'def_value'         => '4',
                'holder'            => '',
                'class'             => 'prog_in_height',
                'admin_label'       => false,
                'weight'            => '',
                'edit_field_class'  => ''
            ),

        )
    );


    return $master_array;
}

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_progressbar_master_array', 10, 1 );


/**
 * The front-end output of this element is returned by the following function
 *
 * @param  array  $atts              The array containing the parsed values from shortcode, it should be same as defined params above.
 * @param  string $shortcode_content The shorcode content
 * @return string                    The output of element markup
 */
function auxin_widget_progressbar_callback( $atts, $shortcode_content = null ){

    // Defining default attributes
    $default_atts = array(
        'title'             => '', // header title
        'prog_text'         => '',
        'display_icon'      =>  'off',
        'text_icon'         =>  '',
        'prog_value'        => '50',
        'prog_bg_height'     => '4',
        'prog_in_height'     => '4',
        'prog_bg_color_stop1' => '',
        'prog_bg_color_stop2' => '',
        'prog_bg_color_type'=>'',
        'prog_bg_color_value'=>'',
        'prog_bg_sec_color_grad' => '',
        'prog_bg_grad_angle'    => '',
        'prog_in_color_stop1' => '',
        'prog_in_color_stop2' => '',
        'prog_in_color_type'=>'',
        'prog_in_color_value'=>'',
        'prog_in_sec_color_grad' => '',
        'prog_in_grad_angle'   => '',
        'extra_classes'     => '', // custom css class names for this element
        'base_class'        => 'aux-widget-progressbar'  // base class name for container
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );

    $svg_extra_class = '';

    $top = ( $prog_bg_height - $prog_in_height ) / 2 ;
    $prog_in_style = "top:" . $top . "px;width:" . $prog_value['size'] . "%;height:" . $prog_in_height . "px;";
    $bg_prog_style = "height: " . $prog_bg_height . "px;";

    // widget header ------------------------------
    $output  = $result['widget_header'];
    $output .= $result['widget_title'];
    
    // Start Wrapper
    $output .= '<div class="aux-progressbar-container">';

    // Icon and Text Markup
    $output .= ! empty( $prog_text ) ? sprintf( '<h4 class="aux-progressbar-title"><i class="aux-progressbar-icon %s"></i>%s</h4>', $text_icon, $prog_text ) : '';

    // Progressbar SVG Markup 
    if ( ! empty( $prog_value['size'] ) ) {
        $output .= sprintf( '<div class="aux-progressbar-svg %s">', $svg_extra_class );

        // Value Markup 
        $output .= '<span class="aux-progressbar-value" style="left:' . $prog_value['size'] . '%">' . $prog_value['size'] . '%</span>';

        $output .= "<div class='aux-outer-background' style='$bg_prog_style'><div class='aux-inner-background' style='$prog_in_style'></div></div>";
        $output .= '</div>';
    }

    //End Wrapper
    $output .= '</div>';

    $output .= $result['widget_footer'];

    // widget footer ------------------------------

    return $output;

}