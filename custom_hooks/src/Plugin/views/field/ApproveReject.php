<?php

namespace Drupal\custom_hooks\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Random;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
/**
 * A handler to provide a field that is completely custom by the administrator.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("approveReject")
 */
class ApproveRejectViewsField extends FieldPluginBase {

    /**
   * {@inheritdoc}
   */
    public function usesGroupBy() {
        return FALSE;
    }

    /**
   * {@inheritdoc}
   */
    public function query() {
        // Do nothing -- to override the parent query.
    }

    /**
   * {@inheritdoc}
   */
    protected function defineOptions() {
        $options = parent::defineOptions();

        $options['hide_alter_empty'] = ['default' => FALSE];
        return $options;
    }

    /**
   * {@inheritdoc}
   */
    public function buildOptionsForm(&$form, FormStateInterface $form_state) {
        parent::buildOptionsForm($form, $form_state);
    }

    /**
   * {@inheritdoc}
   */
    public function render(ResultRow $values) {
        $output =[];
        $output['markup']=[
            '#markup'=> "<div class='adnana' id='edit-outpu1t'>hello</div>",

        ];
        //        $output['approved'] =[
        //            '#type' => 'select',
        //            '#options' => array(
        //                'one' => 'one',
        //                'two' => 'two',
        //                'three' => 'three',
        //                'frour' => 'four',
        //            ),
        //
        //
        //
        //        ];

        // Create a select field that will update the contents
        // of the textbox below.
        $output['example_select'] = [
            '#type' => 'select',
            '#title' => $this->t('Example select field'),
            '#options' => [
                '1' => $this->t('One'),
                '2' => $this->t('Two'),
                '3' => $this->t('Three'),
                '4' => $this->t('From New York to Ger-ma-ny!'),
            ],
            '#ajax' => [
                'callback' => '::myAjaxCallback', // don't forget :: when calling a class method.
                //'callback' => [$this, 'myAjaxCallback'], //alternative notation
                'disable-refocus' => FALSE, // Or TRUE to prevent re-focusing on the triggering element.
                'event' => 'change',
                'wrapper' => 'edit-output', // This element is updated with this AJAX callback.
                'progress' => [
                    'type' => 'throbber',
                    'message' => $this->t('Verifying entry...'),
                ],
            ]
        ];


        return $output;



    }
    
    
    // Get the value from example select field and fill
// the textbox with the selected text.
public function myAjaxCallback(array &$form, FormStateInterface $form_state) {
  $markup = 'nothing selected';

  // Prepare our textfield. Check if the example select field has a selected option.
  if ($selectedValue = $form_state->getValue('example_select')) {
      // Get the text of the selected option.
      $selectedText = $form['example_select']['#options'][$selectedValue];
      // Place the text of the selected option in our textfield.
      $markup = "<h1>$selectedText</h1>";
  }

  // Don't forget to wrap your markup in a div with the #edit-output id
  // or the callback won't be able to find this target when it's called
  // more than once.
  $output = "<div id='edit-output'>$markup</div>";

  // Return the HTML markup we built above in a render array.
  return ['#markup' => $output];
}

}
