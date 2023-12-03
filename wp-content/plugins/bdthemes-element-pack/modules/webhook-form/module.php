<?php
namespace ElementPack\Modules\WebhookForm;

use ElementPack\Base\Element_Pack_Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Module extends Element_Pack_Module_Base {

	public function get_name() {
		return 'webhook-form';
	}

	public function get_widgets() {

		$widgets = [ 'Webhook_Form' ];

		return $widgets;
	}

	public function __construct() {
		parent::__construct();
		add_action( 'wp_ajax_nopriv_submit_webhook_form', array( $this, 'submit_webhook_form' ) );
		add_action( 'wp_ajax_submit_webhook_form', array( $this, 'submit_webhook_form' ) );
	}

	public function submit_webhook_form() {

		if ( ! wp_verify_nonce( $_POST['nonce'], 'element-pack-site' ) ) {
			echo json_encode( array(
				'success' => false,
				'message' => esc_html__( 'Nonce verification failed', 'bdthemes-element-pack' ),
			) );
			wp_die();
		}

		$widget_id       = sanitize_text_field( $_REQUEST['widget_id'] );
		$transient_key   = 'bdt_ep_webhook_form_data_' . $widget_id;
		$transient_value = get_transient( $transient_key );

		$form_data = array();

		foreach ( $_POST as $field => $value ) {
			if ( is_email( $value ) ) {
				$value = sanitize_email( $value );
			} else {
				$value = sanitize_textarea_field( $value );
			}

			$form_data[ $field ] = strip_tags( $value );
		}

		unset( $form_data['action'] );
		unset( $form_data['nonce'] );

		if ( isset( $form_data['widget_id'] ) ) {
			unset( $form_data['widget_id'] );
		}

		$headers = array();

		if ( ! empty( $transient_value['header'] ) ) {
			$headers = array_merge( $headers, $transient_value['header'] );
		}

		if ( ! empty( $transient_value['body'] ) ) {
			$form_data = array_merge( $form_data, $transient_value['body'] );
		}

		$hook_url = $transient_value['webhook_url'];

		if ( empty( $hook_url ) ) {
			echo json_encode( array(
				'success' => false,
				'message' => esc_html__( 'Webhook URL empty.', 'bdthemes-element-pack' ),
			) );
			wp_die();
		}

		$updated_url = str_replace( "&#038;", "&", $hook_url );

		$response = wp_remote_post( $updated_url, array(
			'headers' => $headers,
			'body'    => $form_data,
		) );

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			echo json_encode( array(
				'success' => false,
				'message' => esc_html__( $error_message, 'bdthemes-element-pack' ),
			) );
		} else {
			echo json_encode( array(
				'success' => true,
				'message' => esc_html__( 'Your data has been sent successfully.', 'bdthemes-element-pack' ),
			) );
		}

		wp_die();
	}

}
