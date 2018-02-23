<?php
namespace Flyvemdm\Test;

use Flyvemdm\Tests\Src\TestingCommonTools;

class CommonTestCase extends \Glpi\Test\CommonTestCase {

   /**
    * Try to enroll an device by creating an agent. If the enrollment fails
    * the agent returned will not contain an ID. To ensore the enrollment succeeded
    * use isNewItem() method on the returned object.
    *
    * @param \User $user
    * @param array $input enrollment data for agent creation
    * @return \PluginFlyvemdmAgent
    */
   protected function enrollFromInvitation(\User $user, array $input) {
      // Close current session
      $this->terminateSession();
      $this->restartSession();
      $this->setupGLPIFramework();

      // login as invited user
      $_REQUEST['user_token'] = \User::getToken($user->getID(), 'api_token');
      $this->boolean($this->login('', '', false))->isTrue();
      $this->setupGLPIFramework();
      unset($_REQUEST['user_token']);

      // Try to enroll
      $agent = new \PluginFlyvemdmAgent();
      $agent->add($input);

      return $agent;
   }

   /**
    * Create a new invitation
    *
    * @param string $guestEmail
    * @return \PluginFlyvemdmInvitation
    */
   private function createInvitation($guestEmail) {
      $invitation = new \PluginFlyvemdmInvitation();
      $invitation->add([
         'entities_id' => $_SESSION['glpiactive_entity'],
         '_useremails' => $guestEmail,
      ]);
      $this->boolean($invitation->isNewItem())->isFalse();

      return $invitation;
   }

   /**
    * @param string $userIdField
    * @return array
    */
   protected function createUserInvitation($userIdField) {
      // Create an invitation
      $serial = $this->getUniqueString();
      $guestEmail = $this->getUniqueEmail();
      $invitation = $this->createInvitation($guestEmail);
      $user = new \User();
      $user->getFromDB($invitation->getField($userIdField));

      return [$user, $serial, $guestEmail, $invitation];
   }

   /**
    * @param \User $user object
    * @param string $guestEmail
    * @param string|null $serial if null the value is not used
    * @param string $invitationToken
    * @param string $mdmType
    * @param string|null $version if null the value is not used
    * @param string $inventory xml
    * @return \PluginFlyvemdmAgent
    */
   protected function agentFromInvitation(
   $user,
   $guestEmail,
   $serial,
   $invitationToken,
   $mdmType = 'android',
   $version = '',
   $inventory = null
   ) {
      //Version change
      $finalVersion = \PluginFlyvemdmAgent::MINIMUM_ANDROID_VERSION
      if ($version) {
         $finalVersion = $version;
      }
      if (null === $version) {
         $finalVersion = null;
      }

      $finalInventory = (null !== $inventory) ? $inventory : TestingCommonTools::AgentXmlInventory($serial);

      $input = [
         'entities_id'       => $_SESSION['glpiactive_entity'],
         '_email'            => $guestEmail,
         '_invitation_token' => $invitationToken,
         'csr'               => '',
         'firstname'         => 'John',
         'lastname'          => 'Doe',
         'type'              => $mdmType,
         'inventory'         => $finalInventory,
      ];

      if ($serial) {
         $input['_serial'] = $serial;
      }
      if ($finalVersion) {
         $input['version'] = $finalVersion;
      }

      return $this->enrollFromInvitation($user, $input);
   }
}