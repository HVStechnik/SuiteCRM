<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}


class AOS_PDF_TemplatesViewEdit extends ViewEdit
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @deprecated deprecated since version 7.6, PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code, use __construct instead
     */
    public function AOS_PDF_TemplatesViewEdit()
    {
        $deprecatedMessage = 'PHP4 Style Constructors are deprecated and will be remove in 7.8, please update your code';
        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->deprecated($deprecatedMessage);
        } else {
            trigger_error($deprecatedMessage, E_USER_DEPRECATED);
        }
        self::__construct();
    }


    public function display()
    {
        $this->setFields();
        parent::display();
        $this->displayTMCE();
    }

    public function setFields()
    {
        global $app_list_strings, $mod_strings, $beanList;

        //Loading Sample Files
        $json = getJSONobj();
        $samples = array();
        if ($handle = opendir('modules/AOS_PDF_Templates/samples')) {
            $sample_options_array[] = ' ';
            while (false !== ($file = readdir($handle))) {
                if ($value = ltrim(rtrim($file, '.php'), 'smpl_')) {
                    require_once('modules/AOS_PDF_Templates/samples/'.$file);
                    $file = rtrim($file, '.php');
                    $file = new $file();
                    $fileArray =
                        array(
                            $file->getType(),
                            $file->getBody(),
                            $file->getHeader(),
                            $file->getFooter()
                        );
                    $fileArray = $json->encode($fileArray);
                    $value = $mod_strings['LBL_'.strtoupper($value)];
                    $sample_options_array[$fileArray] = $value;
                }
            }
            $samples = get_select_options($sample_options_array, '');
            closedir($handle);
        }

        $this->ss->assign('CUSTOM_SAMPLE', '<select id="sample" name="sample" onchange="insertSample(this.options[this.selectedIndex].value)">'.
            $samples.
            '</select>');


        $insert_fields_js ="<script>var moduleOptions = {\n";
        $insert_fields_js2 ="<script>var regularOptions = {\n";
        $modules = $app_list_strings['pdf_template_type_dom'];

        foreach ($modules as $moduleName => $value) {
            $mod_options_array = array();

            //Getting Fields
            if (!$beanList[$moduleName]) {
                continue;
            }
            $module = BeanFactory::newBean($moduleName);
            $options_array = $this->getModuleOptionArray($module, $module->table_name.'_');
            $options = json_encode($options_array);
            $mod_options_array[$module->module_dir] = translate('LBL_MODULE_NAME', $module->module_dir);
            $insert_fields_js2 .="'$moduleName':$options,\n";
            $firstOptions = $options;

            $fmod_options_array = array();
            $link_id_fields = array();
            foreach ($module->field_defs as $module_name => $module_arr) {
                if (isset($module_arr['source']) && $module_arr['source'] == 'non-db' && isset($module_arr['type']) && $module_arr['type'] == 'link') {
                    $module->load_relationship($module_name);
                    $relate_module_name = $module->$module_name->getRelatedModuleName();
                    if ($relate_module_name != 'EmailAddress') {
                        $relate_module = BeanFactory::newBean($relate_module_name);
                        $key = $module->table_name.'_'.$module_arr['name'];
                        if ($module->$module_name->getType() == 'one') {
                            $options_array = $this->getModuleOptionArray($relate_module, $key.'_');
                        }
                        else {
                            $options_array = $this->getModuleOptionArray($relate_module, $key.'[]_');
                        }

                        $options = json_encode($options_array);
                        $insert_fields_js2 .="'$key':$options,\n";
                        $fmod_options_array[$key] = translate($module_arr['vname'], $module->module_dir).' ('.translate($relate_module->module_dir).')';

                        $link_id_fields[] = $module_arr['id_name'];
                    }
                }
            }
            foreach ($module->field_defs as $module_name => $module_arr) {
                if (isset($module_arr['type']) && $module_arr['type'] == 'relate' && isset($module_arr['source']) && $module_arr['source'] == 'non-db' &&
                    isset($module_arr['module']) &&  $module_arr['module'] != '' && $module_arr['module'] != 'EmailAddress' && !in_array($module_arr['id_name'],$link_id_fields)) {
                        $relate_module = BeanFactory::newBean($module_arr['module']);

                        $options_array = $this->getModuleOptionArray($relate_module, $module_arr['name'].'_');
                        $options = json_encode($options_array);

                        if ($module_arr['vname'] != 'LBL_DELETED') {
                            $options_array['$'.$module->table_name.'_'.$name] = translate($module_arr['vname'], $module->module_dir);
                            $fmod_options_array[$module_arr['vname']] = translate($relate_module->module_dir).' : '.translate($module_arr['vname'], $module->module_dir);
                        }
                        $test = $module_arr['vname'];
                        $insert_fields_js2 .="'$test':$options,\n";
                    }
                }
            }

            //LINE ITEMS CODE!
            if (isset($module->lineItems) && $module->lineItems) {

                //add group fields
                $options_array = array(''=>'');
                $group_quote = new AOS_Line_Item_Groups();
                $options_array = $this->getModuleOptionArray($group_quote, $group_quote->table_name.'_');
                $options = json_encode($options_array);

                $line_module_name = $beanList['AOS_Line_Item_Groups'];
                $fmod_options_array[$line_module_name] = translate('LBL_LINE_ITEMS', 'AOS_Quotes').' : '.translate('LBL_MODULE_NAME', 'AOS_Line_Item_Groups');
                $insert_fields_js2 .="'$line_module_name':$options,\n";

                //PRODUCTS
                $product_quote = new AOS_Products_Quotes();
                $options_array = $this->getModuleOptionArray($product_quote, $product_quote->table_name.'_');

                $product = new AOS_Products();
                $options_array = $this->getModuleOptionArray($product, $product->table_name.'_',$options_array);
                $options = json_encode($options_array);

                $line_module_name = $beanList['AOS_Products_Quotes'];
                $fmod_options_array[$line_module_name] = translate('LBL_LINE_ITEMS', 'AOS_Quotes').' : '.translate('LBL_MODULE_NAME', 'AOS_Products');
                $insert_fields_js2 .="'$line_module_name':$options,\n";

                //Services
                $options_array = array(''=>'');
                $options_array['$aos_services_quotes_name'] = translate('LBL_SERVICE_NAME', 'AOS_Quotes');
                $options_array['$aos_services_quotes_number'] = translate('LBL_LIST_NUM', 'AOS_Products_Quotes');
                $options_array['$aos_services_quotes_service_list_price'] = translate('LBL_SERVICE_LIST_PRICE', 'AOS_Quotes');
                $options_array['$aos_services_quotes_service_discount'] = translate('LBL_SERVICE_DISCOUNT', 'AOS_Quotes');
                $options_array['$aos_services_quotes_service_unit_price'] = translate('LBL_SERVICE_PRICE', 'AOS_Quotes');
                $options_array['$aos_services_quotes_vat_amt'] = translate('LBL_VAT_AMT', 'AOS_Quotes');
                $options_array['$aos_services_quotes_vat'] = translate('LBL_VAT', 'AOS_Quotes');
                $options_array['$aos_services_quotes_service_total_price'] = translate('LBL_TOTAL_PRICE', 'AOS_Quotes');

                $options = json_encode($options_array);

                $s_line_module_name = 'AOS_Service_Quotes';
                $fmod_options_array[$s_line_module_name] = translate('LBL_LINE_ITEMS', 'AOS_Quotes').' : '.translate('LBL_SERVICE_MODULE_NAME', 'AOS_Products_Quotes');
                $insert_fields_js2 .="'$s_line_module_name':$options,\n";


                $options_array = array(''=>'');
                $currencies = new currency();
                foreach ($currencies->field_defs as $name => $arr) {
                    if (!((isset($arr['dbType']) && strtolower($arr['dbType']) == 'id') || $arr['type'] == 'id' || $arr['type'] == 'link' || $arr['type'] == 'bool' || $arr['type'] == 'datetime' || (isset($arr['link_type']) && $arr['link_type'] == 'relationship_info'))) {
                        if (isset($arr['vname']) && $arr['vname'] != 'LBL_DELETED' && $arr['vname'] != 'LBL_CURRENCIES_HASH' && $arr['vname'] != 'LBL_LIST_ACCEPT_STATUS' && $arr['vname'] != 'LBL_AUTHENTICATE_ID' && $arr['vname'] != 'LBL_MODIFIED_BY' && $arr['name'] != 'created_by_name') {
                            $options_array['$currencies_'.$name] = translate($arr['vname'], 'Currencies');
                        }
                    }
                }
                $options = json_encode($options_array);

                $line_module_name = $beanList['Currencies'];
                $fmod_options_array[$line_module_name] = translate('LBL_MODULE_NAME', 'Currencies').' : '.translate('LBL_MODULE_NAME', 'Currencies');
                $insert_fields_js2 .="'$line_module_name':$options,\n";
            }
            array_multisort($fmod_options_array, SORT_ASC, $fmod_options_array);
            $mod_options_array = array_merge($mod_options_array, $fmod_options_array);
            $module_options = json_encode($mod_options_array);


            $insert_fields_js .="'$moduleName':$module_options,\n";
            $moduleOptions[$moduleName] = array("module" => $module_options,"option" => $firstOptions);
        } //End loop.

        //Sets options to original options on load.
        $insert_fields_js .= "} ;</script>";
        $insert_fields_js2 .= "} ;</script>";
        //echo $this->bean->type;
        if ($this->bean->type=='') {
            $type = key($app_list_strings['pdf_template_type_dom']);
        } else {
            $type = $this->bean->type;
        }

        //Start of insert_fields
        $insert_fields = '';
        $insert_fields .= <<<HTML

		$insert_fields_js
		$insert_fields_js2
		<select name='module_name' id='module_name' tabindex="50" onchange="populateVariables(this.options[this.selectedIndex].value);">
		</select>
		<select name='variable_name' id='variable_name' tabindex="50" onchange="showVariable(this.options[this.selectedIndex].value);">
		</select>
		<input type="text" size="30" tabindex="60" name="variable_text" id="variable_text" />
		<input type='button' tabindex="70" onclick='insert_variable(document.EditView.variable_text.value, "email_template_editor");' class='button' value='${mod_strings['LBL_BUTTON_INSERT']}'>
		<script type="text/javascript">
			populateModuleVariables("$type");
	</script>


HTML;

        $this->ss->assign('INSERT_FIELDS', $insert_fields);
    }

    protected function getModuleOptionArray(SugarBean $module, $key, &$options_array = NULL)
    {
        if (!isset($options_array)) {
            $options_array = array(''=>'');
        }
        foreach ($module->field_defs as $field_name => $field_def) {
            if (!((isset($field_def['dbType']) && strtolower($field_def['dbType']) == 'id') || (isset($field_def['type']) && $field_def['type'] == 'id') || (isset($field_def['type']) && $field_def['type'] == 'link'))) {
                if ((!isset($field_def['reportable']) || $field_def['reportable']) && isset($field_def['vname'])) {
                    $options_array['$'.$key.$field_name] = translate($field_def['vname'], $module->module_dir);
                }
            }
        }
        return $options_array;
    }

    public function displayTMCE()
    {
        require_once("include/SugarTinyMCE.php");
        global $locale;

        $tiny = new SugarTinyMCE();
        $tinyMCE = $tiny->getConfig();

        $js =<<<JS
		<script language="javascript" type="text/javascript">
		$tinyMCE
		var df = '{$locale->getPrecedentPreference('default_date_format')}';

 		tinyMCE.init({
    		theme : "advanced",
    		theme_advanced_toolbar_align : "left",
    		mode: "exact",
			elements : "description",
			theme_advanced_toolbar_location : "top",
			theme_advanced_buttons1: "code,help,separator,bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,forecolor,backcolor,separator,styleprops,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,selectall,separator,search,replace,separator,bullist,numlist,separator,outdent,indent,separator,ltr,rtl,separator,undo,redo,separator, link,unlink,anchor,image,separator,sub,sup,separator,charmap,visualaid",
			theme_advanced_buttons3: "tablecontrols,separator,advhr,hr,removeformat,separator,insertdate,pagebreak",
			theme_advanced_fonts:"Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Helvetica Neu=helveticaneue,sans-serif;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats",
			plugins : "advhr,insertdatetime,table,paste,searchreplace,directionality,style,pagebreak",
			height:"500",
			width: "100%",
			inline_styles : true,
			directionality : "ltr",
			remove_redundant_brs : true,
			entity_encoding: 'raw',
			cleanup_on_startup : true,
			strict_loading_mode : true,
			convert_urls : false,
			plugin_insertdate_dateFormat : '{DATE '+df+'}',
			pagebreak_separator : "<pagebreak />",
			extended_valid_elements : "textblock,barcode[*]",
			custom_elements: "textblock",
		});

		tinyMCE.init({
    		theme : "advanced",
    		theme_advanced_toolbar_align : "left",
    		mode: "exact",
			elements : "pdfheader,pdffooter",
			theme_advanced_toolbar_location : "top",
			theme_advanced_buttons1: "code,separator,bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,undo,redo,separator,forecolor,backcolor,separator,styleprops,styleselect,formatselect,fontselect,fontsizeselect,separator,insertdate",
			theme_advanced_buttons2 : "",
    		theme_advanced_buttons3 : "",
    		theme_advanced_fonts:"Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Helvetica Neu=helveticaneue,sans-serif;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats",
			plugins : "advhr,insertdatetime,table,paste,searchreplace,directionality,style",
			width: "100%",
			inline_styles : true,
			directionality : "ltr",
			entity_encoding: 'raw',
			cleanup_on_startup : true,
			strict_loading_mode : true,
			convert_urls : false,
			remove_redundant_brs : true,
			plugin_insertdate_dateFormat : '{DATE '+df+'}',
			extended_valid_elements : "textblock,barcode[*]",
			custom_elements: "textblock",
		});

		</script>

JS;
        echo $js;
    }
}
