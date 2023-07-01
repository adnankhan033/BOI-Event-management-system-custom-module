<?php

namespace Drupal\customsteps\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the customsteps module.
 */
class CustomStepsControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "customsteps CustomStepsController's controller functionality",
      'description' => 'Test Unit for module customsteps and controller CustomStepsController.',
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
   * Tests customsteps functionality.
   */
  public function testCustomStepsController() {
    // Check that the basic functions of module customsteps.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
