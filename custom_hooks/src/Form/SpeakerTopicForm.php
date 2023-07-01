<?php

namespace Drupal\custom_hooks\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Component\Datetime;
use Drupal\Core\Render\Element;

/**
 * Class SpeakerTopicForm.
 */
class SpeakerTopicForm extends FormBase {

    /**
   * {@inheritdoc}
   */
    public function getFormId() {
        return 'speaker_topic_form';
    }

    /**
   * {@inheritdoc}
   */
    public function buildForm(array $form, FormStateInterface $form_state, $prop = []) {

        $form['#attached']['library'][] = 'custom_hooks/customhooks_lib';
       
       $values='';

        $node = \Drupal\node\Entity\Node::load($prop['nid']);

        $sessions = $node->field_add_new_session->getValue();
        if (!empty($sessions)) {
          foreach ($sessions as $sessionid) {
             $values = $form_state->getValues();
            
          $session = \Drupal\paragraphs\Entity\Paragraph::load($sessionid['target_id']);
          $speakerids = $session->field_chair_person2->getValue();
          $lable= [];
         
          foreach ($speakerids as $speakerid) {
            $ajax_wrapper = 'my-ajax-wrapper_'.$speakerid['target_id'];
            $speaker = \Drupal\paragraphs\Entity\Paragraph::load($speakerid['target_id']);
            $uid = $speaker->field_users->getValue();
            $field = $speaker->field_topic->getValue();
            $user = '';
            if (!empty($uid)) {

              $user = User::load($uid[0]['target_id']);
              // kint($speakerid,$user->getUsername());
             $form['responce_'.$speakerid['target_id']] = [
                '#type' => 'container',
                '#markup' => $field[0]['value'],
                '#attributes' => [
                  'id' => $ajax_wrapper,
                ]
              ];
              $lable[$speakerid['target_id']] = $user->getUsername();  
            }
            
          }
          $form['speaker_'.$sessionid['target_id']] = [
            '#type' => 'select',
            '#title' => $this->t('Speaker'),
            "#empty_option"=>t('- Select -'),
            '#options' => $lable,
             '#default_value' => (isset($values['speaker_'.$sessionid['target_id']]) ? $values['speaker_'.$sessionid['target_id']] : ''),
            /*'#ajax' => [
           'callback' => [$this, 'ajaxCallback'],
           'event' => 'change',
           'wrapper' => $ajax_wrapper,
            'effect' => 'fade',
           'progress' => [
              'type' => 'throbber',
              ],
        ],*/

          ];
         /*$form['my_ajax_container'] = [
      '#type' => 'container',
      '#attributes' => [
        'id' => $ajax_wrapper,
      ]
    ];

    // ONLY LOADED IN AJAX RESPONSE OR IF FORM STATE VALUES POPULATED.
    if (!empty($values) && !empty($values['speaker_'.$sessionid['target_id']])) {
      $form['my_ajax_container']['my_response_'.$sessionid['target_id']] = [
        '#markup' => 'The current select value is ' . $values['speaker_'.$sessionid['target_id']],
      ];
    }
*/          }
          
        }
      return $form;
                
    }

   /* public function ajaxCallback(array $form, FormStateInterface $form_state)
    {
      
      $values = '';
      $element =[];
      foreach ($form_state->getValues() as $key => $value) {
        if ($value != '') {
          $values = $form_state->getValues($key);
          $form[$key]['#value'] = $value;
          $element[] = $form[$key]['#value'];
        }
      }
     /* $form['topic_1628']['#value'] = '123';
      $element = [$form['topic_1628']['#value']];
        return  $element;
        
        return $form['my_ajax_container'];
    }*/

   
   /* {@inheritdoc}
   */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        parent::validateForm($form, $form_state);
    }

    /**asdfasd
   * {@inheritdoc}
   */
    public function submitForm(array &$form, FormStateInterface $form_state) {

    }

}
