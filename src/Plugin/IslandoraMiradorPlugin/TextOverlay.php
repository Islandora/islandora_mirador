<?php

namespace Drupal\islandora_mirador\Plugin\IslandoraMiradorPlugin;

use Drupal\islandora_mirador\IslandoraMiradorPluginPluginBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the islandora_mirador.
 *
 * @IslandoraMiradorPlugin(
 *   id = "textOverlayPlugin",
 *   label = @Translation("Text Overlay"),
 *   description = @Translation("Mirador text overlay plugin for text selection and accessibility.")
 * )
 */
class TextOverlay extends IslandoraMiradorPluginPluginBase implements ContainerFactoryPluginInterface {
  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a TextOverlay object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function windowConfigAlter(array &$windowConfig) {
    // Get the config to check if this plugin is enabled.
    $config = $this->configFactory->get('islandora_mirador.settings');
    $enabled_plugins = $config->get('mirador_enabled_plugins');

    if (!empty($enabled_plugins['textOverlayPlugin'])) {
      // Enabled config - checkbox is checked.
      $windowConfig['textOverlay'] = [
        "enabled" => TRUE,
        "selectable" => TRUE,
        "visible" => FALSE,
      ];
    } 
    else {
      // Disabled config - checkbox is unchecked.
      $windowConfig['textOverlay'] = [
        "enabled" => FALSE,
        "selectable" => FALSE,
        "visible" => FALSE,
      ];
    }
  }

}
