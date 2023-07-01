<?php

namespace Drupal\boi_event_access\Access;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Routing\Access\AccessInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Checks access for displaying configuration translation page.
 */
class PermissionAccessCheck implements AccessInterface{

  /**
   * A custom access check.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   Run access checks for this account.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access result.
   */
  public function access(AccountInterface $account, \Drupal\Core\Routing\RouteMatchInterface $route_match) {

    $url = \Drupal\Core\Url::fromRouteMatch($route_match);
    $internalPath = $url->getInternalPath();
    
//    kint('asdf');
//    exit;

    $mainItems = \Drupal\boi_erp\BoiErp::getSideBarMenu();

    $routeName = \Drupal::routeMatch()->getRouteName();

    $routeParams = \Drupal::routeMatch()->getParameters();
    $items = [];
    $currentItem = null;
    foreach($mainItems as $id=>$item)
    {
      if($item['internalPath'])
      {
        if($item['internalPath'] == $internalPath)
        {
          $currentItem = ['id'=>$id, 'item'=>$item];
          break;
        }
      }

      foreach($item['subTree'] as $idSub=>$itemSub)
      {
        if(!$itemSub['internalPath'])
        {
          continue;
        }

        if($itemSub['internalPath'] == $internalPath)
        {
          $currentItem = [
            'id'=>$idSub,
            'item'=>$itemSub,
            'sub'=>true, 
            'parent'=>[
              'id'=>$id,
              'item'=>$item
            ],
          ];
          break;
        }
      }
    }
    if($currentItem)
    {
      $permission = 'boi_erp_'.$currentItem['id'].'_read';

      if($account->hasPermission($permission))
      {
        return AccessResult::allowed();
      }else{
        return AccessResult::forbidden();
      }
    }else{
      return AccessResult::allowed();
    }
  }

}