<?php

namespace Drupal\boi_erp;

class BoiErp
{

  public static function getSideBarMenu(){
    // Include all menu children on page.
    $menu_tree = \Drupal::menuTree();
    $menu_name = 'site-admin';

    // Build the typical default set of menu tree parameters.
    $parameters = $menu_tree->getCurrentRouteMenuTreeParameters($menu_name);
    // Load the tree based on this set of parameters.
    $tree = $menu_tree->load($menu_name, $parameters);

    $mainItems = [];
    foreach($tree as $key=>$item)
    {

      $internalPath = $item->link->getUrlObject();
      if($internalPath->isRouted())
      {
        $internalPath = $internalPath->getInternalPath();
      }else{
        $internalPath = null;
      }


      $uuid = $item->link->getDerivativeId();
      $entity = \Drupal::service('entity.repository')
        ->loadEntityByUuid('menu_link_content', $uuid);
      $id = $entity->id();

      $title = $entity->getTitle();
      $description = $entity->getDescription();
      $routeName = $item->link->getRouteName();
      $routePara = $item->link->getRouteParameters();

      $menuItemInfo = ['title'=>$title, 
                       'id'=>$id,
                       'description'=>$description,
                       'routeName'=>$routeName,
                       'para'=>$routePara,
                       'internalPath'=>$internalPath,
                       'subTree'=>[]
                      ];

      if($routeName == 'node.add')
      {
        $menuItemInfo['extra'] = ['item'=>$item, 'entity'=>$entity];
      }


      if( count($item->subtree) )
      {
        foreach($item->subtree as $subKey=>$itemSub)
        {

          $internalPathSub = $itemSub->link->getUrlObject();
          if($internalPathSub->isRouted())
          {
            $internalPathSub = $internalPathSub->getInternalPath();
          }else{
            $internalPathSub = null;
          }

          $uuidSub = $itemSub->link->getDerivativeId();
          $entitySub = \Drupal::service('entity.repository')
            ->loadEntityByUuid('menu_link_content', $uuidSub);
          $idSub = $entitySub->id();
          $titleSub = $entitySub->getTitle();
          $descriptionSub = $entitySub->getDescription();
          $routeNameSub = $itemSub->link->getRouteName();
          $routeParaSub = $itemSub->link->getRouteParameters();

          $menuItemInfo['subTree'][$idSub] = ['title'=>$titleSub,
                                              'id'=>$idSub,
                                              'routeName'=>$routeNameSub,
                                              'para'=>$routeParaSub,
                                              'description'=>$descriptionSub,
                                              'internalPath'=>$internalPathSub,
                                             ];



        }
      }
      $mainItems[$id] = $menuItemInfo;
    }

    return $mainItems;
  }
}