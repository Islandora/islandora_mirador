<?php

namespace Drupal\islandora_mirador\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Extension\ExtensionPathResolver;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * A controller for the Service Worker.
 */
class ServiceWorkerController extends ControllerBase {

  /**
   * The extension path resolver service.
   *
   * @var \Drupal\Core\Extension\ExtensionPathResolver
   */
  protected $extensionPathResolver;

  /**
   * Constructs a ServiceWorkerController object.
   *
   * @param \Drupal\Core\Extension\ExtensionPathResolver $extension_path_resolver
   *   The extension path resolver service.
   */
  public function __construct(ExtensionPathResolver $extension_path_resolver) {
    $this->extensionPathResolver = $extension_path_resolver;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('extension.path.resolver')
    );
  }

  /**
   * Adds headers to the HTTP response.
   */
  public function serve(Request $request) {
    $file_str = $this->extensionPathResolver->getPath('module', 'islandora_mirador') . '/js/service_worker.js';
    if (file_exists($file_str)) {
      $response = new BinaryFileResponse($file_str, 200);
      $response->headers->set('Content-Type', 'application/javascript');
      // Allow same origin service worker.
      $response->headers->set('Service-Worker-Allowed', '/');
      return $response;
    }
    throw new NotFoundHttpException();
  }

}
