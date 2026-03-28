<?php

declare(strict_types=1);

namespace Drupal\jobs_import_demo\Plugin\migrate\process;

use Drupal\migrate\Attribute\MigrateProcess;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Reduces a migration_lookup result to one numeric destination id.
 *
 * When the lookup uses only a shared key (e.g. job id) while the map holds one
 * row per locale, migration_lookup may return several node ids. Translation rows
 * still need a single nid to attach to; this plugin keeps the first numeric id
 * in that list (all refer to the same job’s destinations in typical feeds).
 */
#[MigrateProcess(id: 'migration_lookup_first_nid')]
final class MigrationLookupFirstNid extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    return $this->normalize($value);
  }

  /**
   * Returns the first numeric destination id, or NULL if absent.
   */
  private function normalize(mixed $value): ?int {
    if ($value === NULL || $value === '' || $value === FALSE) {
      return NULL;
    }
    if (is_array($value)) {
      if ($value === []) {
        return NULL;
      }
      $first = reset($value);
      if (is_numeric($first)) {
        return (int) $first;
      }
      return $this->normalize($first);
    }
    return (int) $value;
  }

}
