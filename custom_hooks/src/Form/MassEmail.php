<?php

namespace Drupal\custom_hooks\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\NodeType;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\Core\Entity\EntityInterface;

/**
 * Class MassEmail.
 */
class MassEmail extends FormBase {

    /**
   * {@inheritdoc}
   */
    public function getFormId() {
        return 'mass_email';
    }

    /**
   * {@inheritdoc}
   */
    public function buildForm(array $form, FormStateInterface $form_state, $id=NULL) {


        $value = \Drupal::routeMatch()->getParameter('id');


        // ***** Get all email templates ****//
        $bundle = 'new_template'; // or $bundle='my_bundle_type';
        $query = \Drupal::entityQuery('node');
        $query->condition('status', 1);
        $query->condition('type', $bundle);
        $entity_ids = $query->execute();

        $email_templates = \Drupal\node\Entity\Node::loadMultiple($entity_ids);


        foreach($email_templates as $mail_templates)
        {

            $id = $mail_templates->id();
            $templates[$id] = $mail_templates->getTitle();
        } 

        // ***** end all email templates ****//


        $path = $current_path = \Drupal::service('path.current')->getPath();
        $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
        $arg  = explode('/',$path);  
        $event_id = $arg[4];
        $specific_nodes = \Drupal::entityTypeManager()->getStorage('node')->load($event_id);
        $node_title = $specific_nodes->getTitle();


        $event = $specific_nodes->field_add_new_session->referencedEntities();


        $field_travel_type = $specific_nodes->get('field_add_new_session')->getValue();

        foreach ($field_travel_type as $element ) {

            $paragraph_event = \Drupal\paragraphs\Entity\Paragraph::load( $element['target_id'] );

            $attendee = $paragraph_event->field_attendee2->getValue()[0]['target_id'];

            $field_focal_person = $paragraph_event->field_focal_person->getValue()[0]['target_id'];

            $field_speakers = $paragraph_event->field_speakers->getValue()[0]['target_id'];

            $chair_p = $paragraph_event->field_chair_person_new->getValue();

            $speakers = $paragraph_event->field_speakers->getValue();

            $sub_organizer = $paragraph_event->field_add_sub2->getValue();

            //kint($paragraph_event); exit;


            $attendee_email = \Drupal\user\Entity\User::load($attendee)->getEmail();

            $field_focal_person_mail = \Drupal\user\Entity\User::load($field_focal_person)->getEmail();


            $account = \Drupal\user\Entity\User::load($chair_p); // pass your uid

            foreach($chair_p as $chair_key => $chair_person)
            {
                $chair_p_id = $chair_person['target_id'];

                $chair_p_mails[$chair_key]  = \Drupal\user\Entity\User::load($chair_p_id)->getEmail();


            } 





            foreach($sub_organizer as $sub_key => $sub_organizers)
            {



                //                                $id = $sub_organizers->id();
                //
                $sub_org_id = $sub_organizers['target_id'];
                //                
                $sub_ogranizer_load = \Drupal\user\Entity\User::load($sub_org_id); // pass your uid    
                //
                //                $organizer_mails = $sub_ogranizer_load->getEmail();
                //
                //
                ////                kint($id);
                //                kint($id);

            } 







        } 





        $form['back'] = array(
            '#markup' => '<div class="btn btn-default custom-back"><a href="https://115.186.58.50/boi-event-portal/new-event-request/'.$value.'">Back</a></div>',
        );

        $form['e-shop']['vertical_tabs'] = array(
            '#type' => 'vertical_tabs',
            '#default_tab' => 'edit-tab1',
        );
        $form['tab1'] = array(
            '#type' => 'fieldset',
            '#title' => t('Group  Title '),
            '#collapsible' => TRUE,
            '#group' => 'one',
        );
        //        $form['tab2'] = array(
        //            '#type' => 'fieldset',
        //            '#title' => t('Group Three '),
        //            '#collapsible' => TRUE,
        //            '#group' => 'two',
        //        );  
        //        $form['tab2'] = array(
        //            '#type' => 'fieldset',
        //            '#title' => t('Group Three '),
        //            '#collapsible' => TRUE,
        //            '#group' => 'three',
        //        ); 
        //        $form['tab'] = array(
        //            '#type' => 'fieldset',
        //            '#title' => t('Group Four '),
        //            '#collapsible' => TRUE,
        //            '#group' => 'three',
        //        );  



        $form['tab1']['event_name'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Event Name'),
            '#value' => $node_title,
        );

        $form['tab1']['event_name']['#attributes'] = array('disabled' => 'disabled');


        //        $form['tab1']['select_role'] = array(
        //            '#type' => 'select2',
        //            '#title' => $this->t('Select Role'),
        //        );

        $form['tab1']['templates'] = array(
            '#type' => 'select2',
            '#title' => $this->t('Select Template'),
            '#options' => $templates,
        );  
        $form['tab1']['subject'] = array(
            '#type' => 'textfield',
            '#title' => $this->t('Subject'),

        );

        $form['tab1']['body'] = array(
            '#type' => 'textarea',
            '#title' => $this->t('Body'),

        ); 


        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
        ];




        return $form;


        return $form;
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

        // Display result.
        foreach ($form_state->getValues() as $key => $value) {
            \Drupal::messenger()->addMessage("Mass Email Send Successfully..");
        }

        $event_name = $form_state->getValue('event_name');

        //        $select_role = $form_state->getValue('select_role');

        $templates_id = $form_state->getValue('templates');

        $select_template_by_id = \Drupal\node\Entity\Node::load($templates_id);

        $email_subject  = $select_template_by_id->title->getValue()[0]['value'];

        $email_body  = $select_template_by_id->field_comments_if_any_->getValue()[0]['value']; 

        $subject = $form_state->getValue('subject');

        $body = $form_state->getValue('body');
        
        $mail ="adnan.drupl@gmail.com";
        $params = [];
        $params['subject'] = $subject;
        $params['subject'] = $email_subject;
        $text = $email_body;
        $params['body'][] = $body;
        $params['body'][] = $email_body;
        $params['body'][] = ('<p>'.$event_name.'</p>');



        $mail =  customMailSend($mail, $params); 


    }

}
