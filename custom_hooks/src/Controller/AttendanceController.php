<?php

namespace Drupal\custom_hooks\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class AttendanceController.
 */
class AttendanceController extends ControllerBase {

  /**
   * Attendancecontroller.
   *
   * @return string
   *   Return Hello string.
   */
 
  public function AttendanceController() {
      
      
    $render_service = \Drupal::service('renderer');

        $entity = \Drupal::entityManager()
            ->getStorage('node')
            ->create(array());

      kint($entity);
      
      exit;
        $formObject = \Drupal::entityManager()
            ->getFormObject('attendance', 'create')
            ->setEntity($entity);
      
        $form = \Drupal::formBuilder()->getForm($formObject);
      
      $form['field_name'] = [
            '#type' => 'textfield',
            '#title' => 'User',
        ];
      
      $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];
      
      return $form;
  }

}
