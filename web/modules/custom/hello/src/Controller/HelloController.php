<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\user\UserInterface;

class HelloController extends ControllerBase {

    public function content($param) {
        $message = $this->t('You are on the Hello page. Your name is @username! @param', [
            '@username' => $this->currentUser()->getAccountName() ? $this->currentUser()->getAccountName() : $this->t('guest'),
            '@param' => $param,
          ]);
        
          return ['#markup' => $message];
    }
}