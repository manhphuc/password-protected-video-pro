<?php
/**
 * Created by PhpStorm.
 * User: manhphucofficial@yahoo.com
 * Date: 07/13/2021
 * Time: 9:59 PM
 */

namespace Yivic\Wp\Plugin\Helpers\Html;

class HtmlPassword {
    /**
     * Create password template string
     * @param $name null Name of the password element
     * @param $value null
     * @param $attr null|array Attributes of the password element ( Id - style - width - class - value ... )
     * @param $options null Sections will be added as new cases arise
     * @return string
     * */
	public static function create( $name = '', $value = '', $attr = [], $options = null ): string {
		$html = '';
        // MARK 1: Generate property string from $attr parameter
		$strAttr = '';
		if ( count( $attr ) > 0 ) {
			foreach ( $attr as $key => $val ) {
				if( $key != "type" && $key != 'value' ) {
					$strAttr .= ' ' . $key . '="' . $val . '" ';
				}
			}
		}
		return $html = '<input type="password" name="'. $name . '" ' . $strAttr . ' value="' . $value . '" />';
	}
}

