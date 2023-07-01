<?php

namespace Drupal\custom_hooks\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Component\Datetime;
use Drupal\Core\Render\Element;

/**
 * Class CheckListViewForm.
 */
class CheckListViewForm extends FormBase {

    /**
   * {@inheritdoc}
   */
    public function getFormId() {
        return 'check_list_view_form';
    }

    /**
   * {@inheritdoc}
   */
    public function buildForm(array $form, FormStateInterface $form_state, $prop=[]) {






        $form['#attached']['library'][] = 'custom_hooks/customhooks_lib';

        static $iiid = 0;
        $iiid++;

        $node = \Drupal\node\Entity\Node::load($prop['nid']);
        $form_state->set('nid', $prop['nid']);
        $wrapperId = "tasks$iiid";


        //        kint($node->field_ff->getValue());?
        $default = [];
        foreach($node->field_ff->getValue() as $value)
        {
            $default[] = $value['target_id'];
        }

        $tasks = $prop['tasks'];
        $p = array_keys($prop['tasks'])[count($prop['tasks'])-1];
        //        kint($tasks);
        //        $form['tasks_allowed'] = [
        //            '#type' => 'textfield',
        //            '#value' => json_encode($prop['tasks_allowed'])
        //        ];

        //paragraphs


        $form['mark'] = [
            '#type' => 'checkbox',
            '#title' => 'Mark All',
            '#attributes' => [
                'class' => ['send-checkbox-data col-md-12'],
                'data-nid' => $node->id()
            ]
            //            array('class','col-md-12'),
        ];
        
//        
//        echo "<pre>";
//        print_r($tasks); exit;
//        print_r($node->id()); exit;
////    

        //        $query = db_select( 'node', 'n' );
        //        $query
        //            ->condition( 'n.type', 'checkboxdata' )
        //            ->fields('n', array('nid'))
        //            ->range(0, 1);
        //        $result = $query->execute();


        //        $nid3333 = $result->fetchAssoc()['nid'];
        //        $node333 = \Drupal\node\Entity\Node::load($nid3333);
        //        kint($node333->getOwner()->name->values);
        //        exit;
        //        $time = $node333->field_time->value;
        //
        //        
        //        $form['asdfasdfas'] = [
        //            '#markup' => "<h1>$time</h1>"
        //        ];

        //        $tasks_info = [];
        //        foreach($tasks as $task_nid=>$checkbox_label)
        //        {
        //            $task_node = \Drupal\node\Entity\Node::load($task_nid);
        //            kint($task_node);
        //        }
        //            exit;


        $form['tasks'] = [
            '#prefix' => '<div class="yaodwadre" data-nid="'.$node->id().'" id="'.$wrapperId.'"> <div class="checkboxes-d">',
            '#suffix' => '</div> <div></div></div>',
            '#type' => 'checkboxes',
            '#options' => $tasks,
            '#default_value' => $default,
            '#weight' => '0',
            '#attributes' => [
                'class' => ['send-checkbox-data'],
                'data-nid' => $node->id()
            ]
        ];



        //        $tasks = array_keys($prop['tasks']);

        //        $tasks = array_combine($tasks, $tasks);
        //        kint($tasks);
        //        kint($prop['tasks']);
        //        $form['tadsks'] = [
        //            '#type' => 'checkboxes',
        //            '#options' => $tasks,
        //            '#default_value' => $default,
        //            '#ajax' => [    
        //                'trigger' => 'change',
        //                'callback' => '::ajaxCallback2'
        //            ]
        //        ];
        //        $form['submit'] = [
        //            '#type' => 'submit',
        //            '#submit' => ['::ajaxCallback'],
        //            '#value' => 'SVEEE'
        //        ]
        ;        return $form;
    }

    public function ajaxCallback2(array $form, FormStateInterface $form_state)
    {
        echo "test";
        exit;
    }

    public function ajaxCallback(array $form, FormStateInterface $form_state)
    {
        //        echo 'hi';
        //        exit;
        $username = \Drupal::currentUser()->getUsername();
        $userid = \Drupal::currentUser()->id();
        $request_time = \Drupal::time()->getCurrentTime();
        $date = date('Y-m-d', $request_time);
        $old = $form['tasks'];
        $node = \Drupal\node\Entity\Node::load($form_state->get('nid'));

        //        $old['#suffix'] = 'And node is '.$form_state->get('node')->id().'</div>';
        $tasks_raw = $form_state->getValue('tasks');
        $task_selected = [];
        $oldChecked = $node->field_ff->getValue();

        $oldChecked = array_map(function($e){
            return $e['target_id'];
        }, $oldChecked);
        $updated = [];
        //        $allTaskRef = [];
        foreach($tasks_raw as $tid=>$selected)
        {
            //            $allTaskRef[$tid] = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($tid);

            //checked case
            if($selected !== 0)
            {
                //                $task_selected[$tid] = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($tid);
                $task_selected[$tid] = \Drupal\paragraphs\Entity\Paragraph::load($tid);

                if(!in_array($tid, $oldChecked))
                {
                    $updated[$tid] = 1;
                }

            }else{
                if(in_array($tid, $oldChecked))
                {
                    $updated[$tid] = 0;
                }
            }            

        }

        //        print_r($task_selected);
        //        exit;

        /*$message_node = \Drupal\node\Entity\Node::create([
            'type'        => 'checkboxdata',
            'title'       => '(User) (updated) (task)',
            'field_users'=> ['target_id' => 1]
            'field_ff' => ['target_id'=> 1]
        ]);

        $message_node->save();
        $id = $message_node->id();


        $old = $event->field_task->getValue();
        $old['target_id'] = $id;
        $event->field_task->setValue($old);
        $event->save();*/

        $oldD = $node->field_task->getValue();

        //        print_r($updated)
        //        exit;

        foreach($updated as $tid=>$status)
        {
            if($status)
            {
                $message_node = \Drupal\node\Entity\Node::create([
                    'type'        => 'checkboxdata',
                    'title'       => 'Checked by '.$username,
                ]);

            }else{
                $message_node = \Drupal\node\Entity\Node::create([
                    'type'        => 'checkboxdata',
                    'title'       => 'Unchecked by '.$username,
                ]);
            }
            $message_node->field_ff->setValue(\Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($tid));
            $message_node->field_checkboxes->setValue($status?"Checked":"Unchecked");
            $message_node->save();
            $oldD['target_id'] = $message_node->id();

        }

        $node->field_task->setValue($oldD);

        $node->field_ff->setValue($task_selected);
        $node->save();

        $old['#suffix'] = '<div>Updated successfully by '.$username.' at '.$date.'</div></div>';
        return $old;
    }

    /**
   * {@inheritdoc}
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
