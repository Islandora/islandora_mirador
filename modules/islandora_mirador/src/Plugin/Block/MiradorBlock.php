<?php

namespace Drupal\islandora_mirador\Plugin\Block;

use Drupal\Cache\Cache;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Utility\Token;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a mirador block from a IIIF Manifest.
 *
 * @Block(
 *   id="mirador_block",
 *   admin_label = @Translation("Mirador block")
 * )
 */
class MiradorBlock extends BlockBase implements ContainerFactoryPluginInterface {
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
   * Constructor for Mirador Block.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the formatter.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Utility\Token $token
   *   The token service.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, Token $token, RouteMatchInterface $route_match) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->token = $token;
    $this->routeMatch = $route_match;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('token'),
      $container->get('current_route_match')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['iiif_manifest_url_fieldset'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('IIIF Manifest URL'),
    ];
    $form['iiif_manifest_url_fieldset']['iiif_manifest_url'] = [
      '#type' => 'textfield',
      '#description' => $this->t('Absolute URL of the IIIF manifest to render.  You may use tokens to provide a pattern (e.g. "http://localhost/node/[node:nid]/manifest")'),
      '#default_value' => $this->configuration['iiif_manifest_url'],
      '#maxlength' => 256,
      '#size' => 64,
      '#required' => TRUE,
      '#element_validate' => ['token_element_validate'],
      '#token_types' => ['node'],
    ];
    $form['iiif_manifest_url_fieldset']['token_help'] = [
      '#theme' => 'token_tree_link',
      '#global_types' => FALSE,
      '#token_types' => ['node'],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['iiif_manifest_url'] = $form_state->getValue(['iiif_manifest_url_fieldset', 'iiif_manifest_url']);
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $node = $this->routeMatch->getParameter('node');
    $manifest_url = $this->token->replace($this->configuration['iiif_manifest_url'], ['node' => $node]);
    $id = 'mirador_' . $node->id();
    $build = [
      "#title" => $this->t('Mirador Viewer'),
      "#description" => $this->t("A div for mirador viewer"),
      "#theme" => "mirador",
      '#mirador_view_id' => $id,
      '#iiif_manifest_url' => $manifest_url,
      "#attached" => [
        'drupalSettings' => [
          'iiif_manifest_url' => $manifest_url,
          'mirador_view_id' => $id,
        ],
      ],
    ];

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    return Cache::mergeContexts(parent::getCacheContexts(), ['route']);
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    $node = $this->routeMatch->getParameter('node');
    if ($node) {
      $cache_items = [
        'node:' . $node->id(),
        'media_list',
      ];
      // Note we could not specify individual media IDs here
      // Since there would be no way to know that a node
      // Got referenced by a new media to update the media ids.
      return Cache::mergeTags(parent::getCacheTags(), $cache_items);
    }
    else {
      return parent::getCacheTags();
    }
  }

}
