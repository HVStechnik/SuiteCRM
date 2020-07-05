{*
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
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
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for technical reasons, the Appropriate Legal Notices must
 * display the words "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 */
*}


                <div class="nav-buttons">
                    <input title="{$MOD.LBL_WIZARD_BACK_BUTTON}"
                           class="button" type="button" name="prev_tab" id="prev_tab" value="  {$MOD.LBL_WIZARD_BACK_BUTTON}  "
                           onclick="selectAdjacentTab(-1);" style="display:none;"/>&nbsp;
                    <input title="{$MOD.LBL_WIZARD_NEXT_BUTTON}"
                           class="button primary" type="button" name="next_tab" id="next_tab" value="  {$MOD.LBL_WIZARD_NEXT_BUTTON}  "
                           onclick="selectAdjacentTab(1);" style="display:none;"/>
                    <input title="{$MOD.LBL_WIZARD_FINISH_BUTTON}" class="button primary" id="submit_button"
                           type="submit" name="save" value="  {$MOD.LBL_WIZARD_FINISH_BUTTON}  " />&nbsp;
                </div>



                {literal}
                <script type='text/javascript'>

                	var tab_max = $('.nav-tabs > li').length - 1;
                	function selectAdjacentTab(dir){
                		tab = Number($('.nav-tabs > li.active > a').eq(0).attr('id').slice(-1)) + dir;
                		$('.nav-tabs > li > a#tab' + (tab)).trigger('click');
                		if(tab == 0) $('#prev_tab').hide();
                		else $('#prev_tab').show();
                		if(tab >= tab_max){
                			$('#next_tab').hide();
                			$('#submit_button').show();
                		}else {
                			$('#next_tab').show();
                			$('#submit_button').hide();
                		}
                	}

                	$('#submit_button').hide();
                	$('#next_tab').show();

                </script>
                {/literal}
                {$JAVASCRIPT}
                {literal}
                <script type="text/javascript" language="Javascript">
                    {/literal}
                    {$getNameJs}
                    {$getNumberJs}
                    {$currencySymbolJs}
                    setSymbolValue(document.getElementById('currency_select').selectedIndex);
                    setSigDigits();

                    {$confirmReassignJs}
                </script>

                {literal}
                <script type='text/javascript'>
                <!--
                var SugarWizard = new function()
                {
                    this.currentScreen = 'welcome';

                    this.handleKeyStroke = function(e)
                    {
                        // get the key pressed
                        var key;
                        if (window.event) {
                            key = window.event.keyCode;
                        }
                        else if(e.which) {
                            key = e.which
                        }

                        switch(key) {
                            case 13:
                                primaryButton = YAHOO.util.Selector.query('input.primary',SugarWizard.currentScreen,true);
                                primaryButton.click();
                                break;
                        }
                    }

                    this.changeScreen = function(screen,skipCheck)
                    {
                        if ( !skipCheck ) {
                            clear_all_errors();
                            var form = document.getElementById('UserWizard');
                            var isError = false;

                            switch(this.currentScreen) {
                                case 'personalinfo':
                                    if ( document.getElementById('last_name').value == '' ) {
                                        add_error_style('UserWizard',form.last_name.name,
                                                '{/literal}{$APP.ERR_MISSING_REQUIRED_FIELDS} {$MOD.LBL_LAST_NAME}{literal}' );
                                        isError = true;
                                    }
                                {/literal}
                                {if $REQUIRED_EMAIL_ADDRESS}
                                {literal}
                                    if ( document.getElementById('email1').value == '' ) {
                                        document.getElementById('email1').focus();
                                        add_error_style('UserWizard',form.email1.name,
                                                '{/literal}{$APP.ERR_MISSING_REQUIRED_FIELDS} {$MOD.LBL_EMAIL}{literal}' );
                                        isError = true;
                                    }
                                {/literal}
                                {/if}
                                {literal}
                                    break;
                            }
                            if (isError == true)
                                return false;
                        }

                        document.getElementById(this.currentScreen).style.display = 'none';
                        document.getElementById(screen).style.display = 'block';

                        this.currentScreen = screen;
                    }
                }

                $(document).ready(function() {
                  $('.screen').each(function() {
                    $(this).hide();
                  });

                  {/literal}
                  {if $SKIP_WELCOME}
                  SugarWizard.changeScreen('personalinfo');
                  {else}
                  SugarWizard.changeScreen('welcome');
                  {/if}
                  {literal}
                });

                document.onkeypress = SugarWizard.handleKeyStroke;

                var mail_smtpport = '{/literal}{$MAIL_SMTPPORT}{literal}';
                var mail_smtpssl = '{/literal}{$MAIL_SMTPSSL}{literal}';

                -->
                </script>
                {/literal}
                {$JAVASCRIPT}
                {literal}
                <script type="text/javascript" language="Javascript">
                    {/literal}
                    {$getNameJs}
                    {$getNumberJs}
                    {$currencySymbolJs}
                    setSymbolValue(document.getElementById('currency_select').selectedIndex);
                    setSigDigits();

                    {$confirmReassignJs}
                </script>
            </form>

            <div id="testOutboundDialog" class="yui-hidden">
                <div id="testOutbound">
                    <form>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="edit view">
                            <tr>
                                <td scope="row">
                                    {$APP.LBL_EMAIL_SETTINGS_FROM_TO_EMAIL_ADDR}
                                    <span class="required">
            						{$APP.LBL_REQUIRED_SYMBOL}
            					</span>
                                </td>
                                <td >
                                    <input type="text" id="outboundtest_from_address" name="outboundtest_from_address" size="35" maxlength="64" value="">
                                </td>
                            </tr>
                            <tr>
                                <td scope="row" colspan="2">
                                    <input type="button" class="button" value="   {$APP.LBL_EMAIL_SEND}   " onclick="javascript:sendTestEmail();">&nbsp;
                                    <input type="button" class="button" value="   {$APP.LBL_CANCEL_BUTTON_LABEL}   " onclick="javascript:EmailMan.testOutboundDialog.hide();">&nbsp;
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
