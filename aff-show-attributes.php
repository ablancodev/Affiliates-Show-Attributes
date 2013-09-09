<?php
/*
 Plugin Name: Affiliates Show Attributes
Plugin URI: http://www.eggemplo.com
Description: Add a shortcode to show affiliate's attributes as lineal text (without input type)
Author: eggemplo
Version: 1.0
Author URI: http://www.eggemplo.com
*/

add_shortcode('aff-show-attributes', 'aff_show_attributes');
add_shortcode('aff_show_attributes', 'aff_show_attributes');

function aff_show_attributes ($attr = array()) {
	global $affiliates_db;
	
	$output = "";
	$sep =  isset( $attr['separator'] ) ? $attr['separator'] : "<br>";
	
	$keys = isset( $attr['show_attributes'] ) ? $attr['show_attributes'] : null;
	
	$aff_id = Affiliates_Affiliate_WordPress::get_user_affiliate_id();
	if ( $aff_id === false ) { 
		return $output; 
	}
	
	if ( $keys ) {
		$keys = explode( ",", $keys );
		$tempkeys = array();
		foreach ( $keys as $key ) {
			$key = trim( $key );
			if ( Affiliates_Attributes::validate_key( $key ) ) {
				$tempkeys[] = $key;
			}
		}
		$keys = $tempkeys;
	} else {
		$keys = array();
	}
	
	if ( $keys ) {
		$attrTable = $affiliates_db->get_tablename( 'affiliates_attributes' );
		$attributes = $affiliates_db->get_objects( "SELECT * FROM $attrTable WHERE affiliate_id = %d", $aff_id );
		$values = array();
		foreach ( $attributes as $attribute ) {
			$values[$attribute->attr_key] = $attribute->attr_value;
		}
		$IXAP361 = Affiliates_Attributes::get_keys();
		foreach ( $keys as $key ) {
			$attrValue = isset( $values[$key] ) ? $values[$key] : '';
			
			$output .= esc_attr( $attrValue ) . $sep;
		}
		
		if ( strlen($output)>0 ) { // delete last separator
			$output = substr($output, 0, strlen($output)-strlen($sep));
		}
	}
	
	return $output;
				
}
