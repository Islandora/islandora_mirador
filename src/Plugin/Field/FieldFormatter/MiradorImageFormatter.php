<?php

namespace Drupal\islandora_mirador\Plugin\Field\FieldFormatter;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Utility\Token;
use Drupal\image\Plugin\Field\FieldFormatter\ImageFormatterBase;
use Drupal\islandora\IslandoraUtils;
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
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The token service container.
   *
   * @var \Drupal\Core\Utility\Token
   */
  protected $token;

  /**
   * The route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * Islandora utility functions.
   *
   * @var \Drupal\islandora\IslandoraUtils
   */
  protected $utils;

  /**
   * Constructs a StringFormatter instance.
   *
   * @param string $plugin_id
   *   The plugin_id for the formatter.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the formatter is associated.
   * @param array $settings
   *   The formatter settings.
   * @param string $label
   *   The formatter label display setting.
   * @param string $view_mode
   *   The view mode.
   * @param array $third_party_settings
   *   Any third party settings settings.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Utility\Token $token
   *   The token service.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match.
   * @param \Drupal\islandora\IslandoraUtils $utils
   *   Islandora utils.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, ConfigFactoryInterface $config_factory, Token $token, RouteMatchInterface $route_match, IslandoraUtils $utils) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings, $config_factory);
    $this->token = $token;
    $this->routeMatch = $route_match;
    $this->configFactory = $config_factory;
    $this->utils = $utils;
  }

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
      $configuration['third_party_settings'],
      $container->get('config.factory'),
      $container->get('token'),
      $container->get('current_route_match'),
      $container->get('islandora.utils')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $settings = $this->getSettings();
    $files = $this->getEntitiesToView($items, $langcode);
    if (empty($files)) {
      return $elements;
    }
    $iiif_url = $this->configFactory->get('islandora_mirador.settings')->get('iiif_manifest_url');
    $token_service = $this->token;
    foreach ($files as $file) {
      $medias = $this->utils->getReferencingMedia($file->id());
      $first_media = array_values($medias)[0];
      $node = $first_media->get('field_media_of')->entity;
      $id = 'mirador_' . $node->id();
      $manifest_url = $token_service->replace($iiif_url, ['node' => $node]);
      $elements[] = [
        '#theme' => 'mirador',
        '#mirador_view_id' => $id,
        '#iiif_manifest_url' => $manifest_url,
        '#attached' => [
          'drupalSettings' => [
            'iiif_manifest_url' => $manifest_url,
            'mirador_view_id' => $id,
          ],
        ],
        '#settings' => $settings,
      ];
    }
    return $elements;
  }

}
