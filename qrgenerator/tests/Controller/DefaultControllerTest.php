<?php

namespace Drupal\qrgenerator\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the qrgenerator module.
 */
class DefaultControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "qrgenerator DefaultController's controller functionality",
      'description' => 'Test Unit for module qrgenerator and controller DefaultController.',
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
   * Tests qrgenerator functionality.
   */
  public function testDefaultController() {
    // Check that the basic functions of module qrgenerator.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
