<?php

namespace Flyvemdm\Test;

use Glpi\Test\ApiRestTestCase as GlpiApiRestTestCase;

class ApiRestTestCase extends GlpiApiRestTestCase
{

   protected function agent($method, $sessionToken, $body = '', $params = [], $appToken = null) {
      $headers = ['Session-Token' => $sessionToken];
      if ($appToken !== null) {
         $headers['App-Token'] = $appToken;
      }

      $this->emulateRestRequest($method, 'PluginFlyvemdmAgent', $headers, $body, $params);
   }

   protected function invitation($method, $sessionToken, $body = '', $params = [], $appToken = null) {
      $headers = ['Session-Token' => $sessionToken];
      if ($appToken !== null) {
         $headers['App-Token'] = $appToken;
      }

      $this->emulateRestRequest($method, 'PluginFlyvemdmInvitation', $headers, $body, $params);
   }

   protected function fleet($method, $sessionToken, $body = '', $params = [], $appToken = null) {
      $headers = ['Session-Token' => $sessionToken];      if ($appToken !== null) {
         $headers['App-Token'] = $appToken;
      }

      $this->emulateRestRequest($method, 'PluginFlyvemdmFleet', $headers, $body, $params);
   }

   protected function file($method, $sessionToken, $body = '', $params = [], $appToken = null) {
      $headers = ['Session-Token' => $sessionToken];      if ($appToken !== null) {
         $headers['App-Token'] = $appToken;
      }

      $this->emulateRestRequest($method, 'PluginFlyvemdmFile', $headers, $body, $params);
   }

   protected function fleet_policy($method, $sessionToken, $body = '', $params = [], $appToken = null) {
      $headers = ['Session-Token' => $sessionToken];      if ($appToken !== null) {
         $headers['App-Token'] = $appToken;
      }

      $this->emulateRestRequest($method, 'PluginFlyvemdmFleet_Policy', $headers, $body, $params);
   }

   protected function geolocation($method, $sessionToken, $body = '', $params = [], $appToken = null) {
      $headers = ['Session-Token' => $sessionToken];      if ($appToken !== null) {
         $headers['App-Token'] = $appToken;
      }

      $this->emulateRestRequest($method, 'PluginFlyvemdmGeolocation', $headers, $body, $params);
   }

   protected function invitationLog($method, $sessionToken, $body = '', $params = [], $appToken = null) {
      $headers = ['Session-Token' => $sessionToken];      if ($appToken !== null) {
         $headers['App-Token'] = $appToken;
      }

      $this->emulateRestRequest($method, 'PluginFlyvemdmInvitationLog', $headers, $body, $params);
   }

   protected function mqttLog($method, $sessionToken, $body = '', $params = [], $appToken = null) {
      $headers = ['Session-Token' => $sessionToken];      if ($appToken !== null) {
         $headers['App-Token'] = $appToken;
      }

      $this->emulateRestRequest($method, 'PluginFlyvemdmMqttLog', $headers, $body, $params);
   }

   protected function package($method, $sessionToken, $body = '', $params = [], $appToken = null) {
      $headers = ['Session-Token' => $sessionToken];      if ($appToken !== null) {
         $headers['App-Token'] = $appToken;
      }

      $this->emulateRestRequest($method, 'PluginFlyvemdmPackage', $headers, $body, $params);
   }

   protected function policy($method, $sessionToken, $body = '', $params = [], $appToken = null) {
      $headers = ['Session-Token' => $sessionToken];      if ($appToken !== null) {
         $headers['App-Token'] = $appToken;
      }

      $this->emulateRestRequest($method, 'PluginFlyvemdmPolicy', $headers, $body, $params);
   }

   protected function wellknownpath($method, $sessionToken, $body = '', $params = [], $appToken = null) {
      $headers = ['Session-Token' => $sessionToken];      if ($appToken !== null) {
         $headers['App-Token'] = $appToken;
      }

      $this->emulateRestRequest($method, 'PluginFlyvemdmWellknownpath', $headers, $body, $params);
   }

   protected function entityconfig($method, $sessionToken, $body = '', $params = [], $appToken = null) {
      $headers = ['Session-Token' => $sessionToken];      if ($appToken !== null) {
         $headers['App-Token'] = $appToken;
      }

      $this->emulateRestRequest($method, 'PluginFlyvemdmEntityconfig', $headers, $body, $params);
   }

   protected function accountvalidation($method, $sessionToken, $body = '', $params = [], $appToken = null) {
      $headers = ['Session-Token' => $sessionToken];
      $headers = ['Session-Token' => $sessionToken];      if ($appToken !== null) {
         $headers['App-Token'] = $appToken;
      }

      $this->emulateRestRequest($method, 'PluginFlyvemdmAccountValidation', $headers, $body, $params);
   }
}
