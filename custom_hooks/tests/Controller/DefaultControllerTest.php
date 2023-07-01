<?php

namespace Drupal\custom_hooks\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the custom_hooks module.
 */
class DefaultControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "custom_hooks DefaultController's controller functionality",
      'description' => 'Test Unit for module custom_hooks and controller DefaultController.',
      'group' => 'Other',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests custom_hooks functionality.
   */
  public function testDefaultController() {
    // Check that the basic functions of module custom_hooks.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
