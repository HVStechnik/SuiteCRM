<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C] 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C] 2011 - 2020 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a]: FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b] of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */

 $viewdefs['Users']['WizardView'] = [
 	'templateMeta' => [
 		'maxColumns' => '2',
 		'widths' => [
 			[
 				'label' => '20',
 				'field' => '30',
 			],
 			[
 				'label' => '20',
 				'field' => '30',
 			],
 		],
 		'form' => [
 			'headerTpl' => 'modules/Users/tpls/WizardViewHeader.tpl',
 			'footerTpl' => 'modules/Users/tpls/WizardViewFooter.tpl',
 		],
 		'useTabs' => true,
 		'tabDefs' => [
 			'LBL_WIZARD_WELCOME_TITLE' => [
 				'newTab' => true,
 				'panelDefault' => 'expanded',
 			],
 			'LBL_WIZARD_PERSONALINFO' => [
 				'newTab' => true,
 				'panelDefault' => 'expanded',
 			],
 			'LBL_WIZARD_LOCALE' => [
 				'newTab' => true,
 				'panelDefault' => 'expanded',
 			],
 			'LBL_WIZARD_FINISH1' => [
 				'newTab' => true,
 				'panelDefault' => 'expanded',
 			],
 		],
 		'syncDetailEditViews' => false,
 	],
 	'panels' => [
 		'LBL_WIZARD_WELCOME_TITLE' => [
 			[
 				[
 					'name' => 'first_name',
 					'customCode' => '<p>{$MOD.LBL_WIZARD_WELCOME_NOSMTP}</p>',
 					'hideLabel' => true,
 				],
 			],
            [
 				[
 					'name' => 'first_name',
 					'customCode' => '<div class="userWizWelcome"><img src="include/images/sugar_wizard_welcome.jpg" border="0" alt="{$MOD.LBL_WIZARD_WELCOME_TAB}" width="765px" height="325px"></div>',
 					'hideLabel' => true,
 				],
 			],
 		],
 		'LBL_WIZARD_PERSONALINFO' =>
 		[
 			[
 				[
 					'name' => 'first_name',
 					'customCode' => '<i>{$MOD.LBL_WIZARD_PERSONALINFO_DESC}</i>',
 					'hideLabel' => true,
 				],
 			],
            [
 				'first_name',
 				'last_name',
 			],
 			[
 				'email1',
 			],
 			[
 				'phone_work',
 				'messenger_type',
 			],
 			[
 				'phone_mobile',
 				'messenger_id',
 			],
 			[
 				'address_street',
 			],
 			[
 				'address_city',
 				'address_state',
 			],
 			[
                'address_postalcode',
                'address_country',
 			],
 		],
 		'LBL_WIZARD_LOCALE' =>
 		[
            [
 				[
 					'name' => 'first_name',
 					'customCode' => '<i>{$MOD.LBL_WIZARD_LOCALE_DESC}</i>',
 					'hideLabel' => true,
 				],
 			],
 			[
                [
 					'name' => 'first_name',
 					//'customCode' => "<select tabindex='14' name='timezone'>{html_options options=$TIMEZONEOPTIONS selected=$TIMEZONE_CURRENT}</select>",
                    'label' => 'LBL_TIMEZONE',
                    'help' => 'LBL_TIMEZONE_TEXT'
 				],
 			],
            [
                [
 					'name' => 'first_name',
 					'customCode' => "<select tabindex='14' name='dateformat'>{$DATEOPTIONS}</select>",
                    'label' => 'LBL_DATEFORMAT',
                    'help' => 'LBL_DATE_FORMAT_TEXT'
 				],
                [
 					'name' => 'first_name',
 					'customCode' => "<select tabindex='14' name='timeformat'>{$TIMEOPTIONS}</select>",
                    'label' => 'LBL_TIME_FORMAT',
                    'help' => 'LBL_TIME_FORMAT_TEXT'
 				],
 			],
            [
                [
 					'name' => 'first_name',
 					'customCode' => "<select tabindex='14' id='currency_select' name='currency' onchange='setSymbolValue(this.selectedIndex);setSigDigits();'>{$CURRENCY}</select><input type='hidden' id='symbol' value=''>",
                    'label' => 'LBL_CURRENCY',
                    'help' => 'LBL_CURRENCY_TEXT'
 				],
 			],
            [
                [
 					'name' => 'first_name',
 					'customCode' => "<select id='sigDigits' onchange='setSigDigits(this.value);' name='default_currency_significant_digits'>{$sigDigits}</select>",
 					'label' => 'LBL_CURRENCY_SIG_DIGITS',
 				],
                [
 					'name' => 'first_name',
 					'customCode' => "<input type='text' disabled id='sigDigitsExample' name='sigDigitsExample'>",
 					'label' => 'LBL_LOCALE_EXAMPLE_NAME_FORMAT',
 				],
 			],
            [
                [
 					'name' => 'first_name',
 					'customCode' => "<input tabindex='14' name='dec_sep' id='default_decimal_seperator'
                           type='text' maxlength='1' size='1' value='{$DEC_SEP}'
                           onkeydown='setSigDigits();' onkeyup='setSigDigits();'>",
 					'label' => 'LBL_DECIMAL_SEP',
 				],
                [
 					'name' => 'first_name',
 					'customCode' => "<input tabindex='14' name='num_grp_sep' id='default_number_grouping_seperator'
                               type='text' maxlength='1' size='1' value='{$NUM_GRP_SEP}'
                               onkeydown='setSigDigits();' onkeyup='setSigDigits();'>",
 					'label' => 'LBL_NUMBER_GROUPING_SEP',
 				],
 			],
            [
                [
 					'name' => 'first_name',
 					'customCode' => '<hr />',
                    'hideLabel' => true,
 				],
 			],
            [
                [
 					'name' => 'first_name',
 					'customCode' => "<select id='default_locale_name_format' tabindex='14' name='default_locale_name_format' selected='{$default_locale_name_format}'>{$NAMEOPTIONS}</select>",
                    'label' => 'LBL_LOCALE_DEFAULT_NAME_FORMAT',
                    'help' => 'SMARTY_LOCALE_NAME_FORMAT_DESC'
				],
 			],
 		],
		'LBL_WIZARD_FINISH1' =>
 		[
            [
 				[
 					'name' => 'first_name',
 					'customCode' => '<h3>{$MOD.LBL_WIZARD_FINISH1}</h3>',
 					'hideLabel' => true,
 				],
 			],
 			[
 				[
 					'name' => 'first_name',
 					'customCode' => '
                        <table cellpadding=0 cellspacing=0><input id="whatnext" name="whatnext" type="hidden" value="finish" />
                            {if $IS_ADMIN}
                                <tr><td><img src=include/images/start.png style="margin-right: 5px;"></td><td><a onclick=\'document.UserWizard.whatnext.value="finish";document.UserWizard.submit()\' href="#" ><b> {$MOD.LBL_WIZARD_FINISH2}  </b></a><br> {$MOD.LBL_WIZARD_FINISH2DESC}</td></tr>
                                <tr><td colspan=2><hr style="margin: 5px 0px;"></td></tr>
                                <tr><td><img src=include/images/import.png style="margin-right: 5px;"></td><td><a onclick=\'document.UserWizard.whatnext.value="import";document.UserWizard.submit()\' href="#" ><b> {$MOD.LBL_WIZARD_FINISH3} </b></a><br> {$MOD.LBL_WIZARD_FINISH4}</td></tr>
                                <tr><td colspan=2><hr style="margin: 5px 0px;"></td></tr>
                                <tr><td><img src=include/images/create_users.png style="margin-right: 5px;"></td><td><a onclick=\'document.UserWizard.whatnext.value="users";document.UserWizard.submit()\' href="#"  ><b> {$MOD.LBL_WIZARD_FINISH5} </b></a><br>{$MOD.LBL_WIZARD_FINISH6}</td></tr>
                                <tr><td colspan=2><hr style="margin: 5px 0px;"></td></tr>
                                <tr><td><img src=include/images/settings.png style="margin-right: 5px;"></td><td><a  onclick=\'document.UserWizard.whatnext.value="settings";document.UserWizard.submit()\' href="#" ><b> {$MOD.LBL_WIZARD_FINISH7} </b></a><br>{$MOD.LBL_WIZARD_FINISH8}</td></tr>
                                <tr><td colspan=2><hr style="margin: 5px 0px;"></td></tr>
                                <tr><td><img src=include/images/configure.png style="margin-right: 5px;"></td><td><a onclick=\'document.UserWizard.whatnext.value="studio";document.UserWizard.submit()\' href="#"  ><b> {$MOD.LBL_WIZARD_FINISH9} </b></a><br>{$MOD.LBL_WIZARD_FINISH10}</td></tr>
                                <tr><td colspan=2><hr style="margin: 5px 0px;"></td></tr>
                                <tr><td><img src=include/images/university.png style="margin-right: 5px;"></td><td><a href="https://community.suitecrm.com" target="_blank"><b> {$MOD.LBL_WIZARD_FINISH11} </b></a></b><br>{$MOD.LBL_WIZARD_FINISH12}</td></tr>
                                <tr><td colspan=2><hr style="margin: 5px 0px;"></td></tr>
                            {else}
                                <tr><td><img src=include/images/university2.png style="margin-right: 5px;"></td><td><a href="https://community.suitecrm.com" target="_blank"><b> {$MOD.LBL_WIZARD_FINISH11} </b></a></b><br>{$MOD.LBL_WIZARD_FINISH12}</td></tr>
                                <tr><td colspan=2><hr style="margin: 5px 0px;"></td></tr>
                                <tr><td><img src=include/images/docs.png style="margin-right: 5px;"></td><td><a href="https://docs.suitecrm.com/user/" target="_blank"><b> {$MOD.LBL_WIZARD_FINISH14} </b></a></b><br>{$MOD.LBL_WIZARD_FINISH15}</td></tr>
                                <tr><td colspan=2><hr style="margin: 5px 0px;"></td></tr>
                                <tr><td><img src=include/images/forums.png style="margin-right: 5px;"></td><td><a href="https://community.suitecrm.com/" target="_blank"><b> {$MOD.LBL_WIZARD_FINISH18} </b></a></b><br>{$MOD.LBL_WIZARD_FINISH19}</td></tr>
                                <tr><td colspan=2><hr style="margin: 5px 0px;"></td></tr>
                            {/if}
                        </table>
                    ',
 					'hideLabel' => true,
 				],
 			],
  		],
 	],
 ];
