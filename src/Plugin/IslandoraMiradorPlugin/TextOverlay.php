<?php

namespace Drupal\islandora_mirador\Plugin\IslandoraMiradorPlugin;

use Drupal\islandora_mirador\IslandoraMiradorPluginPluginBase;

/**
 * Plugin implementation of the islandora_mirador.
 *
 * @IslandoraMiradorPlugin(
 *   id = "textOverlayPlugin",
 *   label = @Translation("Text Overlay"),
 *   description = @Translation("Mirador text overlay plugin for text selection and accessibility.")
 * )
 */
class TextOverlay extends IslandoraMiradorPluginPluginBase {

  /**
   * {@inheritdoc}
   */
  public function windowConfigAlter(array &$windowConfig) {
    // Get the config to check if this plugin is enabled.
    $config = \Drupal::service('config.factory')
      ->get('islandora_mirador.settings');
    $enabled_plugins = $config->get('mirador_enabled_plugins');
    
    if (!empty($enabled_plugins['textOverlayPlugin'])) {
      // Enabled config - checkbox is checked.
      $windowConfig['textOverlay'] = [
        "enabled" => true,
        "selectable" => true,
        "visible" => false,
      ];
    } else {
      // Disabled config - checkbox is unchecked.
      $windowConfig['textOverlay'] = [
        "enabled" => false,
        "selectable" => false,
        "visible" => false,
      ];
    }
  }

}
