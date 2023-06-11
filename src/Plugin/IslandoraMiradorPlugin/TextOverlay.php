<?php

namespace Drupal\islandora_mirador\Plugin\IslandoraMiradorPlugin;

use Drupal\islandora_mirador\IslandoraMiradorPluginPluginBase;
use Drupal\Core\Form\FormStateInterface;

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
   * {@InheritDoc}
   */
  public function windowConfigAlter(array &$windowConfig) {
    $windowConfig['textOverlay'] = [
      "enabled" => true,
      "selectable" => true,
      "visible" => false,
    ];
  }

}
