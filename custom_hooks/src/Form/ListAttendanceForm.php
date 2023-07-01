<?php

namespace Drupal\custom_hooks\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use \Drupal\node\Entity\Node;

/**
 * Class ListAttendanceForm.
 */
class ListAttendanceForm extends FormBase {

    /**
   * {@inheritdoc}
   */
    public function getFormId() {
        return 'listattendance_form';
    }

    /**
   * {@inheritdoc}
   */
    public function buildForm(array $form, FormStateInterface $form_state) {

        //        $view = \Drupal\views\Views::getView('attendance_history');
        //        exit;

        //                $data_result = db_select('node', 'n');
        //                    $data_result->join('node_field_data', 'nfd', 'nfd.nid=n.nid');
        //                    $data_result->fields('nfd', ['title', 'nid'])
        //                    ->condition('n.type', 'attachments');
        //                
        //                
        //                $attachments = $data_result->execute()->fetchAllAssoc(nid);

        $bundle = 'attachments'; // or $bundle='my_bundle_type';
        $query = \Drupal::entityQuery('node');
        $query->condition('status', 1);
        $query->condition('type', $bundle);
        $entity_ids = $query->execute();

        $attachments = \Drupal\node\Entity\Node::loadMultiple($entity_ids);     

        $new_bundle = 'new_attachments'; // or $bundle='my_bundle_type';
        $new_query = \Drupal::entityQuery('node');
        $new_query->condition('status', 1);
        $new_query->condition('type', $new_bundle);
        $entity_idss = $new_query->execute();

        $new_attachments = \Drupal\node\Entity\Node::loadMultiple($entity_idss);

        foreach($new_attachments as $attach_new_list){

            $attach_ids = $attach_new_list->id();
            $title_new[$attach_ids] = $attach_new_list->getTitle();

        } 




        //        kint($attachments[212]->getTitle());
        //        exit;

        //        $paragraph = $attachments[436]->field_attach_lists_paragraph;
        //        $value = $paragraph->referencedEntities()[0]->field_topic->getValue()[0]['value'];

        foreach($attachments as $attachment)
        {
            //            $title = $attachment->title;
            //            $optionss[$id] = $this->t($title);
            $id = $attachment->id();
            $title = $attachment->getTitle();
            $paragraph = $attachment->field_attach_lists_paragraph;
            $value = $paragraph->referencedEntities()[0]->field_topic->getValue()[0]['value'];
            $optionss[$id] = $value?$this->t($value):"";



        } 





//        $result = db_select('node', 'n');
//        $result->fields('n', ['title'])
//        $result->join('node_field_data', 'nfd', 'nfd.nid=n.nid');
//        $result->fields('nfd', ['title', 'nid', 'status'])
//            ->condition('n.type', 'new_event')
//            -> condition('n.status', 1);
        
//        $events = $result->execute()->fetchAllAssoc('nid');
//        kint($events);
//        exit;
        $nids = \Drupal::entityQuery('node')->condition('type','new_event')->condition('status', 1)->execute();
        $events = \Drupal\node\Entity\Node::loadMultiple($nids);
 
        
        foreach($events as $event)
        {
            $id = $event->id();
            $title = $event->getTitle();
            $status = $event->isPublished();
            $options[$id.'-'.$status] = $this->t($title?:"[Event $id]");


        } 
//   kint($options);
//        exit;

        $form['attachment'] = [
            '#markup' => '<div class="attachment-main"><div class="attendance-attachment btn">MARK ATTENDANCE (ATTACH LIST)
</div></div>'
        ];

        $form['main-section'] = [
            '#markup' => '<div class="custom-main">'
        ];

        $form['event'] = [
            '#prefix' => '<div class="hidden event-auto-select"',
            '#type' => 'select',
            '#title' => $this->t('Events'),
            '#options' => $options,
            '#suffix' => '</div>',

        ];



        $form['user'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Name'),
        ];

        $form['email'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Email'),
        ];

        $form['cnic_passport'] = [
            '#type' => 'radios',
            '#options' => [
                'CNIC'=>'CNIC',
                'Passport'=>'Passport',
            ],

        ];

        $form['cnic'] = [
            '#type' => 'textfield',
            '#title' => $this->t('CNIC'),
            '#maxlength' => 15,
        ];

        $form['passport'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Passport'),
            '#maxlength' => 15,
        ];

        $form['attachlist'] = [
            '#type' => 'select',
            '#title' => $this->t('Add Reference from Attached List'),
            //            '#options' => $optionss,
            '#options' => $title_new,
        ];

        //        $form['attachlistfilename'] = [
        //            '#type' => 'textfield',
        //            '#title' => $this->t('File Name'),
        //
        //        ];
        //        $form['attachlistfile'] = [
        //            '#type' => 'file',
        //            '#title' => $this->t('Attached List File'),
        //
        //        ];
        //        $form['attachlistfile'] = array(
        //            '#type' => 'managed_file',
        //            '#upload_location' => 'public://images/',
        //            '#title' => $this->t('Attached List File'),
        //            //      '#description' => t("gif, png, jpg, jpeg"),
        //            //      '#default_value' => $this->configuration['image'],
        //            '#upload_validators' => array(
        //                'file_validate_extensions' => array('pdf,docx'),
        //                'file_validate_size' => array(25600000),
        //            ),
        //            //      '#states'        => array(
        //            //        'visible'      => array(
        //            //          ':input[name="image_type"]' => array('value' => t('Attached List File')),
        //            //        )
        //            //      )
        //        );

        $form['submit'] = [
            '#prefix' => '<div class="mark-attendance"',
            '#type' => 'submit',
            '#value' => $this->t('Mark Attendance'),
            '#suffix' => '</div>',
        ];

        $form['main-section-end'] = [
            '#markup' => '</div>'
        ];


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
            \Drupal::messenger()->addMessage('Attendance Marked');
        }

        $user = $form_state->getValue('user');

        $event = $form_state->getValue('event');


        $email = $form_state->getValue('email');

        $cnic = $form_state->getValue('cnic');

        $passport = $form_state->getValue('passport');

        $attachlist = $form_state->getValue('attachlist');

        //        $attachlist_name = $form_state->getValue('attachlistfilename');

        //        $attachlist_file = $form_state->getValue('attachlistfile');
        //        $image = $form_state->getValue('image');

        //    $this->configuration['image'] = $image;


        //        $file = \Drupal\file\Entity\File::load( $attachlist_file[0] );

        //    $file->setPermanent();

        $userid = \Drupal::currentUser()->id();

        $node = Node::create([
            'type'        => 'attendance',
            'title'       => 'Attendance marked by '.$userid,
            'field_name1' => $user,
            'field_select_event' => $event,
            'field_email' => $email,
            'field_field_cnic' => $cnic,
            'field_field_passport' => $passport,
            //            'field_add_attached_lists' => $attachlist,
            'field_attach_file' => $attachlist,
            //            'field_field_file_name' => $attachlist_name,
            //            'field_field_attach_list_file' => $attachlist_file,
        ]);
        $node->save();
    }

}
