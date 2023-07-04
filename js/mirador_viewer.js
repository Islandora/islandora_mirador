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
            Object.entries(settings.mirador.viewers).forEach(entry => {
              const [base, values] = entry;
              once('mirador-viewer', base, context).forEach(() =>
                Mirador.viewer(values, window.miradorPlugins || {})
              );
            });
        },
        detach: function (context, settings) {
            Object.entries(settings.mirador.viewers).forEach(entry => {
              const [base, ] = entry;
              once.remove('mirador-viewer', base, context);
            });
        }
    };

})(Drupal, once);
