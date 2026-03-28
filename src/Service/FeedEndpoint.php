<?php

declare(strict_types=1);

namespace Drupal\jobs_import_demo\Service;

use Drupal\Core\Extension\ModuleExtensionList;
use Drupal\Core\Routing\UrlGeneratorInterface;

/**
 * Resolves the job feed URL for the Migrate Plus URL source (demo route).
 */
final class FeedEndpoint {

  public function __construct(
    private readonly ModuleExtensionList $moduleExtensionList,
    private readonly UrlGeneratorInterface $urlGenerator,
  ) {}

  /**
   * Returns an absolute HTTP URL to the demo feed route, or '' if missing fixture.
   */
  public function getJobFeedUrl(): string {
    $relative = $this->moduleExtensionList->getPath('jobs_import_demo') . '/fixtures/job_feed.xml';
    $path = DRUPAL_ROOT . '/' . $relative;
    if (realpath($path) === FALSE) {
      return '';
    }

    return $this->urlGenerator->generateFromRoute(
      'jobs_import_demo.demo_feed',
      [],
      ['absolute' => TRUE],
    );
  }

}
