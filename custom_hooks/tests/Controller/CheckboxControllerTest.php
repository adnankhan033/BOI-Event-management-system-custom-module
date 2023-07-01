<?php

namespace Drupal\custom_hooks\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the custom_hooks module.
 */
class CheckboxControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "custom_hooks CheckboxController's controller functionality",
      'description' => 'Test Unit for module custom_hooks and controller CheckboxController.',
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
  public function testCheckboxController() {
    // Check that the basic functions of module custom_hooks.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
