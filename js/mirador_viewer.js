/*jslint browser: true*/
/*global Mirador, textOverlayPlugin, Drupal*/
/**
 * @file
 * Displays Mirador viewer.
 */
(function ($, Drupal) {
    'use strict';

    /**
     * If initialized.
     * @type {boolean}
     */
    var initialized;
    /**
     * Unique HTML id.
     * @type {string}
     */
    var base;

    function init(context,settings){
        if (!initialized){
            initialized = true;
            var plugins = [];
            settings.mirador_enabled_plugins.forEach(plugin => plugins.push(...window[plugin]));
          var miradorInstance = Mirador.viewer({
            "id": base,
            "manifests": {
              [settings.iiif_manifest_url]: {provider: "Islandora"}
            },
            "window": settings.mirador_window_settings,
            "windows": [
                    {
                        "manifestId": settings.iiif_manifest_url,
                        "thumbnailNavigationPosition": 'far-bottom'
                    }
                ]
            },  [
              ...plugins,
            ]);

        }
    }
    Drupal.Mirador = Drupal.Mirador || {};

    /**
     * Initialize the Mirador Viewer.
     */
    Drupal.behaviors.Mirador = {
        attach: function (context, settings) {
            base = settings.mirador_view_id;
            init(context,settings);
        },
        detach: function () {
        }
    };

})(jQuery, Drupal);
