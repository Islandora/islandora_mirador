<?php

namespace Drupal\islandora_mirador\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a mirador block from a IIIF Manifest.
 *
 * @Block(
 *   id="mirador_block",
 *   admin_label = @Translation("Mirador block")
 * )
 */
class MiradorBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [] + parent::defaultConfiguration();
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
    $token_service = \Drupal::token();
    $node = \Drupal::routeMatch()->getParameter('node');
    \Drupal::logger('mirador')->info($node->id());
    $manifest_url = $token_service->replace($this->configuration['iiif_manifest_url'], ['node' => $node]);
    \Drupal::logger('mirador')->info($manifest_url);
    $build = [
      "#title" => 'Mirador Viewer',
      "#description" => "A div for mirador viewer",
      "#theme" => "miradordiv",
      "#attached" => [
        'drupalSettings' => [
          'iiif_manifest_url' => $manifest_url,
        ],
      ],
    ];

    return $build;
  }

}
