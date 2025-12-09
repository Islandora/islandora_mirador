<?php

namespace Drupal\islandora_mirador\Plugin\IslandoraMiradorPlugin;

use Drupal\islandora_mirador\IslandoraMiradorPluginPluginBase;

/**
 * Plugin implementation of the islandora_mirador.
 *
 * @IslandoraMiradorPlugin(
 *   id = "miradorImageToolsPlugin",
 *   label = @Translation("Mirador Image Tools"),
 *   description = @Translation("MIrador image manipluation..")
 * )
 */
class MiradorImageTools extends IslandoraMiradorPluginPluginBase {

  /**
   * {@inheritdoc}
   */
  public function windowConfigAlter(array &$windowConfig) {
    // Get the config to check if this plugin is enabled.
    $config = \Drupal::service('config.factory')
      ->get('islandora_mirador.settings');
    $enabled_plugins = $config->get('mirador_enabled_plugins');
    
    if (!empty($enabled_plugins['miradorImageToolsPlugin'])) {
      // Enabled config - checkbox is checked.
      $windowConfig['imageToolsEnabled'] = true;
      $windowConfig['imageToolsOpen'] = true;
    } else {
      // Disabled config - checkbox is unchecked.
      $windowConfig['imageToolsEnabled'] = false;
      $windowConfig['imageToolsOpen'] = false;
    }
  }

}
