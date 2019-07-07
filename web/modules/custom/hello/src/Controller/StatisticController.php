<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\user\UserInterface;
use Drupal\Core\Database\Database;

class StatisticController extends ControllerBase {

    public function content($user) {

        $get_user =\Drupal::database()->select('hello_user_statistics', 'h')->condition('h.uid', $user, '=')->fields('h', ['time', 'action'])->execute()->fetchAll();
        $time = \Drupal::service('date.formatter');
        $row = [];
        foreach($get_user as $item){
            $row[] = [$item->action == '1' ? $this->t('Login') : $this->t('Logout'), $time->format($item->time, 'custom', 'D, d/m/y - H:i\m')]; 
        }
        $user = $this->currentUser()->getAccountName();
       
        $count = \Drupal::database()->select('hello_user_statistics','u')->condition('u.uid', $this->currentUser()->id(), '=')->countQuery()->execute()->fetchField();
       
     
        $topmess = ['#theme' => 'hello_user_conexion',
                    '#user' => $this->t($user),
                    '#count' => $this->t($count),
                ];

        $table =  ['#type' => 'table',
                    '#header' => [$this->t('Action'), $this->t('Time')],
                    '#rows' => $row,
                    ];
        
       return  [$topmess, $table];
    }


}