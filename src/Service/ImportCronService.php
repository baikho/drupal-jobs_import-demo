<?php

declare(strict_types=1);

namespace Drupal\jobs_import_demo\Service;

/**
 * Cron-style import runner: spawns Drush migrate:import in the background.
 *
 * Ultimate Cron should call {@see self::jobsImportCron()}.
 */
final class ImportCronService {

  /**
   * Ultimate Cron callback: import jobs for the jobs_import_demo group.
   */
  public function jobsImportCron(): void {
    $this->runGroup('jobs_import_demo');
  }

  /**
   * Spawns drush mim in the background (non-blocking).
   *
   * @param string $migrationGroup
   *   Migrate Plus group id (e.g. jobs_import_demo).
   */
  private function runGroup(string $migrationGroup): void {
    $drush = DRUPAL_ROOT . '/../vendor/bin/drush';
    if (!is_executable($drush)) {
      return;
    }

    $command = $drush . ' mim --group=' . escapeshellarg($migrationGroup) . ' --update --sync > /dev/null 2>&1 &';
    exec($command);
  }

}
