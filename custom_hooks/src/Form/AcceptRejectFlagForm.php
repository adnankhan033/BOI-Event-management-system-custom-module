<?php

namespace Drupal\custom_hooks\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CloseModalDialogCommand;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\node\Entity\Node;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Url;

/**
 * Class AcceptRejectFlagForm.
 */
class AcceptRejectFlagForm extends FormBase {

    /**
   * {@inheritdoc}
   */
    public function getFormId() {
        return 'accept_reject_flag_form';
    }

    /**
   * {@inheritdoc}
   */
    public function buildForm(array $form, FormStateInterface $form_state, $flagId=null, $status=null) {





        //load flag

        $flag = \Drupal\flag\Entity\Flagging::load($flagId);


        if(!$flag || !in_array($status, ['Approved', 'Rejected']))
        {

            $form['msg'] = [
                '#markup' => 'Flag Not found or status incorrect'
            ];
            return $form;
        }



        $flagStatus = $flag->field_status->getValue();

        //if value is set or it is not equal to not selected
        if( !(!isset($flagStatus[0]['value']) || $flagStatus[0]['value'] == "Not selected") )
        {
            $form['msg'] = [
                '#markup' => 'Already '.$flagStatus[0]['value']
            ];
            return $form;
        }


        $form_state->set("flagId", $flagId);
        $form_state->set("status", $status);


        if($status == 'Approved'){
            $form['header'] = [
                //            '#markup' => '<div class="request-modal-header">Confirmation</div><div class="request-modal-quest">Are you sure you want to <span class=custom-status-appr-rej>'.$status.'</span> his request?</div>',
                '#markup' => '<div class="request-modal-header">Confirmation</div><div class="request-modal-quest">Are you sure you want to <span class=custom-status-appr-rej> approve </span> his request?</div>',
            ];
        }
        else{
            $form['header'] = [
                '#markup' => '<div class="request-modal-header">Rejection</div><div class="request-modal-quest">Are you sure you want to <span class=custom-status-appr-rej> reject </span> his request?</div>',
            ];
        }


        $form['msg'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Comments (if any)'),
            '#placeholder' => $this->t('Type here...'),
            //      '#maxlength' => 64,
            //      '#size' => 64,
            '#weight' => '0',
        ];

        $form['cancel'] = [
            '#markup' => '<a class="cancel-dialoge btn btn-default btn-lg request-modal-no"  href="#" aria-label="Close" data-dismiss="modal">No</a>'
        ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Yes'),
            '#ajax' => [
                'callback' => '::save',
            ],
            '#attributes' => [
                'class' => ['btn btn-primary btn-lg request-modal-yes']
            ]
        ];

        return $form;
    }

    function save(array &$form, FormStateInterface $form_state)
        //    {
        //        $response = new AjaxResponse();
        //        $response->addCommand(new CloseModalDialogCommand());
        //        return $response;
        //
        //        $mailSent = $form_state->get("emailSent");
        //        $mailSentMsg = $mailSent?"Mail sent":"Mail sending failed";
        //
        //
        //        if( $form_state->get('flagUpdate') )
        //        {
        //            $response->addCommand(new OpenModalDialogCommand("Success", '<div class="info info-success">Updated </div>'."<div>$mailSentMsg</div>", ["dialogClass"=>'updateviews event-request-modal']));
        //        }else{
        //            $response->addCommand(new OpenModalDialogCommand("Failure", '<div class="info info-danger">Not Updated</div>'));
        //        }
        //        return $response;
        //    }

    {


        $response = new AjaxResponse();
        $response->addCommand(new CloseModalDialogCommand());


        $mailSent = $form_state->get("emailSent");
        $mailSentMsg = $mailSent?"Mail sent":"Mail sending failed";
        if( $form_state->get('flagUpdate') )
        {
            //        drupal_set_message(t("Event request has been submitted"));


            $response->addCommand(new OpenModalDialogCommand("", '<div class="info info-success event-sent">Event Request has been sent </div>'."<div></div>", ["dialogClass"=>'updateviews event-request-modal']));
            $response->addCommand(new InvokeCommand(null, 'reloadPage'));


        }else{
            $response->addCommand(new OpenModalDialogCommand("Failure", '<div class="info info-danger event-failled">Not Updated</div>'));
        }




        return $response;
    }

    /**
   * {@inheritdoc}
   */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        foreach ($form_state->getValues() as $key => $value) {
            // @TODO: Validate fields.
        }
        parent::validateForm($form, $form_state);
    }

    /**
   * {@inheritdoc}
   */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $form_state->set('msgsgs', 'set');
        $status = $form_state->get('status');
        $flagId = $form_state->get('flagId');
        $msg = $form_state->getValue("msg");
        $flag = \Drupal\flag\Entity\Flagging::load($flagId);



        $to = $ownerEmail = $flag->getOwner()->getEmail();
        $node_rejection = \Drupal::entityManager()->getStorage('node')->load(1046);
        $node_approval = \Drupal::entityManager()->getStorage('node')->load(1045);

        $reject_subject  = $node_rejection->getTitle(); 
        $approved_subject = $node_approval->getTitle();

        if($status =='Approved'){
            $text = t('Approved');
            $params['subject'] = $approved_subject;
            //        $params['subject'] = 'Request '.$flagId;
            $params['body'][] = t($msg);
            //            $params['body'][] = t("Status ". $status);
            $mail =  customMailSend($to, $params);
        }

        if($status =='Rejected'){
            $text = t('Rejected');
            $params['subject'] = $reject_subject;
            $params['body'][] = t($msg);
            $mail =  customMailSend($to, $params);
        } 






        $form_state->set("emailSent", $mail['result']);



        $flag->field_status->setValue($status);
        $abc =  $flag->field_message->setValue($msg);
        $flag->save();
        $form_state->set("flagUpdate", true);
        print_r($abc);



    }

}
