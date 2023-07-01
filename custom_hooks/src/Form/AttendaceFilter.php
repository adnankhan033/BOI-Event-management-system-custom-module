<?php

namespace Drupal\custom_hooks\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class AttendaceFilter.
 */
class AttendaceFilter extends FormBase {

    /**
   * {@inheritdoc}
   */
    public function getFormId() {
        return 'attendace_filter';
    }

    /**
   * {@inheritdoc}
   */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('name'),
            '#maxlength' => 64,
            '#size' => 64,
            '#weight' => '0',
        ];
        $bundle = 'attendance'; // or $bundle='my_bundle_type';
        $query = \Drupal::entityQuery('node');
        $query->condition('status', 1);
        $query->condition('type', $bundle);
        $entity_ids = $query->execute();

        $attendace_filter = \Drupal\node\Entity\Node::loadMultiple($entity_ids);


        foreach($attendace_filter as $mail_templates)
        {

            $id = $mail_templates->id();
            $templates[$id] = $mail_templates->getTitle();
            kint($templates);
        } 
        
    

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
            \Drupal::messenger()->addMessage($key . ': ' . ($key === 'text_format'?$value['value']:$value));
        }
    }

}
