<?php
/*
Plugin Name: CMB2 Field Type: Leaflet Maps
Plugin URI: https://github.com/berio/cmb_field_leafletmap
GitHub Plugin URI: https://github.com/berio/cmb_field_leafletmap
Description: Leaflet Maps field type for CMB2.
Version: 2.2.0
Author: Berio Molina
Author URI: https://beriomolina.com/
License: GPLv2+
*/

/**
 * Class LAULO_CMB2_Field_Leaflet_Maps.
 */
class LAULO_CMB2_Field_Leaflet_Maps {

	/**
	 * Current version number.
	 */
	const VERSION = '1.6.0';

	/**
	 * Initialize the plugin by hooking into CMB2.
	 */
	public function __construct() {
		add_filter( 'cmb2_render_pw_map', array( $this, 'render_pw_map' ), 10, 5 );
		add_filter( 'cmb2_sanitize_pw_map', array( $this, 'sanitize_pw_map' ), 10, 4 );
    add_action( 'wp_enqueue_scripts', array($this, 'setup_public_scripts') );
	}

	/**
	 * Render field.
	 */
	public function render_pw_map( $field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object ) {

		$this->setup_admin_scripts( );

		echo '<input type="text" class="large-text pw-map-search search-coordinates-input" id="' . $field->args( 'id' ) . '" />';
		echo '<p class="cmb2-metabox-description">';
		echo __('Write coordinates separated by comma, like: 42.599622, -7.726531', '');
		echo '</p>';
		echo '<p><button class="button" type="button" id="search_coordinates">Zoom to coordinates</button></p>';

//		echo '<div class="pw-map"></div>';
		echo '<div id="laulo-map"></div>';

		$field_type_object->_desc( true, true );

		echo $field_type_object->input( array(
			'type'       => 'hidden',
			'name'       => $field->args('_name') . '[latitude]',
			'value'      => isset( $field_escaped_value['latitude'] ) ? $field_escaped_value['latitude'] : '',
			'class'      => 'pw-map-latitude',
			'desc'       => '',
		) );
		echo $field_type_object->input( array(
			'type'       => 'hidden',
			'name'       => $field->args('_name') . '[longitude]',
			'value'      => isset( $field_escaped_value['longitude'] ) ? $field_escaped_value['longitude'] : '',
			'class'      => 'pw-map-longitude',
			'desc'       => '',
		) );
	}

	/**
	 * Optionally save the latitude/longitude values into two custom fields.
	 */
	public function sanitize_pw_map( $override_value, $value, $object_id, $field_args ) {
		if ( isset( $field_args['split_values'] ) && $field_args['split_values'] ) {
			if ( ! empty( $value['latitude'] ) ) {
				update_post_meta( $object_id, $field_args['id'] . '_latitude', $value['latitude'] );
			}

			if ( ! empty( $value['longitude'] ) ) {
				update_post_meta( $object_id, $field_args['id'] . '_longitude', $value['longitude'] );
			}
		}

		return $value;
	}

	/**
	 * Enqueue admin scripts and styles.
	 */
	public function setup_admin_scripts() {
		wp_register_script( 'laulo-leaflet-js', plugins_url( 'js/leaflet.js', __FILE__ ), array(), self::VERSION );
		wp_enqueue_script( 'laulo-leaflet-maps-js', plugins_url( 'js/script.js', __FILE__ ), array( 'laulo-leaflet-js', 'jquery' ), self::VERSION );
		wp_enqueue_style( 'laulo-leaflet-css', plugins_url( 'css/leaflet.css', __FILE__ ), array(), self::VERSION );
		wp_enqueue_style( 'laulo-leaflet-maps-css', plugins_url( 'css/style.css', __FILE__ ), array('laulo-leaflet-css'), self::VERSION );
	}

	/**
	 * Enqueue public scripts and styles.
	 */
	public function setup_public_scripts() {
		wp_register_script( 'laulo-leaflet-js', plugins_url( 'js/leaflet.js', __FILE__ ), array(), self::VERSION, false );
    wp_enqueue_script('laulo-leaflet-js');
		wp_enqueue_style( 'laulo-leaflet-css', plugins_url( 'css/leaflet.css', __FILE__ ), array(), self::VERSION );
	}
}
$laulo_cmb2_field_leaflet_maps = new LAULO_CMB2_Field_Leaflet_Maps();
