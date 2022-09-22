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
            var miradorInstance = Mirador.viewer({
                "id": base,
                "manifests": {
                    [settings.iiif_manifest_url]: {provider: "Islandora"}
                },
                "windows": [
                    {
                        "manifestId": settings.iiif_manifest_url,
                        "thumbnailNavigationPosition": 'far-bottom'
                    }
                ]
            },  [
        ...textOverlayPlugin,
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
