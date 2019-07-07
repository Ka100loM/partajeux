<?php

    namespace Drupal\Hello\Access;

    use Drupal\Core\Session\AccountInterface;
    use Drupal\Core\Access\AccessResult;
    use Symfony\Component\Routing\Route;
    use Symfony\Component\HttpFoundation\Request;
    use Drupal\Core\Access\AccessCheckInterface;

    class ExampleAccessCheck implements AccessCheckInterface {
        
        public function applies(Route $route){
            return NULL;
        }

        public function access(Route $route, Request $request = NULL, AccountInterface $account){
            if($account->isAnonymous()){
                return AccessResult::forbidden();
            }else{
                $time = \Drupal::service('datetime.time')->getCurrentTime();
                $param = $route->getRequirement('_access_example')*3600;
                $accountCreated = $account->getAccount()->created;
                $diffTime = $time - $accountCreated;
                if($diffTime < $param  ){
                    return AccessResult::forbidden();
                }
                else{
                    return AccessResult::allowed();
                }
            }
         
            
        }

    }