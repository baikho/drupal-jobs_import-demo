<?php

declare(strict_types=1);

namespace Drupal\jobs_import_demo\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Extension\ModuleExtensionList;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Serves the bundled demo XML feed over HTTP for Migrate Plus URL fetching.
 */
final class DemoJobFeedController extends ControllerBase {

  public function __construct(
    private readonly ModuleExtensionList $moduleExtensionList,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('extension.list.module'),
    );
  }

  /**
   * Returns the demo job feed XML.
   */
  public function serve(): Response {
    $relative = $this->moduleExtensionList->getPath('jobs_import_demo') . '/fixtures/job_feed.xml';
    $full = DRUPAL_ROOT . '/' . $relative;
    if (!is_readable($full)) {
      throw new NotFoundHttpException();
    }
    $xml = file_get_contents($full);
    if ($xml === FALSE) {
      throw new NotFoundHttpException();
    }
    return new Response($xml, Response::HTTP_OK, [
      'Content-Type' => 'application/xml; charset=UTF-8',
      'Cache-Control' => 'public, max-age=3600',
    ]);
  }

}
