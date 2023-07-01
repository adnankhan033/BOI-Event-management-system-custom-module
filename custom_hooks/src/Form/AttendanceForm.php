<?php

namespace Drupal\custom_hooks\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
//use \Drupal\node\Entity\Node;
use Drupal\node\Entity\Node;


/**
 * Class AttendanceForm.
 */
class AttendanceForm extends FormBase {

    /**
   * {@inheritdoc}
   */
    public function getFormId() {
        return 'attendance_form';
    }

    /**
   * {@inheritdoc}
   */
    public function buildForm(array $form, FormStateInterface $form_state) {

        $form['attachment'] = [
            '#markup' => '<div class="attachment-main"><div class="attendance-attachment btn">MARK ATTENDANCE (QR CODE)</div></div>'
        ];

         $form['image'] = [
            '#markup' => '<div class="attendance-image"><p>QR Attendance</p><img src="/boi-event-portal/sites/default/files/qrr.png"/></div>'
        ];

        $form['qr-reader'] = [
            '#markup' => '<div class="qr-reader"></div>'
        ];
        
        $form['qr-message'] = [
            '#markup' => '<div class="qr-message hidden">New Qr code detected, please click on mark attendance</div>'
        ];
        
        $form['user'] = [
            '#type' => 'textfield',
            '#title' => $this->t('User'),
            '#required' => TRUE,
        ];

        $form['event'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Event'),
        ];
        
        $form['code'] = [
            '#prefix' => '<div class="attendance-code"',
            '#type' => 'textfield',
            '#title' => $this->t('Attendance Code'),
            '#suffix' => '</div>',
        ];

        $form['submit'] = [
            '#prefix' => '<div class="mark-attendance"',
            '#type' => 'submit',
            '#value' => $this->t('Mark Attendance'),
            '#suffix' => '</div>',
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
        
        $userid = \Drupal::currentUser()->id();

        $node = Node::create([
            'type'        => 'attendance',
            'title'       => 'Attendance marked by '.$userid,
            'field_name' => $user,
            'field_select_event' => $event,
        ]);
        $node->save();
    }

}
