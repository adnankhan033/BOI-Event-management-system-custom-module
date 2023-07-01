<?php

namespace Drupal\customsteps\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class CustomStepsController.
 */
class CustomStepsController extends ControllerBase {

    /**
   * Customsteps.
   *
   * @return string
   *   Return Hello string.
   */
    public function CustomSteps() {


        $form['my_table'][1]['my_text_field'] = [
            '#type' => 'textfield',
            '#title' => "One",
        
        ]; $form['my_table'][2]['my_text_field'] = [
            '#type' => 'textfield',
            '#title' => "Two",
        
        ];
         $form['submit'][] ="submit";
        return $form;
    }

}
