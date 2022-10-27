<?php

namespace Drupal\islandora_mirador\Plugin\IslandoraMiradorPlugin;

use Drupal\islandora_mirador\IslandoraMiradorPluginPluginBase;
use Drupal\Core\Form\FormStateInterface;

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
   * {@InheritDoc}
   */
  public function windowConfigAlter(array &$windowConfig) {
    $windowConfig['imageToolsEnabled'] = true;
    $windowConfig['imageToolsOpen'] = true;
  }

}
