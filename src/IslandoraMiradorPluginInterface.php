<?php

namespace Drupal\islandora_mirador;

/**
 * Interface for islandora_mirador plugins.
 */
interface IslandoraMiradorPluginInterface {

  /**
   * Returns the translated plugin label.
   *
   * @return string
   *   The translated title.
   */
  public function label();

  /**
   * Lets a plugin inject custom settings into the
   * Mirador window JSON array.
   *
   * @param array $windowConfig
   * @return void
   */
  public function windowConfigAlter(array &$windowConfig);

}
