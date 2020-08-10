<?php

namespace Drupal\islandora_mirador\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Mirador Settings Form.
 */
class MiradorConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'islandora_mirador.miradorconfig.form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $config = $this->config('mirador.settings');
    $form['iiif_manifest_url_fieldset'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('IIIF Manifest URL'),
    ];
    $form['iiif_manifest_url_fieldset']['iiif_manifest_url'] = [
      '#type' => 'textfield',
      '#description' => $this->t('Absolute URL of the IIIF manifest to render.  You may use tokens to provide a pattern (e.g. "http://localhost/node/[node:nid]/manifest")'),
      '#default_value' => $config->get('iiif_manifest_url'),
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
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('mirador.settings');
    $config->set('iiif_manifest_url', $form_state->getValue('iiif_manifest_url'));
    $config->save();
    return parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'mirador.settings',
    ];
  }

}
