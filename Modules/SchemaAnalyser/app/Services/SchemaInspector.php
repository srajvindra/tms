<?php

namespace Modules\SchemaAnalyser\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SchemaInspector
{
    public function inspect(?string $connection = null): array
    {
        $builder = Schema::connection($connection);
        $databaseName = DB::connection($connection)->getDatabaseName();
        $tables = $builder->getTables();

        $result = [];

        foreach ($tables as $table) {
            if (isset($table['schema']) && $table['schema'] !== $databaseName) {
                continue;
            }

            $name = $table['name'];

            $columns = $builder->getColumns($name);
            $indexes = $builder->getIndexes($name);
            $foreignKeys = $builder->getForeignKeys($name);

            $pkColumns = $this->primaryKeyColumns($indexes);
            $fkMap = $this->foreignKeyMap($foreignKeys);

            $cols = [];
            foreach ($columns as $col) {
                $flag = '';
                if (in_array($col['name'], $pkColumns, true)) {
                    $flag = 'PK';
                } elseif (isset($fkMap[$col['name']])) {
                    $flag = 'FK:' . $fkMap[$col['name']];
                }

                $cols[] = [
                    $col['name'],
                    strtoupper($col['type'] ?? $col['type_name'] ?? ''),
                    $flag,
                    $col['comment'] ?? '',
                ];
            }

            $result[$name] = [
                'cat' => $this->categorize($name),
                'cols' => $cols,
            ];
        }

        ksort($result);

        return $result;
    }

    private function primaryKeyColumns(array $indexes): array
    {
        foreach ($indexes as $index) {
            if (($index['primary'] ?? false) === true) {
                return $index['columns'] ?? [];
            }
        }

        return [];
    }

    private function foreignKeyMap(array $foreignKeys): array
    {
        $map = [];
        foreach ($foreignKeys as $fk) {
            $cols = $fk['columns'] ?? [];
            $target = $fk['foreign_table'] ?? null;
            if ($target === null) {
                continue;
            }
            foreach ($cols as $col) {
                $map[$col] = $target;
            }
        }

        return $map;
    }

    private function categorize(string $table): string
    {
        $underscore = strpos($table, '_');

        return $underscore === false ? $table : substr($table, 0, $underscore);
    }
}
