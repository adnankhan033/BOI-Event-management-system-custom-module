<?php

namespace Drupal\custom_hooks\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AlertCommand;
use Drupal\Core\Database\Database;
use Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Url;
use Drupal\Component\Render\FormattableMarkup;  
use Symfony\Component\HttpFoundation\JsonResponse;
/**
* Class AjaxHelperController.
*/
class AjaxHelperController extends ControllerBase {
    
    /**
    * Attendee.
    *
    * @return string
    *   Return Hello string.
    */
    public function userExistsByMail() {
        if(isset($_POST['mail']))
        {
            $user = user_load_by_mail($_POST['mail']);
            if($user)
            {
                return new JsonResponse(['status'=>0, 'error'=>'user already exists', 'code'=>'exists', 'mail'=>$_POST['mail']]);
            }else{
                return new JsonResponse(['status'=>1,'code'=>'not_exists', 'mail'=>$_POST['mail']]);
            }
        }else{
            return new JsonResponse(['status'=>0, 'error'=>'Mail is not provided', 'code'=>'mail_not_set', 'mail'=>$_POST['mail']]);
        }
    }
    
    
}