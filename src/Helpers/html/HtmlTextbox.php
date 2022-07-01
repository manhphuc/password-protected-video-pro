<?php
/**
 * Created by PhpStorm.
 * User: manhphucofficial@yahoo.com
 * Date: 07/13/2021
 * Time: 9:59 PM
 */

namespace Yivic\Wp\Plugin\Helpers\Html;

class HtmlTextBox {
    /**
     * Create template string input
     * @param $name null Name of the textbox element
     * @param $value null
     * @param $attr null|array The properties of the textbox element ( Id - style - width - class ... )
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
		return $html = '<input type="text" name="'. $name . '" ' . $strAttr . ' value="' . $value . '" />';
	}
}
