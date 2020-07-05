<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
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

/*********************************************************************************

 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

 require_once('modules/Users/views/view.edit.php');
 require_once('modules/Users/UserViewHelper.php');

class ViewWizard extends UsersViewEdit
{

	public $type = 'wizard';

 	function __construct(){
 		parent::__construct();
 		$this->options['show_header'] = false;
        $this->options['show_footer'] = false;
        $this->options['show_javascript'] = false;
 	}

	public function getEditView() {
		if(empty($this->ev)) {
			$this->ev = parent::getEditView();
			$this->ev->view = 'WizardView';
		}
		return $this->ev;
	}

    public function preDisplay()
    {
        parent::preDisplay();
    }

    function display() {

        global $current_user, $app_list_strings;

        $this->fieldHelper = new UserViewHelper($this->ss, $this->bean, 'EditView');
        $this->fieldHelper->setupAdditionalFields();

 		$themeObject = SugarThemeRegistry::current();
		$css = $themeObject->getCSS();
		$this->ss->assign('SUGAR_CSS', $css);
		$this->ss->assign('SUGAR_JS', $themeObject->getJS());
        $favicon = $themeObject->getImageURL('sugar_icon.ico',false);
        $this->ss->assign('FAVICON_URL',getJSPath($favicon));
        $this->ss->assign('CSS', '<link rel="stylesheet" type="text/css" href="'.$themeObject->getCSSURL('wizard.css').'" />');
//	    $this->ss->assign('JAVASCRIPT',user_get_validate_record_js().user_get_chooser_js().user_get_confsettings_js());

        // get javascript
        ob_start();
        $this->options['show_javascript'] = true;
        $this->renderJavascript();
        $this->options['show_javascript'] = false;
        $this->ss->assign("SUGAR_JS", ob_get_contents().$themeObject->getJS());
        ob_end_clean();

        // set default settings
        $use_real_names = $current_user->getPreference('use_real_names');
        if (empty($use_real_names)) {
            $current_user->setPreference('use_real_names', 'on');
        }
        $current_user->setPreference('reminder_time', 1800);
        $current_user->setPreference('email_reminder_time', 3600);
        $current_user->setPreference('mailmerge_on', 'on');

        //// Timezone
        if (empty($current_user->id)) { // remove default timezone for new users(set later)
            $current_user->user_preferences['timezone'] = '';
        }

        $userTZ = $current_user->getPreference('timezone');
        if (empty($userTZ) && !$current_user->is_group && !$current_user->portal_only) {
            $userTZ = TimeDate::guessTimezone();
            $current_user->setPreference('timezone', $userTZ);
        }

        parent::display();
    }
}
