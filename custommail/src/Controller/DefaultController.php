<?php

namespace Drupal\custommail\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class DefaultController.
 */
class DefaultController extends ControllerBase {

    /**
   * Testingmail.
   *
   * @return string
   *   Return Hello string.
   */
    public function testingMail() {
        $form = [];
        $username = $arr['adnan'];
        //        $username = 'adnan';
        //        $email = $arr['adnan.drupl@gmail.com'];
        $email = 'adnan.drupl@gmail.com';

        $otp = '1111';


        $to = 'adnan.drupl@gmail.com';
        //        $to = $email;
        //        $text = t('Dear "'.$username.'" Please use the OTP to Login the website"'.$otp.'"');
        $text = t('asdf');

        //        $params['message'] = $text;
        $params['subject'] = 'Event Management Portal';

        $params['body'][] = $text;
        $text = t(' q234');
        $params['body'][] = $text;


       $mail =  customMailSend($to, $params);





        return $form;
    }



    //function otp_mail($key, &$message, $params) {
    //
    //    switch ($key) {
    //        case 'sending_email_to_user':
    //            $message['from'] = \Drupal::config('system.site')->get('mail');
    //            $message['subject'] = t('@title', array('@title' => $params['subject']));
    //            $message['body'][] = $params['message'];
    //            break;
    //    }
    //}
}
