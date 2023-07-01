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
use Drupal\Component\Serialization\Json;
/**
 * Class DefaultController.
 */
class DefaultController extends ControllerBase {

    /**
   * Attendee.
   *
   * @return string
   *   Return Hello string.
   */
    public function hello() {


    }



    public function attendee() {



        $render_service = \Drupal::service('renderer');

        $_SESSION['otp'] = [
            //            'otp' => 1234,
            //            'mail' => $email,
            'verified' => false
        ];

        $entity = \Drupal::entityManager()
            ->getStorage('user')
            ->create(array());

        $formObject = \Drupal::entityManager()
            ->getFormObject('user', 'register')
            ->setEntity($entity);



        //        $formObject->set
        $form = \Drupal::formBuilder()->getForm($formObject);

        $form['#attached']['library'][] = 'custom_hooks/customhooks_lib2';

        //   multistep one section
        $form['groupone'] = [
            '#type' => 'fieldset',
        ]; 

        //   multistep two section
        $form['grouptwo'] = [
            '#type' => 'fieldset',
        ]; 
        //   multistep three section
        $form['groupthree'] = [
            '#type' => 'fieldset',
        ];
        //        $form['grouptwo']['pass'] = $form['pass'];

        $form['groupone']['info'] =[
            '#markup' => '<h3 class="custom-p-info">PERSONAL INFORMATION</h3>',
            '#weight' =>-13,
        ];
        global $base_url;
        $user_back_login = $base_url.'/user/login';

        //        ** attendee create 4 step **//
        $form['groupfour'] = [
            '#type' => 'fieldset',
        ];
        $form['groupthree']['info'] =[
            '#markup' => '<h3 class="custom-p-info set-pass">SETUP PASSWORD</h3>',
            //            '#weight' =>-9,
        ];

        $form['groupthree']['pass'] = $form['pass'];


        $form['groupthree']['back-three1'] =[
            '#markup' => "<a  class='btn back-two btn btn-default unique-back' id='three'>BACK</a>",
            '#weight' => 55500,
        ];
        //***     third setep password set section ***//
        $form['grouptwo']['nextpass'] =[
            '#markup' => "<a  class='btn ' id='pin-next'>next</a>",
            '#weight' => 0,
        ];
        $form['grouptwo']['back-two'] =[
            '#markup' => "<a  class='btn back-two unique-back' id='two'>BACK</a>",
            '#weight' => 1,
        ];

        //                $form['groupone']['name'] = $form['name'];
        //        $form['groupone']['infoback'] =[
        //            '#markup' => '<h3 class="user-reg-back"><a href="'.$user_back_login.'">Back</a></h3>',
        //            '#weight' =>20,
        //        ];

        $form['groupone']['name'] = $form['name'];
        $form['groupone']['name']['#weight'] = -11;

        //        $form['groupone']['name']['widget'][0]['value']['#title'] = "";
        $form['groupone']['name']['#attributes']['placeholder']= t('User Name');

        $form['groupone']['field_first_name'] = $form['field_first_name'];
        $form['groupone']['field_first_name']['#weight'] = -10;
        $form['groupone']['field_first_name']['widget'][0]['value']['#attributes']['placeholder']= t('First Name');

        $form['groupone']['field_last_name'] = $form['field_last_name'];
        $form['groupone']['field_last_name']['#weight'] = -9;
        $form['groupone']['field_last_name']['widget'][0]['value']['#attributes']['placeholder']= t('Last Name');

        $form['groupone']['field_last_namecnic'] = [
            '#title' => 'Select CNIC or Passport',
            '#type'=> 'select',
            '#options' =>['CNIC','Passport'],
            '#weight' =>-8,
            '#attributes' =>array('placeholder' => t('****')),

        ];

        //        $form['groupone']['field_cnic'] = [$form['field_cnic'],'#weight' =>-4];
        $form['groupone']['field_cnic'] = $form['field_cnic'];
        $form['groupone']['field_cnic']['#weight'] = -7;
        $form['groupone']['field_cnic']['widget'][0]['value']['#attributes']['placeholder']= t('xxxxxxxxxxxxxxx');

        //        $form['groupone']['field_passport'] = [$form['field_passport'],'#weight' =>-3];
        //        $form['groupone']['field_passport'] = $form['field_passport'];
        $form['groupone']['field_passport'] = $form['field_passport2'];
        $form['groupone']['field_passport']['#weight'] = -6;
        $form['groupone']['field_passport']['widget'][0]['value']['#attributes'] = array('class' => array('states-bbq-selector'));

        //        $form['groupone']['field_passport']['widget'][0]['value']['#attributes']['placeholder']= t('xxx-xxxxxx');

        $form['groupone']['mail'] = [$form['mail'],'#weight' =>-4];
        $form['groupone']['mail'] = $form['mail'];
        $form['groupone']['mail']['#weight'] = -4;
        $form['groupone']['mail']['#attributes']['placeholder']= t('example@gmail.com');


        //        $form['groupone']['field_designation'] = [$form['field_designation'],'#weight' =>-1];
        $form['groupone']['field_designation'] = $form['field_designation'];
        //        kint($form['groupone']['field_designation']);
        //        $form['groupone']['field_designation']['widget'][0]['target_id']['#attributes']['placeholder']= t('Vice President BOI');
        $form['groupone']['field_designation'] = $form['field_designation_attendee_'];
        $form['groupone']['field_designation']['#weight'] = -3;
        $form['groupone']['field_designation']['widget'][0]['value']['#attributes']['placeholder']= t('Designation');

        $form['groupone']['field_nationality'] = $form['field_nationality'];
        $form['groupone']['field_nationality']['#weight'] = -5;

        $form['groupone']['field_contact_number2'] = $form['field_contact_number2'];
        $form['groupone']['field_contact_number2']['#weight'] = -1;
        $form['groupone']['field_contact_number2']['widget'][0]['value']['#attributes']['placeholder']= t('Contact Number');

        $form['groupone']['field_fax_number'] = $form['field_fax_number'];
        $form['groupone']['field_fax_number']['#weight'] = -2;
        $form['groupone']['field_fax_number']['widget'][0]['value']['#attributes']['placeholder']= t('Fax Number');


        //        kint
        //            ($form['groupone']['field_fax_number']['widget'][0]['value']['#attributes'] =[
        //            'max'=>'12',
        //]); 
        //        exit;
        $form['groupone']['next'] =[
            '#markup' => "<a  class='btn first-next'>next</a>",
            '#weight' => 0,
        ];
        $form['groupone']['nextbacknew'] =[
            '#markup' => '<h3 class="one-back btn btn-default unique-back"><a href="'.$user_back_login.'">Back</a></h3>',
            '#weight' => 1,
        ];





        //  mutiple step two
        $form['grouptwo']['companyInfo'] =[
            '#markup' => '<h3 class="custom-p-info">COMPANY INFORMATION</h3>',
            '#weight' => -20,

        ];  

        //                 $form['grouptwo']['backnew'] =[
        //                    '#markup' => '<h3 class="custom-p-info">BACK</h3>',
        //                    '#weight' => -19,
        //        
        //                ];  



        $form['grouptwo']['field_company_name'] = $form['field_company_name'];   
        $form['grouptwo']['field_company_name']['#weight'] = -19;  
        $form['grouptwo']['field_company_name']['widget'][0]['value']['#title'] = "Company Name";  
        $form['grouptwo']['field_company_name']['widget'][0]['value']['#attributes']['placeholder']= t('Company Name');

        $form['grouptwo']['field_company_registration_numbe'] = $form['field_company_registration_numbe'];
        $form['grouptwo']['field_company_registration_numbe']['#weight'] = -18;
        $form['grouptwo']['field_company_registration_numbe']['widget'][0]['value']['#title'] = "Company Registration Number";
        $form['grouptwo']['field_company_registration_numbe']['widget'][0]['value']['#attributes']['placeholder']= t('Company Registration Number');

        $form['grouptwo']['field_company_profile1'] = $form['field_company_profile1'];   
        $form['grouptwo']['field_company_profile1']['#weight'] = -17; 
        $form['grouptwo']['field_company_profile1']['widget'][0]['value']['#title'] = "";
        $form['grouptwo']['field_company_profile1']['widget'][0]['value']['#attributes']['placeholder']= t('Company Profile');

        $form['grouptwo']['field_company_address'] = $form['field_company_address'];   
        $form['grouptwo']['field_company_address']['#weight'] = -16; 
        $form['grouptwo']['field_company_address']['widget'][0]['value']['#title'] = "Company Address";
        $form['grouptwo']['field_company_address']['widget'][0]['value']['#attributes']['placeholder']= t('Company Address');

        $form['grouptwo']['field_attachment'] = $form['field_attachment'];   
        $form['grouptwo']['field_attachment']['#weight'] = -14; 

        $form['grouptwo']['field_sector'] = $form['field_sector'];   
        $form['grouptwo']['field_sector']['#weight'] = -15;
        $form['grouptwo']['field_sector']['widget'][0]['value']['#title'] = "";
        $form['grouptwo']['field_sector']['widget'][0]['value']['#attributes']['placeholder']= t('Sector');


        //        kint($form['grouptwo']['field_company_registration_numbe']);
        //        unset($form['field_company_registration_numbe']);
        //        kint($form['grouptwo']['field_company_registration_numbe']);
        //        17
        //            16
        //            15
        //        14



        //email varification                   Verify with Email
        $form['grouptwo']['varifyemail'] =[
            '#markup' => "<div class='varifyemail btn btn-success'> <a>VERIFY WITH EMAIL ADDRESS</a></div>",
            '#weight' => -13,

        ]; 

        $form['grouptwo'] ['customtext'] = [
            '#markup' => "<p class='customtext'>Dear please use the OTP to register</p>",
            '#weight' => -12,
        ];
        //kint($form['groupone']['name']); exit;

        $form['grouptwo']['previous1'] =[
            '#prefix' => '<div class="custom-code">',
            '#type' => 'password',
            '#title' => t('Please enter 4-digit PIN from Email sent to <span class="custom-email"></span>'),
            '#maxlength' => 4,
            '#attributes' =>array('placeholder' => t('****')),
            '#weight' => -11,
            '#suffix' => '</div>',
        ];
        $form['grouptwo']['otp-success'] =[

            '#markup' => "<p class='hidden otp-resent-message'>OTP code has been sent successfully</p>",
            '#weight' => -10,

        ];

        $form['grouptwo']['varifyemail2'] =[
            '#markup' => "<div class='varifyemail  resend'> <span class='custom-resent-text'>Resend Code</span><a>Click here</a></div><span class='custom-resent-seconds'></span>",
            '#weight' => -9,

        ]; 




        //        otp code 
        //        $form['a']['resend_otp'] = array(
        //            '#type' => 'button',
        //            '#value' => t('Click Here'),
        //            '#weight' => -8,
        //
        //            '#ajax' => array(
        //                'callback' => '::event_resend_otp',
        ////                'event' => 'change',
        ////                'wrapper' => 'msg-div',
        ////                'method' => 'replace',
        ////                'effect' => 'fade',
        //            ),
        //            '#prefix' => '<div id="msg-div" class="resend-otp">Resend Code', 
        //            '#suffix' => '</div>',
        //        );




        //        $form['grouptwo']['submit'] = $form['actions']['submit'];
        $form['groupthree']['submit'] = $form['actions']['submit'];


        $form['#validate'] = [];
        $form['actions']['submit']['#validate'] = [];
        //        array_unshift($form['#validate'], 'username_from_otherfields');
        //        kint($form['groupthree']['submit']['#submit'], 'username_from_otherfields');
        //        
        //        exit;


        $form['groupthree']['submit']['#weight'] = 300;

        $form['groupone']['submit']['#value'] = 'confirm';

        //        $form['grouptwo']['submit']['#submit'] = ['mycustomfunctiont'];
        //        $form['grouptwo']['submit']['#validate'] =  ['mycustomfunctiont'];


        unset($form['actions']['submit']);
        //        unset($form['name']);
        unset($form['pass']);
        unset($form['field_last_name']);
        unset($form['field_first_name']);
        unset($form['field_designation_attendee_']);
        unset($form['field_last_namecnic']);
        unset($form['field_passport']);
        unset($form['field_passport2']);
        unset($form['field_cnic']);
        unset($form['mail']);
        unset($form['field_designation']);
        unset($form['field_company_name']);
        unset($form['field_company_registration_numbe']);
        unset($form['field_nationality']);
        unset($form['field_contact_number2']);
        unset($form['field_fax_number']);
        unset($form['field_company_profile1']);
        unset($form['field_company_address']);
        unset($form['field_attachment']);
        unset($form['field_sector']);
        unset($form['next']);


        //        $form = \Drupal::formBuilder()->getForm('\Drupal\user\RegisterForm');

        $formRendered = $render_service->render($form);
        //                        kint($formRendered);
        //                                kint($form); exit;



        $output = [];
        $output['mail'] = [
            '#markup' =>  \Drupal\Core\Render\Markup::create($formRendered)
        ];


        //        kint($form);

        return $output;
    }



    public function otpGenerate()
    {


        global $user;
        $otp = $num = str_pad(rand(0,9999),4,'0',STR_PAD_LEFT);
        $session = \Drupal::request()->getSession();
        /*** SET SESSION ***/
        $session->set('otp', 1247);

        $email =  $_POST['mail'];
        $username =  $_POST['name'];
        $_SESSION['otp'] = [
            'otp' => $otp,
            'mail' => $email,
            'verified' => false
        ];



        $session->set('mail', $email);
        $session->save();
        // $form['actions']['submit']['#attributes'] = array('class' => 'custom-save'); 
        //        $username = $arr['adnan'];

        //        $params['message'] = $text;
        $params['subject'] = 'Event Management Portal';
        $params['body'][] = $text;
        $text = '1 Dear  Please use the OTP asdf = <img src="//css-tricks.com/examples/WebsiteChangeRequestForm/images/wcrf-header.png" alt="Website Change Request" /> to Login the website"https://115.186.58.50/newboi-event-portal/"'.$otp;
        //        $params['body'][] = $text;

        $params['body'][] = ("Your's OTP code ".$otp);
        //                $params['body'][] = ('<p>Boi image <img src="http://115.186.58.50/newboi-event-portal/image-qr-generate/Hello%20World%20For%2CTesting"/>Start Your Text Here</p>');
        //        $params['body'][] = ('<p>Boi image <img src="http://115.186.58.50/newboi-event-portal/image-qr-generate/Hello%20World%20For%2CTesting"/>Start Your Text Here</p>');

        $mail =  customMailSend($email, $params);
        echo 'sent '.print_r(['otp'=>$otp, 'email'=>$email], 1);
        exit;



        //    kint($form_state);exit;

        //echo "<pre>"; print_r($session->get('otp'));exit;
        //        $name = $form_state->getValue("name");

        //        $user = user_load_by_name($name);
        //        $session->set('session-name', $name);
        //        $email = $user->getEmail();
        //        $id = $user->id(); 
        //        $session->set('login-id', $id);
        //        $session->set('login-email', $email);
        //kint($Id); exit;
        //        $arr = array('name' => $name, 'email' => $email, 'otp' => $otp);
        //sendEmail($name,$email,$otp);
        //        sendEmail($arr, 'user_login_opt');
        //        $form_state->setRedirect('multi_login.otp');
        //$submit_message = "Note Edited Successfully";
        //drupal_set_message($submit_message);
        //$form_state->setRebuild(TRUE);

    }




    public function verifyOtp(){


        //        print_r($_POST);
        //        exit;

        if(false){
            $timezone = drupal_get_user_timezone();
            $start = new \DateTime('now');

            $start->setTimezone(new \DateTimeZone(DateTimeItemInterface::STORAGE_TIMEZONE));
            $start = DrupalDateTime::createFromDateTime($start);

            $end = new \DateTime('now + 2 hour', new \DateTimezone($timezone));

            $end->setTimezone(new \DateTimeZone(DateTimeItemInterface::STORAGE_TIMEZONE));
            $end = DrupalDateTime::createFromDateTime($end);


            //        kint($start->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT));
            //        kint($end->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT));

            $query = \Drupal::entityQuery('node');
            $query->condition('type', 'new_event');
            $query
                ->condition('field_select_starting_date_time_', $start->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT), '>=')
                ->condition('field_select_starting_date_time_', $end->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT), '<=');
            $results = $query->execute();
            foreach($results as $nid)
            {
                $nodes = \Drupal::entityManager()->getStorage('node')->load($nid);
                //           kint($nodes->field_user); 
                kint($nodes->field_add_new_session->referencedEntities()[0]->field_attendee2); 



            }

            exit;

            $query = \Drupal::entityQuery('node');









            exit;

            $now = new \Drupal\Core\Datetime\DrupalDateTime('now');
            $now->setTimezone(new \DateTimeZone(\Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface::STORAGE_TIMEZONE));
            $nowPlus2 = new \Drupal\Core\Datetime\DrupalDateTime('+ 2 hour');
            kint($nowPlus2->__toString());
            $query = \Drupal::entityQuery('node');
            $query->condition('type', 'new_event');
            //        kint($now->format(\Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface::DATETIME_STORAGE_FORMAT));
            //        kint($nowPlus2->format(\Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface::DATETIME_STORAGE_FORMAT));
            //        exit;
            kint($now->format(\Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface::DATETIME_STORAGE_FORMAT));
            //        $query->condition('field_select_starting_date_time_', [], 'BETWEEN');


            //        kint($query);
            //        exit;

            //        $query->condition('field_select_starting_date_time_', $now->format(\Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface::DATETIME_STORAGE_FORMAT), '>=');
            //        $query->condition('field_select_starting_date_time_', $nowPlus2->format(\Drupal\datetime\Plugin\Field\FieldType\DateTimeItemInterface::DATETIME_STORAGE_FORMAT), '<=');
            //        $query->condition();
            $results = $query->execute();
            kint($results); exit;



            //    $node = \Drupal\node\Entity\Node::load(787);
            //        $hello  = $node->field_add_new_session->referencedEntities();
            //        kint($hello); exit;
            //        
        }

        $email = $_POST['email'];
        $otp = $_POST['otp'];
        //        header('Content-Type: application/json');
        //                print_r(['e'=>$email, 'o'=>$otp, 'se'=>$_SESSION['otp']]);


        if(isset($_SESSION['otp']['mail'], $_SESSION['otp']['otp'], $email, $otp) && ($email == $_SESSION['otp']['mail'] && $otp == $_SESSION['otp']['otp']))
        {
            if($_SESSION['otp']['verified']){
                return new JsonResponse(['status'=>0, 'msg'=>'Already verified']);
            }
            $_SESSION['otp']['verified'] = true;
            //            echo json_encode(['status'=>'1', 'msg'=>'verified']);
            return new JsonResponse(['status'=>1, 'msg'=>'verified']);

        }else{
            return new JsonResponse(['status'=>0, 'msg'=>'otp not matched']);
        }


        exit;

    }


    //Ajax otp code resent 
    public function event_resend_otp(array &$form, FormStateInterface $form_state){

        global $user;
        $otp = $num = str_pad(rand(0,9999),4,'0',STR_PAD_LEFT);
        $session = \Drupal::request()->getSession();
        /*** SET SESSION ***/
        $session->set('otp', $otp);

        //        $email =  $_POST['mail'];

        $session->set('login-email', $email);

        $params['subject'] = 'Event Management Portal';
        $params['body'][] = $text;
        $text = t('2 Dear  Please use the OTP to Login the image website"'.$otp.'" <img src="https://115.186.58.50/newboi-event-portal/image-qr-generate/Fahadi%20Da%20Niya%20Kus" />');
        $params['body'][] = $text;
        $params['body'][] = ('2 Dear  Please use the OTP to Login the image website"'.$otp.'" <img src="https://115.186.58.50/newboi-event-portal/image-qr-generate/Fahadi%20Da%20Niya%20Kus" />');

        $mail =  customMailSend($email, $params);


        echo 'sent';
        exit;


        $output = [];
        $output['a'] = ['#markup'=>"<span class='otp-resend'>OTP has been sent Successfully.</span>"];
        return $output;	
        drupal_set_message(t('Its done'), 'status', false);	
        $ajax_response = new AjaxResponse();

        //$ajax_response->addCommand(new AlertCommand("Please Enter Your Coupon Code If You have..."));
        return $ajax_response;
    }



    public function allrolestable(){


        //        $bundle = 'attendance'; // or $bundle='my_bundle_type';
        //        $query = \Drupal::entityQuery('node');
        //        $query->condition('status', 1);
        //        $query->condition('type', $bundle);
        //        $entity_ids = $query->execute();
        //
        //        $email_templates = \Drupal\node\Entity\Node::loadMultiple($entity_ids);
        //        foreach($email_templates as $key=> $mail_templates)
        //        {
        //
        //            $attendee[] = $mail_templates->field_select_event->getValue()[0]['target_id'];
        //          
        //    
        ////        kint($nodes);
        //        } 
        //        
        //
        //kint($node2); 
        //        exit;

        global $base_url;
        $newlinks[] = array(

            array('data' => new FormattableMarkup('<a href=":link">@name</a>', 
                                                  [':link' => 'https://www.google.com'/*$entry['link_url']*/, 
                                                   '@name' => 'Google'/*$entry['name']*/])
                 ),

        );

        $form = [];
        //for table
        $all_roles = [];

        $_all_roles =\Drupal::entityTypeManager()->getStorage('user_role')->loadMultiple();
        $heello =[];
        $sr = count($_all_roles);
        $serial_desc_order = $sr  -5+2;

        foreach ($_all_roles as $role_id => $role) {
            //skip these roles
            if(in_array($role->id(), ['anonymous', 'authenticated', 'administrator'] ))
            {
                continue;
            }

            $link = new FormattableMarkup('<a href=":link">@name</a>', 
                                          [':link' => $base_url.'/role/'.$role->id().'/edit',
                                           '@name' => 'Edit']);
            //            $all_roles[$role_id] = ["#"+$sr++, $role->label(), $link];
            $all_roles[$role_id] = ["#"+$serial_desc_order--, $role->label(), $link];
            $heello[] =$all_roles[$role_id];
        }
        $reverse_roles = array_reverse($all_roles, true);


        $form['headingallroles']= [
            '#markup'=> '<h2 class="view-header">ALL ROLES</h2>',
        ];
        $form['table']= array(
            '#theme' => 'table',
            //'#cache' => ['disabled' => TRUE],
            '#header' => ['SR#', 'Name','Edit'],
            '#rows' => $reverse_roles, 
            '#attributes'=> [
                'id' => ['example'],
            ],
        );


        //        $form
        return $form;

    }




}