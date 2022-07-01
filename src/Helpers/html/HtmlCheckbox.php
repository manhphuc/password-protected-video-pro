<?php
/**
 * Created by PhpStorm.
 * User: manhphucofficial@yahoo.com
 * Date: 07/13/2021
 * Time: 9:59 PM
 */

namespace Yivic\Wp\Plugin\Helpers\Html;

class HtmlCheckbox {
    /**
     * Create checkbox template string
     * @param $name null Name of the checkbox element
     * @param $value null
     * @param $attr null|array Attributes of the checkbox element ( Id - style - width - class - value ... )
     * @param $options null Sections will be added as new cases arise
     *                      [current_value]
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
		
		// Check to see if there is a check
		$checked = '';
		if ( isset( $options['current_value'] ) ) {
			if ( $options['current_value'] == $value ) {
				$checked = ' checked="checked" ';
			}
		}
		return $html = '<input type="checkbox" name="'. $name . '" ' . $strAttr . ' value="' . $value . '" ' . $checked  . ' />';
	}
}