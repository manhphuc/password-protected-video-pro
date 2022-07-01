<?php
/**
 * Created by PhpStorm.
 * User: manhphucofficial@yahoo.com
 * Date: 07/13/2021
 * Time: 9:59 PM
 */

namespace Yivic\Wp\Plugin\Helpers\Html;

class HtmlRadio {
    /**
     * Create HtmlRadio template string
     * @param $name null Name of the radio element
     * @param $value null
     * @param $attr null|array Attributes of the radio element ( Id - style - width - class - value ... )
     * @param $options null Sections will be added as new cases arise
     *                      [data]: Is the element that will contain an array of values and labels of the radio element
     *                      [separator]: Separator value of radio buttons
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
		$strValue = $value;

        // MARK 3: Check the separator character between radio buttons
		if ( !isset( $options['separator'] ) ) {
			$options['separator'] = ' ';
		}

        // MARK 4: Create radio buttons
		$html = '';
		$data = $options['data'];
		if ( count( $data ) ) {
			foreach ( $data as $key => $val ) {
				$checked = '';
				if ( preg_match('/^(' . $strValue .')$/i', $key ) ) {
					$checked = ' checked="checked" ';
				}				
				$html  .= '<input type="radio" name="' . $name . '" ' . $checked . ' value="' . $key . '"/>' . $val  . $options['separator'];
			}
		}
		return $html;
	}
}
