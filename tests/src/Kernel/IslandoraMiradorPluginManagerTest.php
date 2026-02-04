<?php

namespace Drupal\Tests\islandora_mirador\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\islandora_mirador\IslandoraMiradorPluginInterface;

/**
 * Tests the Islandora Mirador plugin manager.
 *
 * @group islandora_mirador
 */
class IslandoraMiradorPluginManagerTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'islandora_mirador',
  ];

  /**
   * The plugin manager.
   *
   * @var \Drupal\islandora_mirador\IslandoraMiradorPluginManager
   */
  protected $pluginManager;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->pluginManager = $this->container->get('plugin.manager.islandora_mirador');
  }

  /**
   * Tests that the plugin manager discovers all plugins.
   */
  public function testPluginDiscovery(): void {
    $definitions = $this->pluginManager->getDefinitions();

    $this->assertArrayHasKey('textOverlayPlugin', $definitions);
    $this->assertArrayHasKey('miradorImageToolsPlugin', $definitions);
    $this->assertCount(2, $definitions);
  }

  /**
   * Tests that plugin definitions contain required properties.
   */
  public function testPluginDefinitions(): void {
    $definitions = $this->pluginManager->getDefinitions();

    foreach ($definitions as $plugin_id => $definition) {
      $this->assertArrayHasKey('id', $definition, "Plugin $plugin_id missing 'id' property");
      $this->assertArrayHasKey('label', $definition, "Plugin $plugin_id missing 'label' property");
      $this->assertArrayHasKey('description', $definition, "Plugin $plugin_id missing 'description' property");
    }

    // Check specific labels.
    $this->assertEquals('Text Overlay', (string) $definitions['textOverlayPlugin']['label']);
    $this->assertEquals('Mirador Image Tools', (string) $definitions['miradorImageToolsPlugin']['label']);
  }

  /**
   * Tests that plugins can be instantiated.
   */
  public function testPluginInstantiation(): void {
    $text_overlay = $this->pluginManager->createInstance('textOverlayPlugin');
    $this->assertInstanceOf(IslandoraMiradorPluginInterface::class, $text_overlay);

    $image_tools = $this->pluginManager->createInstance('miradorImageToolsPlugin');
    $this->assertInstanceOf(IslandoraMiradorPluginInterface::class, $image_tools);
  }

  /**
   * Tests the plugin label() method.
   */
  public function testPluginLabel(): void {
    $text_overlay = $this->pluginManager->createInstance('textOverlayPlugin');
    $this->assertEquals('Text Overlay', $text_overlay->label());

    $image_tools = $this->pluginManager->createInstance('miradorImageToolsPlugin');
    $this->assertEquals('Mirador Image Tools', $image_tools->label());
  }

  /**
   * Tests the TextOverlay plugin windowConfigAlter() method.
   */
  public function testTextOverlayWindowConfigAlter(): void {
    $plugin = $this->pluginManager->createInstance('textOverlayPlugin');
    $window_config = [];

    $plugin->windowConfigAlter($window_config);

    $this->assertArrayHasKey('textOverlay', $window_config);
    $this->assertTrue($window_config['textOverlay']['enabled']);
    $this->assertTrue($window_config['textOverlay']['selectable']);
    $this->assertFalse($window_config['textOverlay']['visible']);
  }

  /**
   * Tests the MiradorImageTools plugin windowConfigAlter() method.
   */
  public function testMiradorImageToolsWindowConfigAlter(): void {
    $plugin = $this->pluginManager->createInstance('miradorImageToolsPlugin');
    $window_config = [];

    $plugin->windowConfigAlter($window_config);

    $this->assertArrayHasKey('imageToolsEnabled', $window_config);
    $this->assertArrayHasKey('imageToolsOpen', $window_config);
    $this->assertTrue($window_config['imageToolsEnabled']);
    $this->assertTrue($window_config['imageToolsOpen']);
  }

  /**
   * Tests that multiple plugins can alter the same config array.
   */
  public function testMultiplePluginsAlterConfig(): void {
    $window_config = [];

    foreach ($this->pluginManager->getDefinitions() as $plugin_id => $definition) {
      $plugin = $this->pluginManager->createInstance($plugin_id);
      $plugin->windowConfigAlter($window_config);
    }

    // Verify both plugins contributed their settings.
    $this->assertArrayHasKey('textOverlay', $window_config);
    $this->assertArrayHasKey('imageToolsEnabled', $window_config);
    $this->assertArrayHasKey('imageToolsOpen', $window_config);
  }

}
