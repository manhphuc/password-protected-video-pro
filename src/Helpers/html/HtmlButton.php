<?php
/**
 * Created by PhpStorm.
 * User: manhphucofficial@yahoo.com
 * Date: 07/13/2021
 * Time: 9:59 PM
 */

namespace Yivic\Wp\Plugin\Helpers\Html;

class HtmlButton {
    /**
     * Create button template string
     * @param $name null Name of the button element
     * @param $value null
     * @param $attr null|array Attributes of the button element ( Id - style - width - class - value ... )
     * @param $options null Sections will be added as new cases arise
     *                      [type]: button - submit - reset
     * @return string
     * */
	public static function create( $name = '', $value = '', $attr = [], $options = null ): string {
		$html = '';
        // MARK 1: Generate property string from $attr parameter
		$strAttr = '';
		if ( count( $attr ) > 0 ) {
			foreach ( $attr as $key => $val ) {
				if ( $key != "type" && $key != 'value' ) {
					$strAttr .= ' ' . $key . '="' . $val . '" ';
				}
			}
		}
		
		// Button Style Format
		if ( !isset( $options['type'] ) ) {
			$type = 'submit';
		} else {
			$type = $options['type'];
		}
		return $html = '<input type="' . $type .'" name="'. $name . '" ' . $strAttr . ' value="' . $value . '" />';
	}
}
