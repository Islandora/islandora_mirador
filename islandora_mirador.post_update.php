<?php

/**
 * @file
 * Post update functions for the module Islandora Mirador.
 */

/**
 * Enable plugins by default for existing sites.
 */
function islandora_mirador_post_update_enable_plugins() {
  $config_factory = \Drupal::configFactory();
  $config = $config_factory->getEditable('islandora_mirador.settings');

  // Get currently enabled plugins. Default to empty array.
  $enabled_plugins = $config->get('mirador_enabled_plugins');
  if (!is_array($enabled_plugins)) {
    $enabled_plugins = [];
  }

  // The array of plugins we want to be enabled.
  $plugins_to_add = ['textOverlayPlugin', 'miradorImageToolsPlugin'];
  $changed = FALSE;

  foreach ($plugins_to_add as $plugin_id) {
    if (empty($enabled_plugins[$plugin_id])) {
      $enabled_plugins[$plugin_id] = $plugin_id;
      $changed = TRUE;
    }
  }

  if ($changed) {
    $config->set('mirador_enabled_plugins', $enabled_plugins);
    $config->save(TRUE);
  }
}

/**
 * Set default theme settings for existing sites.
 */
function islandora_mirador_post_update_theme_defaults() {
  $config = \Drupal::configFactory()->getEditable('islandora_mirador.settings');

  $defaults = [
    'mirador_selected_theme' => 'light',
    'mirador_theme_light_primary' => '#1967d2',
    'mirador_theme_light_secondary' => '#1967d2',
    'mirador_theme_dark_primary' => '#4db6ac',
    'mirador_theme_dark_secondary' => '#4db6ac',
  ];

  $changed = FALSE;
  foreach ($defaults as $key => $value) {
    if ($config->get($key) === NULL) {
      $config->set($key, $value);
      $changed = TRUE;
    }
  }

  if ($changed) {
    $config->save(TRUE);
  }
}
