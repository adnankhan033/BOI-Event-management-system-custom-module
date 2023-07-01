<?php

namespace Drupal\custom_hooks\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use \Drupal\node\Entity\Node;
use \Drupal\file\Entity\File;


/**
 * Class newAttachments.
 */
class newAttachments extends FormBase {

    /**
   * {@inheritdoc}
   */
    public function getFormId() {
        return 'new_attachments';
    }

    /**
   * {@inheritdoc}
   */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['file_name'] = [
            '#prefix' => '<div class="custom-file-name">',

            '#type' => 'textfield',
            '#title' => $this->t('Attach Lists new title'),
            '#maxlength' => 64,
            '#size' => 64,
            '#weight' => '0',
            '#suffix' => '</div>',

        ];


        $form['image'] = array(

            '#type' => 'managed_file',
            '#upload_location' => 'public://images/',
            '#title' => $this->t('+ Attach List'),
//            '#description' => t("gif, png, jpg, jpeg"),
            '#description' => t("pdf, docx"),
            '#default_value' => $this->configuration['image'],

            '#upload_validators' => array(
                'file_validate_extensions' => array('pdf docx text txt png gif jpg jpeg'),
//                'file_validate_extensions' => array('gif png jpg jpeg'),
                'file_validate_size' => array(25600000),
            ),

            '#states'        => array(
                'visible'      => array(
                    ':input[name="image_type"]' => array('value' => t('Upload New Image(s)')),
                )
            )

        );
        $form['image']['#attributes']['class'][] = 'custom-attach-file';

        $form['submit'] = [
            '#prefix' => '<div class="custom-submit">',
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
            '#suffix' => '</div>',
            
        ];
        $form['submit']['#attributes']['class'][] = 'custom-attach-submit';

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
            \Drupal::messenger()->addMessage('File has been added');
        }

        $fileName = $form_state->getValue('file_name');
        //        $file = $form_state->getValue('image');

        $image = $form_state->getValue('image');

        $this->configuration['image'] = $image;

        //        / Load the object of the file by it's fid /
        $file = \Drupal\file\Entity\File::load( $image[0] );

        //    / Set the status fla/g permanent of the file object /
        $file->setPermanent();

        $node = Node::create([
            'type'        => 'new_attachments',
            'title' => $fileName,
            'field__attach_file_new' => $file,

        ]);
        $node->save();


    }

}
