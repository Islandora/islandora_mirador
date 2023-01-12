<?php

namespace Drupal\islandora_mirador\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines islandora_mirador annotation object.
 *
 * @Annotation
 */
class IslandoraMiradorPlugin extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The human-readable name of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $title;

  /**
   * The description of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $description;

}
