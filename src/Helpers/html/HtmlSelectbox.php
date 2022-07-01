<?php
/**
 * Created by PhpStorm.
 * User: manhphucofficial@yahoo.com
 * Date: 07/13/2021
 * Time: 9:59 PM
 */

namespace Yivic\Wp\Plugin\Helpers\Html;

class HtmlSelectBox {
    /**
     * Create SelectBox template string
     * @param $name null Name of the SelectBox element
     * @param $value null
     * @param $attr null|array Attributes of the SelectBox element ( Id - style - width - class - value ... )
     * @param $options null Sections will be added as new cases arise
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
		
		// MARK 2: Check the value of $value
		$strValue = '';
		if ( is_array( $value ) ) {
			$strValue = implode( "|", $value );
		} else {
			$strValue = $value;
		}
		
		// MARK 3: Create value and label of <option>
		$strOption = '';
		$data = $options['data'];
		if ( count( $data ) ) {
			foreach ( $data as $key => $val ) {
				$selected = '';
				if ( preg_match( '/^(' . $strValue .')$/i', $key ) ) {
					$selected = ' selected="selected" ';
				}
				$strOption .= '<option value="' . $key . '" ' . $selected . ' >' . $val . '</option>';
			}
		}
        return $html = '<select name="'. $name . '" ' . $strAttr . ' >' . $strOption . '</select>';
	}
}
