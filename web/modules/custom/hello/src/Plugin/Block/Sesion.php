<?php

namespace Drupal\hello\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Provides  a sesion block.
 * 
 * @Block(
 *  id = "sesion_block",
 *  admin_label = @Translation("Sesion active")
 * )
 */
class Sesion extends BlockBase
{
    /**
     * Implements Drupal|Core|Block|BlockBase::build().
     */
    public function build() {   
        $database = \Drupal::database();
        $session = $database->select('sessions')->countQuery()->execute()->fetchField();
        $build = [
            '#markup' => $this->t('Il y a actuellement %ses sessions actives.', [
              '%ses' => $session,
            ]),
            '#cache' => [
              'keys' => ['sesion:block'],
              'contexts' => ['session'],
              'max-age' => '0',
            ],
          ];


        return $build;
    }

    protected function blockAccess(AccountInterface $account) {
      return AccessResult::allowedIfHasPermission($account,'access user_stat');
    }
}
