<?php

namespace Drupal\custommail\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the custommail module.
 */
class DefaultControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "custommail DefaultController's controller functionality",
      'description' => 'Test Unit for module custommail and controller DefaultController.',
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
   * Tests custommail functionality.
   */
  public function testDefaultController() {
    // Check that the basic functions of module custommail.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
