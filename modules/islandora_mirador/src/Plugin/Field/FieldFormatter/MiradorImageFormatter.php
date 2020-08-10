<?php

namespace Drupal\islandora_mirador\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\image\Plugin\Field\FieldFormatter\ImageFormatterBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Mirador FieldFormatter plugin.
 *
 * @FieldFormatter(
 *   id = "mirador_image",
 *   module = "islandora_mirador",
 *   label = @Translation("mirador"),
 *   description = @Translation("Display image/file through a IIIF server"),
 *   field_types = {
 *     "image",
 *     "file"
 *   }
 * )
 */
class MiradorImageFormatter extends ImageFormatterBase implements ContainerFactoryPluginInterface {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings']
    );
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $settings = $this->getSettings();
    $config = \Drupal::config('mirador.settings');
    $iiif_url = $config->get('iiif_manifest_url');
    $token_service = \Drupal::token();
    $node = \Drupal::routeMatch()->getParameter('node');
    $manifest_url = $token_service->replace($iiif_url, ['node' => $node]);
    $elements[] = [
      '#theme' => 'mirador',
      '#attached' => [
        'drupalSettings' => [
          'iiif_manifest_url' => $manifest_url,
        ],
      ],
      '#settings' => $settings,
    ];
    return $elements;
  }

}
