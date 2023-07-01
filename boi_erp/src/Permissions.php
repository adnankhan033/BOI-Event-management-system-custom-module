<?php

namespace Drupal\boi_erp;

use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
* Provides dynamic permissions for nodes of different types.
*/
class Permissions {

  use StringTranslationTrait;

  /**
    * Returns an array of node type permissions.
    *
    * @return array
    */
  public function getPermissions() {
    $perms = [];

    $mainItems = \Drupal\boi_erp\BoiErp::getSideBarMenu();

    foreach($mainItems as $id=>$item)
    {
      $perms['boi_erp_'.$id.'_read'] = [
        'title' => 'Read Page contents of '.$item['title'].($item['description']?' *'.$item['description'].'*':'')
      ];

      foreach($item['subTree'] as $idSub=>$itemSub)
      {

        $perms['boi_erp_'.$id.'_read'] = [
          'title' => 'Read contents in page '.$itemSub['title']
        ];
        
        $perms['boi_erp_'.$id.'_write'] = [
          'title' => 'Write/Update contents in page '.$itemSub['title']
        ];
        
        $perms['boi_erp_'.$id.'_delete'] = [
          'title' => 'Delete contents in page '.$itemSub['title']
        ];

      }

    }

    return $perms;

  }
}