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
              once('mirador-viewer', base, context, settings).forEach(() => {
                if (settings.token !== undefined) {
                  values["resourceHeaders"] = {
                    'Authorization': 'Bearer '+ settings.token,
                    'token': settings.token
                  };
                  values["requestPipeline"] = [
                    (url, options) => ({  ...options, headers: {
                      "Accept": 'application/ld+json;profile="http://iiif.io/api/presentation/3/context.json"',
                      'Authorization': 'Bearer '+ settings.token,
                      'token': settings.token
                    }})
                  ];
                  values["osdConfig"] = {
                    "loadTilesWithAjax": true,
                    "ajaxHeaders": {
                      'Authorization': 'Bearer '+ settings.token,
                      'token': settings.token
                    }
                  };
                  values["requests"] = {
                    preprocessors: [ // Functions that receive HTTP requests and manipulate them (e.g. to add headers)
                      // rewrite all info.json requests to add the text/json request header
                      (url, options) => (url.match('info.json') && { ...options, headers: {
                        'Authorization': 'Bearer '+ settings.token,
                        'token': settings.token
                      }})
                    ],
                };

                }
                Mirador.viewer(values, window.miradorPlugins || {})
                }
              );
            });
            if (settings.token !== undefined) {
              if ('serviceWorker' in navigator) {
                // The Mirador viewer uses img tags for thumbnails so thumbnail image requests
                // do not have authorization or token headers. Attach them using a service worker.
                window.addEventListener('load', () => {
                  navigator.serviceWorker
                    .register('/islandora_mirador_service_worker?token=' + settings.token, { scope: '/' })
                    .then(registration => {
                      console.log('ServiceWorker registration successful with scope: ', registration.scope);
                    })
                    .catch(err => {
                      console.log('ServiceWorker registration failed: ', err);
                    });
                });
              }
            }
        },
        detach: function (context, settings) {
            Object.entries(settings.mirador.viewers).forEach(entry => {
              const [base, ] = entry;
              once.remove('mirador-viewer', base, context);
            });
        }
    };

})(Drupal, once);
