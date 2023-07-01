<?php

namespace Drupal\custom_hooks\Controller;

use Drupal\Core\Controller\ControllerBase;

use Drupal\node\Controller;


/**
 * Class CheckboxController.
 */
class CheckboxController extends ControllerBase {

    /**
   * Checkbox.
   *
   * @return string
   *   Return Hello string.
   */
    public function Checkbox() {
        
        $response = [];
        
        $state = $_POST['state'];
        $nid = $_POST['nid'];
        $cid = $_POST['cid'];


        $args = [$nid];
        $view = \Drupal\views\Views::getView('checkboxdata');
        $viewOutput = "";
        if (is_object($view)) {
            $view->setArguments($args);
            $view->setDisplay('block_1');
            $view->preExecute();
            $view->execute();
            $content = $view->render('block_1', $args);
            $viewOutput = \Drupal::service('renderer')->render($content);
        }

        //        $task_selected = ;

        //validation variables

        //access check

        //tol logic

        $node = \Drupal\node\Entity\Node::load($nid);

        $task_selected = \Drupal\paragraphs\Entity\Paragraph::load($cid);


        $name = $task_selected->field_topic->getValue()[0]['value'];

        //        print_r($name);
        //        exit;   


        if($node && $task_selected)
        {
            $oldChecked = $node->field_ff->getValue();

            $oldChecked = array_map(function($e){
                return $e['target_id'];
            }, $oldChecked);


            $oldTask = $node->field_task->getValue();


            if(in_array($cid, $oldChecked))
            {

                //if state is true and already exists then no update done
                if($state == "true")
                {
                    echo '{status:0,msg:"Already Checked"}';
                    exit;
                }
                unset($oldChecked[array_search($cid, $oldChecked)]);
                $messageNode = \Drupal\node\Entity\Node::create([
                    'type'        => 'checkboxdata',
                    'title'       => $name.' Marked as Done',
                ]);
            }else{ 
                if($state == "false")
                {
                    echo '{status:0,msg:"Already unchecked"}';
                    exit; 
                }
                $oldChecked[] = $cid;
                $messageNode = \Drupal\node\Entity\Node::create([
                    'type'        => 'checkboxdata',
                    'title'       => $name.' Changed to InProgress ',
                ]);
            }
            $messageNode->field_task_name->setValue($name);
            $messageNode->field_ff->setValue([$task_selected]);
            $messageNode->save();

            $oldTask[] = $messageNode->id();
            //            print_r($oldTask);
            $newChecked = [];
            foreach($oldChecked as $key=>$oldOne)
            {
                $p =    \Drupal\paragraphs\Entity\Paragraph::load($oldOne);
                if($p){
                    $newChecked[] =$p;
                } 
            }

            //render 
            //send it as json
            //                {"data":""}

            $node->field_ff->setValue($newChecked);
            $node->field_task->setValue($oldTask);
            $node->save();
            $response = ['status' => 1, 'msg'=>'success', 'viewOutput' =>$viewOutput];
            echo json_encode($response);
            exit;
        }



        echo 'Node error';


        //return response with success, failureee


        exit;
    }

}


