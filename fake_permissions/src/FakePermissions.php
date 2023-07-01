<?php

namespace Drupal\fake_permissions;

use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
* Provides dynamic permissions for nodes of different types.
*/
class FakePermissions {

    use StringTranslationTrait;

    /**
    * Returns an array of node type permissions.
    *
    * @return array
    */
    public function FakePermissions() {
        $perms = [];



        //load all terms from 

        //check parent $term->parent

        //-- Get tid number and parent tid number
        $vid = 'add_new_role';
        $terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);

        foreach($terms as $row){
            $cid = $row->tid;
            $cname = $row->name;
            
             $perms["$cname.$cid Read"] = [
                        'title' => "Read $cname",
                    ];
//                    $perms["$cname.$cid Write"] = [
//                        'title' => " Write $cname",
//                    ];
//                    $perms["$cname.$cid Update"] = [
//                        'title' => " Update $cname",
//                    ];
        }
        
        
        
        
        
        
        return $perms;
        
        $main =[];
        foreach ($terms as $key => $term) {
            $main[$term->parents[0]][$term->tid] = $term->name;
        }
        //        kint($main);
        //loop only on parent elements
        foreach($main[0] as $pid=>$pname)
        {
            if(isset($main[$pid]))
            {
                
                foreach($main[$pid] as $cid=>$cname)
                {

                    $perms["$cname.$cid Read"] = [
                        'title' => "$pname: Read $cname",
                    ];
                    $perms["$cname.$cid Write"] = [
                        'title' => "$pname: Write $cname",
                    ];
                    $perms["$cname.$cid Update"] = [
                        'title' => "$pname: Update $cname",
                    ];
                }
            }
        }
        
        return $perms;

        exit;




        $perms = [
            "$termname Read" => [
                'title' => "Adnan hadi kabadi kabari",
            ],
            "$termname Write" => [
                'title' => "Ilum da khatake lum",
            ],
            "$termname Update" => [
                'title' => "Update $termname",
            ],
        ];

        return $perms;
    }
}