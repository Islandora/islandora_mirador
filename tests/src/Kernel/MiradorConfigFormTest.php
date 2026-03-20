<?php

namespace Drupal\Tests\islandora_mirador\Kernel;

use Drupal\Core\Form\FormState;
use Drupal\KernelTests\KernelTestBase;
use Drupal\islandora_mirador\Form\MiradorConfigForm;

/**
 * Tests the MiradorConfigForm validation.
 *
 * @group islandora_mirador
 */
class MiradorConfigFormTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'islandora_mirador',
    'system',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installConfig(['islandora_mirador']);
  }

  /**
   * Returns a form object with valid base values for theme fields.
   */
  protected function validValues(): array {
    return [
      'mirador_selected_theme' => 'light',
      'mirador_theme_light_primary' => '#1967d2',
      'mirador_theme_light_secondary' => '#1967d2',
      'mirador_theme_dark_primary' => '#4db6ac',
      'mirador_theme_dark_secondary' => '#4db6ac',
    ];
  }

  /**
   * Runs validateForm on a MiradorConfigForm with the given values.
   */
  protected function validateWithValues(array $values): FormState {
    $form_object = MiradorConfigForm::create($this->container);
    $form_state = new FormState();
    $form_state->setValues($values);
    $form = [];
    $form_object->validateForm($form, $form_state);
    return $form_state;
  }

  /**
   * Tests that valid theme and colors pass validation.
   */
  public function testValidLightTheme(): void {
    $form_state = $this->validateWithValues($this->validValues());
    $this->assertFalse($form_state->hasAnyErrors());
  }

  /**
   * Tests that dark theme is also valid.
   */
  public function testValidDarkTheme(): void {
    $values = $this->validValues();
    $values['mirador_selected_theme'] = 'dark';
    $form_state = $this->validateWithValues($values);
    $this->assertFalse($form_state->hasAnyErrors());
  }

  /**
   * Tests that the system theme option is valid.
   */
  public function testValidSystemTheme(): void {
    $values = $this->validValues();
    $values['mirador_selected_theme'] = 'system';
    $form_state = $this->validateWithValues($values);
    $this->assertFalse($form_state->hasAnyErrors());
  }

  /**
   * Tests that a 3-digit hex color is valid.
   */
  public function testValidShortHexColor(): void {
    $values = $this->validValues();
    $values['mirador_theme_light_primary'] = '#fff';
    $form_state = $this->validateWithValues($values);
    $this->assertFalse($form_state->hasAnyErrors());
  }

  /**
   * Tests that an invalid theme value fails validation.
   */
  public function testInvalidTheme(): void {
    $values = $this->validValues();
    $values['mirador_selected_theme'] = 'blue';
    $form_state = $this->validateWithValues($values);
    $this->assertTrue($form_state->hasAnyErrors());
    $errors = $form_state->getErrors();
    $this->assertArrayHasKey('mirador_selected_theme', $errors);
  }

  /**
   * Tests that a color without a leading # fails validation.
   */
  public function testInvalidColorMissingHash(): void {
    $values = $this->validValues();
    $values['mirador_theme_light_primary'] = '1967d2';
    $form_state = $this->validateWithValues($values);
    $this->assertTrue($form_state->hasAnyErrors());
    $errors = $form_state->getErrors();
    $this->assertArrayHasKey('mirador_theme_light_primary', $errors);
  }

  /**
   * Tests that a color with invalid hex characters fails validation.
   */
  public function testInvalidColorBadCharacters(): void {
    $values = $this->validValues();
    $values['mirador_theme_dark_secondary'] = '#zzzzzz';
    $form_state = $this->validateWithValues($values);
    $this->assertTrue($form_state->hasAnyErrors());
    $errors = $form_state->getErrors();
    $this->assertArrayHasKey('mirador_theme_dark_secondary', $errors);
  }

  /**
   * Tests that a color with wrong length fails validation.
   */
  public function testInvalidColorWrongLength(): void {
    $values = $this->validValues();
    $values['mirador_theme_dark_primary'] = '#12345';
    $form_state = $this->validateWithValues($values);
    $this->assertTrue($form_state->hasAnyErrors());
    $errors = $form_state->getErrors();
    $this->assertArrayHasKey('mirador_theme_dark_primary', $errors);
  }

  /**
   * Tests that multiple invalid fields each produce their own error.
   */
  public function testMultipleInvalidFields(): void {
    $values = $this->validValues();
    $values['mirador_selected_theme'] = 'invalid';
    $values['mirador_theme_light_secondary'] = 'notacolor';
    $form_state = $this->validateWithValues($values);
    $errors = $form_state->getErrors();
    $this->assertArrayHasKey('mirador_selected_theme', $errors);
    $this->assertArrayHasKey('mirador_theme_light_secondary', $errors);
  }

  /**
   * Tests that config defaults are set correctly on fresh install.
   */
  public function testConfigDefaults(): void {
    $config = $this->config('islandora_mirador.settings');
    $this->assertEquals('light', $config->get('mirador_selected_theme'));
    $this->assertEquals('#1967d2', $config->get('mirador_theme_light_primary'));
    $this->assertEquals('#1967d2', $config->get('mirador_theme_light_secondary'));
    $this->assertEquals('#4db6ac', $config->get('mirador_theme_dark_primary'));
    $this->assertEquals('#4db6ac', $config->get('mirador_theme_dark_secondary'));
  }

}
