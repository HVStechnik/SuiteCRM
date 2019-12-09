<?php

/**
 * Products, Quotations & Invoices modules.
 * Extensions to SugarCRM
 * @package Advanced OpenSales for SugarCRM
 * @subpackage Products
 * @copyright SalesAgility Ltd http://www.salesagility.com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU AFFERO GENERAL PUBLIC LICENSE
 * along with this program; if not, see http://www.gnu.org/licenses
 * or write to the Free Software Foundation,Inc., 51 Franklin Street,
 * Fifth Floor, Boston, MA 02110-1301  USA
 *
 * @author SalesAgility Ltd <support@salesagility.com>
 */

use SuiteCRM\Utility\SuiteValidator as SuiteValidator;

class templateParser
{
    public static function parse_template($string, $bean_arr)
    {
        foreach ($bean_arr as $bean_name => $bean_id) {
            $focus = BeanFactory::getBean($bean_name, $bean_id);
            $string = templateParser::parse_template_loop($string, $focus->table_name, $focus);


            if($focus->table_name != strtolower($beanList[$bean_name])) {
                $string = templateParser::parse_template_loop($string, strtolower($beanList[$bean_name], $focus);
            }

            // keep this for templates that refer to related fields with $relate_field_name directly
            foreach ($focus->field_defs as $field_name => $field_def) {
                if ($field_def['type'] == 'relate' && strpos($string,'$'.$field_def['name'].'_') !== FALSE) {
                    if (isset($field_def['module']) && $field_def['module'] != '' && $field_def['module'] != 'EmailAddress') {
                        $idName = $field_def['id_name'];
                        $relate_focus = BeanFactory::getBean($field_def['module'], $focus->$idName);

                        $string = templateParser::parse_template_bean($string, $field_def['name'], $relate_focus);
                    }
                }
            }
        }
        return $string;
    }

	public function parse_template_loop($string, $key, $focus)
	{
		foreach ($focus->field_defs as $field_name => $field_def) {
            if ($field_def['type'] == 'relate'){
				$key_related  = $key.'_'.$field_def['name'];
				if(strpos($string,'$'.$key_related.'_') !== FALSE){
					if(isset($field_def['module']) && $field_def['module'] != '' && $field_def['module'] != 'EmailAddress') {
						$idName = $field_def['id_name'];
						$relate_focus = BeanFactory::getBean($field_def['module'], $focus->$idName);

						$string = templateParser::parse_template_loop($string, $key_related, $relate_focus);
					}
				}
			}
			elseif ($field_def['type'] == 'link'){
				$key_related  = empty($key) ? $field_def['name'] : $key.'_'.$field_def['name'];
				if(strpos($string,'$'.$key_related) !== FALSE && $focus->load_relationship($field_name)){
					$relatedBeans = $focus->$field_name->getBeans();

					// Are we just looking for a singe object indicated by a "_" after the key?
					if($relatedBeans && strpos($string,'$'.$key_related.'_') !== FALSE){
						$string = templateParser::parse_template_loop($string, $key_related, reset($relatedBeans));
					}
					// a M2M or O2M relationship
					else{
						$tables = array();
						if(preg_match_all('~<tr((?!<tr).)+\$'.preg_quote($key_related).'\[\]((?!</tr).)*</tr>~six',$string,$tables) ||
						   preg_match_all('~<p((?!<p).)+\$'.preg_quote($key_related).'\[\]((?!</p).)*</p>~six',$string,$tables) ||
						   preg_match_all('~.*\$'.preg_quote($key_related).'\[\].*~ix',$string,$tables)){
							$replace_arr = array();
							foreach($tables[0] as $table){
								$replacement = '';
								foreach($relatedBeans as $relatedBean){
									$replacement .= templateParser::parse_template_loop($table, $key_related.'[]', $relatedBean);
								}
								$replace_arr[] = $replacement;
							}
							$string = str_replace($tables[0], $replace_arr, $string);
						}
					}
				}
			}
		}

		$string = templateParser::parse_template_bean($string, $key, $focus);
		return $string;
	}


    public function parse_template_bean($string, $key, &$focus)
    {
        global $app_strings, $sugar_config;
        $repl_arr = array();
        $isValidator = new SuiteValidator();

        foreach ($focus->field_defs as $field_def) {
            if (isset($field_def['name']) && $field_def['name'] != '') {
                $fieldName = $field_def['name'];
                if ($field_def['type'] == 'currency') {
                    $repl_arr[$key . "_" . $fieldName] = currency_format_number($focus->$fieldName, $params = array('currency_symbol' => false));
                } elseif (($field_def['type'] == 'radioenum' || $field_def['type'] == 'enum' || $field_def['type'] == 'dynamicenum') && isset($field_def['options'])) {
                    $repl_arr[$key . "_" . $fieldName] = translate($field_def['options'], $focus->module_dir, $focus->$fieldName);
                } elseif ($field_def['type'] == 'multienum' && isset($field_def['options'])) {
                    $mVals = unencodeMultienum($focus->$fieldName);
                    $translatedVals = array();
                    foreach ($mVals as $mVal) {
                        $translatedVals[] = translate($field_def['options'], $focus->module_dir, $mVal);
                    }
                    $repl_arr[$key . "_" . $fieldName] = implode(", ", $translatedVals);
                } //Fix for Windows Server as it needed to be converted to a string.
                elseif ($field_def['type'] == 'int') {
                    $repl_arr[$key . "_" . $fieldName] = (string)$focus->$fieldName;
                } elseif ($field_def['type'] == 'bool') {
                    if ($focus->$fieldName == "1") {
                        $repl_arr[$key . "_" . $fieldName] = "true";
                    } else {
                        $repl_arr[$key . "_" . $fieldName] = "false";
                    }
                } elseif ($field_def['type'] == 'image') {
                    $secureLink = $sugar_config['site_url'] . '/' . "public/". $focus->id .  '_' . $fieldName;
                    $file_location = $sugar_config['upload_dir'] . '/'  . $focus->id .  '_' . $fieldName;
                    // create a copy with correct extension by mime type
                    if (!file_exists('public')) {
                        sugar_mkdir('public', 0777);
                    }
                    if (!copy($file_location, "public/{$focus->id}".  '_' . (string)$fieldName)) {
                        $secureLink = $sugar_config['site_url'] . '/'. $file_location;
                    }

                    if (empty($focus->$fieldName)) {
                        $repl_arr[$key . "_" . $fieldName] = "";
                    } else {
                        $link = $secureLink;
                        $repl_arr[$key . "_" . $fieldName] = '<img src="' . $link . '" width="'.$field_def['width'].'" height="'.$field_def['height'].'"/>';
                    }
                } else {
                    $repl_arr[$key . "_" . $fieldName] = $focus->$fieldName;
                }
            }
        } // end foreach()

        krsort($repl_arr);
        reset($repl_arr);

        foreach ($repl_arr as $name => $value) {
            if (strpos($name, 'product_discount') !== false || strpos($name, 'quotes_discount') !== false) {
                if ($value !== '') {
                    if ($isValidator->isPercentageField($repl_arr['aos_products_quotes_discount'])) {
                        $sep = get_number_separators();
                        $value = rtrim(
                            rtrim(format_number($value), '0'),
                            $sep[1]
                        ) . $app_strings['LBL_PERCENTAGE_SYMBOL'];
                    }
                } else {
                    $value = '';
                }
            }
            if ($name === 'aos_products_product_image' && !empty($value)) {
                $value = '<img src="' . $value . '" class="img-responsive"/>';
            }
            if ($name === 'aos_products_quotes_product_qty') {
                $sep = get_number_separators();
                $value = rtrim(rtrim(format_number($value), '0'), $sep[1]);
            }

            if ($isValidator->isPercentageField($name)) {
                $sep = get_number_separators();
                $value = rtrim(rtrim(format_number($value), '0'), $sep[1]) . $app_strings['LBL_PERCENTAGE_SYMBOL'];
            }
            if ($focus->field_defs[$name]['dbType'] == 'datetime' &&
                (strpos($name, 'date') > 0 || strpos($name, 'expiration') > 0)) {
                if ($value != '') {
                    $dt = explode(' ', $value);
                    $value = $dt[0];
                    if (isset($dt[1]) && $dt[1]!='') {
                        if (strpos($dt[1], 'am') > 0 || strpos($dt[1], 'pm') > 0) {
                            $value = $dt[0].' '.$dt[1];
                        }
                    }
                }
            }
            if ($value != '' && is_string($value)) {
                $string = str_replace("\$$name", $value, $string);
            } elseif (strpos($name, 'address') > 0) {
                $string = str_replace("\$$name<br />", '', $string);
                $string = str_replace("\$$name <br />", '', $string);
                $string = str_replace("\$$name", '', $string);
            } else {
                $string = str_replace("\$$name", '&nbsp;', $string);
            }
        }

        return $string;
    }
}
