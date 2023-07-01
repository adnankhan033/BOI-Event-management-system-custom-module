<?php

/**
 * @file
 * Contains custom_hooks\custom_hooks.views.inc..
 * Provide a custom views field data that isn't tied to any other module. */


/**
* Implements hook_views_data().
*/
function custom_hooks_views_data() {

    $data['views']['table']['group'] = t('Custom Global');
    $data['views']['table']['join'] = [
      // #global is a special flag which allows a table to appear all the time.
      '#global' => [],
    ];

    $data['views']['task_views_field'] = [
        'title' => t('Task views field'),
        'help' => t('My awesome custom views field plugin.'),
        'field' => [
            'id' => 'task_views_field',
        ],
    ]; 
    $data['views']['approveReject'] = [
        'title' => t('Approved & Rejected'),
        'help' => t('Approve Reject checkbox.'),
        'field' => [
            'id' => 'approveReject',
        ],
    ];
    
    $data['views']['currentuser'] = [
        'title' => t('Current User'),
        'help' => t('Current User.'),
        'field' => [
            'id' => 'currentuser',
        ],
    ];

    $data['views']['Box'] = [
        'title' => t('Box'),
        'help' => t('Box.'),
        'field' => [
            'id' => 'Box',
        ],
    ];
     $data['views']['speaker_topic'] = [
        'title' => t('Speaker Topic'),
        'help' => t('Speaker Tpic.'),
        'field' => [
            'id' => 'speaker_topic',
        ],
    ];
    return $data;
}
