<?php

namespace Drupal\islandora_mirador\Plugin\IslandoraMirador;

use Drupal\islandora_mirador\IslandoraMiradorPluginPluginBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the islandora_mirador.
 *
 * @IslandoraMiradorPlugin(
 *   id = "text_overlay",
 *   label = @Translation("Text Overlay"),
 *   description = @Translation("Mirador text overlay plugin for text selection and accessibility.")
 * )
 */
class TextOverlay extends IslandoraMiradorPluginPluginBase {

  public function configForm(array $form, FormStateInterface $form_state) {
    return [];
  }

  public function alterConfig(array $config) {

  }
}
