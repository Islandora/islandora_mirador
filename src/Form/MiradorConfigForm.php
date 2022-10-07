<?php

namespace Drupal\islandora_mirador\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\islandora_mirador\Annotation\IslandoraMiradorPlugin;
use Drupal\islandora_mirador\IslandoraMiradorPluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
/**
 * Mirador Settings Form.
 */
class MiradorConfigForm extends ConfigFormBase {
  /**
   * @var \Drupal\islandora_mirador\IslandoraMiradorPluginManager
   */
  protected $miradorPluginManager;

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
    $config = $this->config('islandora_mirador.settings');
    $form['mirador_library_fieldset'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Mirador library location'),
    ];
    $form['mirador_library_fieldset']['mirador_library_use_remote'] = [
      '#type' => 'radios',
      '#options' => [
        'local'=> $this->t('Local library placed in /libraries inside your webroot.'),
        'remote' => $this->t('Remote (e.g. on a CDN)'),
      ],
      '#default_value' => $config->get('mirador_library_use_remote'),
    ];
    $form['mirador_library_fieldset']['mirador_library_location'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Remote Mirador library location'),
      '#description' => $this->t('Remote URL of compiled Mirador library. It must be minified and be a single file.'),
      '#default_value' => $config->get('mirador_library_location'),
      '#states' => [
        // Show this field only if the 'remote' option is selected above.
        'enabled' => [
          ':input[name="mirador_library_use_remote"]' => [
            'value' => 'remote',
          ],
        ],
      ],
    ];
    $plugins = $this->miradorPluginManager->getDefinitions();
    $form['mirador_library_fieldset']['mirador_enabled_plugins'] = [
      '#title' => $this->t('Enabled Plugins'),
      '#description' => $this->t('Which plugins to enable. The plugins must be compiled in to the application. See the documentation for instructions.'),
      '#type' => 'checkboxes',
      '#options' => $plugins,
    ];
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
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('islandora_mirador.settings');
    $config->set('mirador_library_use_remote', $form_state->getValue('mirador_library_use_remote'));
    $config->set('mirador_library_location', $form_state->getValue('mirador_library_location'));
    $config->set('iiif_manifest_url', $form_state->getValue('iiif_manifest_url'));
    $config->save();
    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'islandora_mirador.settings',
    ];
  }


  /**
   * Constructs the Mirador config form.
   *
   * @param ConfigFactoryInterface $config_factory
   * The configuration factory.
   * @param IslandoraMiradorPluginManager $mirador_plugin_manager
   * The Mirador Plugin Manager interface.
   */
  public function __construct(ConfigFactoryInterface $config_factory, IslandoraMiradorPluginManager $mirador_plugin_manager) {
    parent::__construct($config_factory);
    $this->miradorPluginManager = $mirador_plugin_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('plugin.manager.islandora_mirador')
    );
  }

}
