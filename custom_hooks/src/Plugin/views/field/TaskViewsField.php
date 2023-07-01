<?php

namespace Drupal\custom_hooks\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Random;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * A handler to provide a field that is completely custom by the administrator.
 *
 * @ingroup views_field_handlers
 *
 *
 * @ViewsField("task_views_field")
 */
class TaskViewsField extends FieldPluginBase {

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




        // Return a random text, here you can include your custom logic.
        // Include any namespace required to call the method required to generate
        // the desired output.
        $task_options = [];
        $node = $values->_entity;
        $field_add_new_session = $node->field_add_new_session->referencedEntities();
        foreach($field_add_new_session as $paragraph)
        {
            $field_add_sub2 = $paragraph->field_add_sub2->referencedEntities();
            foreach($field_add_sub2 as $para_sub2)
            {
                $checklists = $para_sub2->field_checklist->referencedEntities();
                foreach($checklists as $checklist)
                {
                    //                  kint($checklist->getTitle());
                    $tasks = $checklist->field_add_task2->referencedEntities();
                    foreach($tasks as $task)
                    {

                        //load checkboxdata wheere field_ff = $task->id()
                        $args = [$task->id(), $node->id()];
                        $view = \Drupal\views\Views::getView('asdf');
                        $viewOutput = "";
                        if (is_object($view)) {
                            $view->setArguments($args);
                            $view->setDisplay('block_1');
                            $view->preExecute();
                            $view->execute();
                            $content = $view->render('block_1', $args);
                            $viewOutput = \Drupal::service('renderer')->render($content);
                          
                        }
                        
                        $task_options[$task->id()] = $task->field_topic->getValue()[0]['value'].$viewOutput;
                    }
                    
//                    kint($tasks);
//                    exit;
                }
            }
        }

        if(!$task_options)
        {
            return 'No tasks';    
        }

        $prop = [];
        $prop['tasks'] = $task_options;

        $prop['nid'] = $node->id();
        $render_service = \Drupal::service('renderer');
        $form = \Drupal::formBuilder()->getForm('Drupal\custom_hooks\Form\CheckListViewForm', $prop);
        //        $form['tasks']['#options'] = $task_options;
        $form = $render_service->render($form);
        return $form;
        return [
            '#markup' => \Drupal\Core\Render\Markup::create($pageOutput)
        ];
    }

}