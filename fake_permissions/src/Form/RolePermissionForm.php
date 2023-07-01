<?php

namespace Drupal\fake_permissions\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class RolePermissionForm.
 */
class RolePermissionForm extends FormBase {

    /**
   * {@inheritdoc}
   */
    public function getFormId() {
        return 'role_permission_form';
    }

    /**
   * {@inheritdoc}
   */
    public function buildForm(array $form, FormStateInterface $form_state, $role_name=null) {
        
        

        $role = \Drupal\user\Entity\Role::load($role_name);

        if(!$role)
        {
            $form = [
                'msg' => [
                    '#markup' => "$role_name doesn't exists",
                ]
            ];
            return $form;
        }

        $form_state->set("role_name", $role_name);


        $role_permissions = $role->getPermissions();





        $module_permissions = [];

        //-- Get tid number and parent tid number
        $vid = 'add_new_role';
        $terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);

        $main =[];
        foreach ($terms as $key => $term) {
            $main[$term->parents[0]][$term->tid] = $term;
        }
        
        $form['checkboxes']  =[
            '#type' => 'container',
            '#tree' => true
        ];
       
        foreach($main[0] as $pid => $pterm)
        {   
            $pfieldset = 'fieldset'.$pid;
            
            //parent term name get here
            $form['checkboxes'][$pfieldset] = [
                '#type' => 'fieldset',
                '#title' => $this->t($pterm->name),
                '#weight' => '0',
               
            ];
           $form['#attributes'] = array('class' => 'custom-permission');

            if(isset($main[$pid]))
            {
             
                foreach($main[$pid] as $tid=>$term)
                {

                    $read_key = "{$term->name}.$tid Read";
                    $write_key = "{$term->name}.$tid Write";
                    $update_key = "{$term->name}.$tid Update";
                    $module_permissions[] = $read_key;
                    $module_permissions[] = $write_key;
                    $module_permissions[] = $update_key;

                    $checkboxes = [
                        $read_key => "Read",
                        $write_key => "Write",
                        $update_key => "Update",
                    ];

                    $defaults = $role_permissions;

                    $form['checkboxes'][$pfieldset]['check'.$tid] = [
                        '#title' => $term->name,
                        '#type' => 'checkboxes',
                        '#options' => $checkboxes,
                        '#default_value' => $defaults
                    ];
                }
            }

        }



        //save permissions from terms
        $form_state->set("module_permissions", $module_permissions);



        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
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

        $role_name = $form_state->get("role_name");
        $role = \Drupal\user\Entity\Role::load($role_name);
        $module_permissions = $form_state->get("module_permissions");

        foreach($form_state->getValue('checkboxes') as $fieldset)
        {
            foreach($fieldset as $checkboxes)
            {
                foreach($checkboxes as $permission=>$checked)
                {
                    if(!$checked)
                    {
                        $role->revokePermission($permission);
                    }else{
                        $role->grantPermission($permission);
                    }
                }
            }
        }

        $role->save();
    }

}
