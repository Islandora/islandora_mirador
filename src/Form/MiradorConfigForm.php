<?php

namespace Drupal\islandora_mirador\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\TypedConfigManagerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\islandora_mirador\IslandoraMiradorPluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Mirador Settings Form.
 */
class MiradorConfigForm extends ConfigFormBase {

  /**
   * The Mirador plugin manager.
   *
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
    $form['mirador_library_fieldset']['mirador_library_installation_type'] = [
      '#type' => 'radios',
      '#options' => [
        'remote' => $this->t('Default remote location'),
        'local' => $this->t('Local library placed in /libraries inside your webroot.'),

      ],
      '#description' => $this->t("For local, put the output of 'npm run webpack' of <a href=\"https://github.com/islandora/mirador-integration-islandora\">Mirador Integration Islandora</a> into web/library/mirador/dist/ and ensure it's named main.js."),
      '#default_value' => $config->get('mirador_library_installation_type'),
    ];

    $form['mirador_library_fieldset']['mirador_library_minified'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Local library is minified'),
      '#description' => $this->t("Check this if the local library has been minified."),
      '#default_value' => $config->get('mirador_library_minified'),
      '#states' => [
        'visible' => [
          ':input[name="mirador_library_installation_type"]' => ['value' => 'local'],
        ],
      ],
    ];

    $form['mirador_library_fieldset']['mirador_language_support'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable multi-language interface'),
      '#description' => $this->t('Use the Drupal interface language for the Mirador viewer (if supported).'),
      '#default_value' => $config->get('mirador_language_support'),
    ];

    $plugins = [];
    foreach ($this->miradorPluginManager->getDefinitions() as $plugin_key => $plugin_definition) {
      $plugins[$plugin_key] = $plugin_definition['label'];
    }
    $form['mirador_library_fieldset']['mirador_enabled_plugins'] = [
      '#title' => $this->t('Enabled Plugins'),
      '#description' => $this->t('Which plugins to enable. The plugins must be compiled in to the application. See the documentation for instructions.'),
      '#type' => 'checkboxes',
      '#options' => $plugins,
      '#default_value' => $config->get('mirador_enabled_plugins'),
    ];
    $form['mirador_theme_fieldset'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Theme'),
      '#description' => $this->t('Configure the Mirador viewer theme and color palette. See the <a href=":url">Mirador theming documentation</a> for more information.', [
        ':url' => 'https://github.com/ProjectMirador/mirador/wiki/M3-Theming-Mirador',
      ]),
    ];
    $form['mirador_theme_fieldset']['mirador_selected_theme'] = [
      '#type' => 'radios',
      '#title' => $this->t('Selected theme'),
      '#options' => [
        'light' => $this->t('Light'),
        'dark' => $this->t('Dark'),
      ],
      '#default_value' => $config->get('mirador_selected_theme'),
    ];
    $form['mirador_theme_fieldset']['mirador_theme_light'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Light theme colors'),
    ];
    $form['mirador_theme_fieldset']['mirador_theme_light']['mirador_theme_light_primary'] = [
      '#type' => 'color',
      '#title' => $this->t('Primary color'),
      '#default_value' => $config->get('mirador_theme_light_primary'),
    ];
    $form['mirador_theme_fieldset']['mirador_theme_light']['mirador_theme_light_secondary'] = [
      '#type' => 'color',
      '#title' => $this->t('Secondary color'),
      '#default_value' => $config->get('mirador_theme_light_secondary'),
    ];
    $form['mirador_theme_fieldset']['mirador_theme_dark'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Dark theme colors'),
    ];
    $form['mirador_theme_fieldset']['mirador_theme_dark']['mirador_theme_dark_primary'] = [
      '#type' => 'color',
      '#title' => $this->t('Primary color'),
      '#default_value' => $config->get('mirador_theme_dark_primary'),
    ];
    $form['mirador_theme_fieldset']['mirador_theme_dark']['mirador_theme_dark_secondary'] = [
      '#type' => 'color',
      '#title' => $this->t('Secondary color'),
      '#default_value' => $config->get('mirador_theme_dark_secondary'),
    ];

    $form['iiif_manifest_url_fieldset'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('IIIF Manifest URL'),
    ];
    $form['iiif_manifest_url_fieldset']['iiif_manifest_url'] = [
      '#type' => 'textfield',
      '#description' => $this->t('Absolute URL of the IIIF manifest to render.  You may use tokens to provide a pattern (e.g. "[node:url:unaliased:absolute]/manifest" or "http://localhost/node/[node:nid]/manifest")'),
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
    parent::validateForm($form, $form_state);

    $theme = $form_state->getValue('mirador_selected_theme');
    if (!in_array($theme, ['light', 'dark'], TRUE)) {
      $form_state->setErrorByName('mirador_selected_theme', $this->t('Theme must be either "light" or "dark".'));
    }

    $color_fields = [
      'mirador_theme_light_primary',
      'mirador_theme_light_secondary',
      'mirador_theme_dark_primary',
      'mirador_theme_dark_secondary',
    ];
    foreach ($color_fields as $field) {
      $value = $form_state->getValue($field);
      if ($value !== NULL && $value !== '' && !preg_match('/^#[0-9a-fA-F]{3}([0-9a-fA-F]{3})?$/', $value)) {
        $form_state->setErrorByName($field, $this->t('Color must be a valid CSS hex color (e.g. #fff or #1967d2).'));
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('islandora_mirador.settings');
    $config->set('mirador_library_installation_type', $form_state->getValue('mirador_library_installation_type'));
    $config->set('mirador_enabled_plugins', $form_state->getValue('mirador_enabled_plugins'));
    $config->set('iiif_manifest_url', $form_state->getValue('iiif_manifest_url'));
    $config->set('mirador_library_minified', $form_state->getValue('mirador_library_minified'));
    $config->set('mirador_language_support', $form_state->getValue('mirador_language_support'));
    $config->set('mirador_selected_theme', $form_state->getValue('mirador_selected_theme'));
    $config->set('mirador_theme_light_primary', $form_state->getValue('mirador_theme_light_primary'));
    $config->set('mirador_theme_light_secondary', $form_state->getValue('mirador_theme_light_secondary'));
    $config->set('mirador_theme_dark_primary', $form_state->getValue('mirador_theme_dark_primary'));
    $config->set('mirador_theme_dark_secondary', $form_state->getValue('mirador_theme_dark_secondary'));

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
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   * @param \Drupal\Core\Config\TypedConfigManagerInterface $typed_config_manager
   *   The typed config manager.
   * @param \Drupal\islandora_mirador\Annotation\IslandoraMiradorPluginManager $mirador_plugin_manager
   *   The Mirador Plugin Manager interface.
   */
  public function __construct(
    ConfigFactoryInterface $config_factory,
    TypedConfigManagerInterface $typed_config_manager,
    IslandoraMiradorPluginManager $mirador_plugin_manager,
  ) {
    parent::__construct($config_factory, $typed_config_manager);
    $this->miradorPluginManager = $mirador_plugin_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('config.typed'),
      $container->get('plugin.manager.islandora_mirador')
    );
  }

}
