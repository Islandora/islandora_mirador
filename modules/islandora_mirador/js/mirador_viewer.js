/*jslint browser: true*/
/*global Mirador, Drupal*/
/**
 * @file
 * Displays Mirador viewer.
 */
(function ($, Drupal) {
    'use strict';

    /**
     * The DOM element that represents the Singleton Instance of this class.
     * @type {string}
     */
    var base = 'mirador';
    var initialized;

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
            })

        }
    }
    Drupal.Mirador = Drupal.Mirador || {};

    /**
     * Initialize the Mirador Viewer.
     */
    Drupal.behaviors.Mirador = {
        attach: function (context, settings) {
            init(context,settings);
        },
        detach: function () {
        }
    };
})(jQuery, Drupal);
