<?php
/**
 LICENSE

 Copyright (C) 2016 Teclib'
 Copyright (C) 2010-2016 by the FusionInventory Development Team.

 This file is part of Flyve MDM Plugin for GLPI.

 Flyve MDM Plugin for GLPi is a subproject of Flyve MDM. Flyve MDM is a mobile
 device management software.

 Flyve MDM Plugin for GLPI is free software: you can redistribute it and/or
 modify it under the terms of the GNU Affero General Public License as published
 by the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.
 Flyve MDM Plugin for GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU Affero General Public License for more details.
 You should have received a copy of the GNU Affero General Public License
 along with Flyve MDM Plugin for GLPI. If not, see http://www.gnu.org/licenses/.
 ------------------------------------------------------------------------------
 @author    Thierry Bugier Pineau
 @copyright Copyright (c) 2016 Flyve MDM plugin team
 @license   AGPLv3+ http://www.gnu.org/licenses/agpl.txt
 @link      https://github.com/flyve-mdm/flyve-mdm-glpi
 @link      http://www.glpi-project.org/
 ------------------------------------------------------------------------------
 */

namespace Glpi\Test;
use Session;
use Html;
use DB;
use Auth;

abstract class CommonTestCase extends CommonDBTestCase
{
   protected $str = null;

   public function beforeTestMethod($method) {
      self::resetGLPILogs();
   }

   protected function resetState() {
      self::resetGLPILogs();

      $DBvars = get_class_vars('DB');
      $result = $this->drop_database(
         $DBvars['dbuser'],
         $DBvars['dbhost'],
         $DBvars['dbdefault'],
         $DBvars['dbpassword']
      );

      $result = $this->load_mysql_file($DBvars['dbuser'],
         $DBvars['dbhost'],
         $DBvars['dbdefault'],
         $DBvars['dbpassword'],
         './save.sql'
      );
   }

   protected function resetGLPILogs() {
      // Reset error logs
      file_put_contents(GLPI_LOG_DIR."/sql-errors.log", '');
      file_put_contents(GLPI_LOG_DIR."/php-errors.log", '');
   }

   protected function setupGLPIFramework() {
      global $CFG_GLPI, $DB, $LOADED_PLUGINS, $PLUGIN_HOOKS, $AJAX_INCLUDE, $PLUGINS_INCLUDED;

      if (session_status() == PHP_SESSION_ACTIVE) {
         session_write_close();
      }
      $LOADED_PLUGINS = null;
      $PLUGINS_INCLUDED = null;
      $AJAX_INCLUDE = null;
      $_SESSION = array();
      $_SESSION['glpi_use_mode'] = Session::NORMAL_MODE;       // Prevents notice in execution of GLPI_ROOT . /inc/includes.php
      if (is_readable(GLPI_ROOT . "/config/config.php")) {
         $configFile = "/config/config.php";
      } else {
         $configFile = "/inc/config.php";
      }
      include (GLPI_ROOT . $configFile);
      require (GLPI_ROOT . "/inc/includes.php");

      $DB = new DB();

      include_once (GLPI_ROOT . "/inc/timer.class.php");

      // Security of PHP_SELF
      $_SERVER['PHP_SELF'] = Html::cleanParametersURL($_SERVER['PHP_SELF']);

      ini_set("memory_limit", "-1");
      ini_set("max_execution_time", "0");

      if (session_status() == PHP_SESSION_ACTIVE) {
         session_write_close();
      }
      ini_set('session.use_cookies', 0); //disable session cookies
      session_start();
      $_SESSION['MESSAGE_AFTER_REDIRECT'] = [];
   }

   protected function login($name, $password, $noauto = false) {
      Session::start();
      $_SESSION['glpi_use_mode'] = Session::NORMAL_MODE;
      $auth = new Auth();
      $result = $auth->Login($name, $password, $noauto);
      $_SESSION['MESSAGE_AFTER_REDIRECT'] = [];
      $this->setupGLPIFramework();

      return $result;
   }

   public function afterTestMethod($method) {
      // Check logs
      $fileSqlContent = file_get_contents(GLPI_LOG_DIR."/sql-errors.log");
      $filePhpContent = file_get_contents(GLPI_LOG_DIR."/php-errors.log");

      $class = static::class;
      $class = str_replace('\\', '_', $class);
      if ($fileSqlContent != '') {
         rename(GLPI_LOG_DIR."/sql-errors.log", GLPI_LOG_DIR."/sql-errors__${class}__$method.log");
      }
      if ($fileSqlContent != '') {
         rename(GLPI_LOG_DIR."/php-errors.log", GLPI_LOG_DIR."/php-errors__${class}__$method.log");
      }

      // Reset log files
      self::resetGLPILogs();

      // Test content
      $this->variable($fileSqlContent)->isEqualTo('', 'sql-errors.log not empty');
      $this->variable($filePhpContent)->isEqualTo('', 'php-errors.log not empty');
   }

   protected function loginWithUserToken($userToken) {
      // Login as guest user
      $_REQUEST['user_token'] = $userToken;
      Session::destroy();
      self::login('', '', false);
      unset($_REQUEST['user_token']);
   }

   /**
    * Get a unique random string
    */
   protected function getUniqueString() {
      if (is_null($this->str)) {
         return $this->str = uniqid('str');
      }
      return $this->str .= 'x';
   }

   protected function getUniqueEmail() {
      return $this->getUniqueString() . "@example.com";
   }

   public function getMockForItemtype($classname, $methods = []) {
      // create mock
      $mock = $this->getMockBuilder($classname)
                   ->setMethods($methods)
                   ->getMock();

      //Override computation of table to match the original class name
      // see CommonDBTM::getTable()
      $_SESSION['glpi_table_of'][get_class($mock)] = getTableForItemType($classname);

      return $mock;
   }

   protected function terminateSession() {
      if (session_status() == PHP_SESSION_ACTIVE) {
         session_write_close();
      }
   }

   protected function restartSession() {
      if (session_status() != PHP_SESSION_ACTIVE) {
         session_start();
         session_regenerate_id();
         session_id();
         //$_SESSION["MESSAGE_AFTER_REDIRECT"] = [];
      }
   }
}
