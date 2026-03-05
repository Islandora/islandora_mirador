/*jslint browser: true, esversion: 6 */
/*global Mirador, Drupal, once*/
/**
 * @file
 * Displays Mirador viewer.
 */
(function (Drupal, once) {
    'use strict';

    /**
     * Initialize the Mirador Viewer.
     */
    Drupal.behaviors.Mirador = {
        attach: function (context, settings) {
            Drupal.IslandoraMirador = Drupal.IslandoraMirador || {}
            Drupal.IslandoraMirador.instances = Drupal.IslandoraMirador.instances || {}
            Object.entries(settings.mirador.viewers).forEach(entry => {
              const [base, values] = entry;
              once('mirador-viewer', base, context).forEach(() => {
                // Resolve 'system' theme to the OS preference at render time.
                const resolvedValues = Object.assign({}, values);
                if (resolvedValues.selectedTheme === 'system') {
                    resolvedValues.selectedTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                }
                // save the mirador instance so other modules can interact
                // with the store/actions at e.g. Drupal.IslandoraMirador.instances["#mirador-xyz"].store
                Drupal.IslandoraMirador.instances[base] = Mirador.viewer(resolvedValues, window.miradorPlugins || {});
              });
            });
        },
        detach: function (context, settings) {
            Object.entries(settings.mirador.viewers).forEach(entry => {
              const [base, ] = entry;
              const removed = once.remove('mirador-viewer', base, context);
              if (removed.length > 0) {
                delete Drupal.IslandoraMirador.instances[base];
              }
            });
        }
    };

})(Drupal, once);
