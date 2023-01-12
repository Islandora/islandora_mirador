<?php

namespace Drupal\islandora_mirador;

use Drupal\Component\Plugin\PluginBase;

/**
 * Base class for islandora_mirador plugins.
 */
abstract class IslandoraMiradorPluginPluginBase extends PluginBase implements IslandoraMiradorPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function label() {
    // Cast the label to a string since it is a TranslatableMarkup object.
    return (string) $this->pluginDefinition['label'];
  }

}
