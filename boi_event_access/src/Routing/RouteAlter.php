<?php
/**
 * @file
 * Contains \Drupal\YOURMODULE\Routing\RouteSubscriber.
 */

namespace Drupal\boi_event_access\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteAlter extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {


    $mainItems = \Drupal\boi_erp\BoiErp::getSideBarMenu();

    foreach($mainItems as $id=>$item)
    {

      $route = $collection->get($item['routeName']);
      if($route)
      {
        drupal_set_message($item['title']);
        $requirements = $route->getRequirements();
        $route->setRequirement('_boi_event_permission', 'TRUE');
      }

      foreach($item['subTree'] as $idSub=>$itemSub)
      {
        $route = $collection->get($itemSub['routeName']);
        if($route)
        {
          $requirements = $route->getRequirements();
          $route->setRequirement('_boi_event_permission', 'TRUE');
        }
      }

    }

  }
}