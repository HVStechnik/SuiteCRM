{*
/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2020 SalesAgility Ltd.
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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html {$langHeader}>
    <head>
        <link rel="SHORTCUT ICON" href="{$FAVICON_URL}">
        <meta http-equiv="Content-Type" content="text/html; charset={$APP.LBL_CHARSET}">
        <title>{$MOD.LBL_WIZARD_TITLE}</title>
        {literal}
            <script type='text/javascript'>
                function disableReturnSubmission(e) {
                    var key = window.event ? window.event.keyCode : e.which;
                    return (key != 13);
                }
            </script>
        {/literal}
        {$SUGAR_JS}
        {$SUGAR_CSS}
        {$CSS}
        {literal}<style>
        @media (min-width: 800px) {
            body {
                background: url('/suite/custom/themes/default/images/login_bg.jpg');
                background-attachment: fixed;
                background-position:center;
                background-size:cover;
                height: auto;
            }
            div#content {
                width:800px;
                margin:0 auto;
                margin-top:50px;
            }
            div.panel {
                -moz-box-shadow: 0 0 15px -2px #111111;
                -webkit-box-shadow: 0 0 15px -2px #111111;
                box-shadow: 0 0 15px -2px #111111;
            }
            div.tab-content {
                padding: 10px;
                background: #fff;
            }
            ul.nav-tabs li {
                cursor:pointer;
            }
        }
        </style>{/literal}
    </head>
    <body class="yui-skin-sam">
        <div id="main">
            <div id="content">
                <form id="UserWizard" name="UserWizard" enctype='multipart/form-data' method="POST" action="index.php" onkeypress="return disableReturnSubmission(event);">
                    <input type='hidden' name='action' value='SaveUserWizard'/>
                    <input type='hidden' name='module' value='Users'/>
                    <span class='error'>{$error.main}</span>
                    <script type="text/javascript" src="{sugar_getjspath file='cache/include/javascript/sugar_grp_yui_widgets.js'}"></script>
                    <script type="text/javascript" src="{sugar_getjspath file='modules/Emails/javascript/vars.js'}"></script>
                    <script type="text/javascript" src="{sugar_getjspath file='cache/include/javascript/sugar_grp_emails.js'}"></script>
                    <script type="text/javascript" src="{sugar_getjspath file='modules/Users/User.js'}"></script>
