<?php

namespace Modules\SchemaAnalyser\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SchemaInspector
{
    /**
     * Curated keyword buckets for smart-mode categorization.
     * Iteration order matters: a table's leftmost token wins, and within a token,
     * the first matching category wins. Each token is checked in both its raw
     * and singular forms so short prefixes like `sos` and `bas` are not mangled.
     */
    private const SMART_RULES = [
        'auth' => ['user', 'role', 'permission', 'password', 'session', 'oauth', 'token', 'otp'],
        'system' => ['migration', 'cache', 'job', 'failed', 'batch', 'notification', 'telescope', 'pulse', 'horizon', 'monitor', 'monitored', 'health', 'log', 'workload', 'export'],
        'tenancy' => ['tenant', 'domain'],
        'custom' => ['custom', 'entity', 'risk'],
        'customer' => ['customer', 'contact', 'lead', 'company', 'client'],
        'staff' => ['staff', 'department', 'employee'],
        'property' => ['property', 'unit', 'amenity', 'address', 'zip', 'location', 'room', 'country', 'state'],
        'integration' => ['qb', 'sos', 'bc', 'fba', 'bas', 'fluidpay', 'stripe', 'twilio', 'webhook', 'api'],
        'commerce' => ['order', 'cart', 'product', 'offer', 'item', 'inventory', 'sku', 'itemsku', 'quote', 'quotemap', 'send', 'po'],
        'logistics' => ['carrier', 'shipment', 'shiping', 'shipping', 'warehouse', 'store', 'delivery', 'rate', 'surcharge'],
        'payment' => ['payment', 'charge', 'refund', 'fee', 'tax', 'invoice', 'transaction', 'bank'],
        'membership' => ['membership', 'memership', 'subscription', 'plan'],
        'pipeline' => ['pipeline', 'stage', 'step', 'workflow', 'process', 'project'],
        'communication' => ['template', 'message', 'email', 'sms'],
        'document' => ['document', 'file', 'attachment', 'media', 'image', 'collateral', 'master'],
        'calendar' => ['event', 'calendar', 'availability', 'schedule', 'reminder', 'timezone', 'attendance'],
        'cms' => ['cms', 'page', 'post', 'article', 'tag', 'category'],
        'business' => ['business', 'organization', 'vendor', 'brand', 'setting', 'supplier'],
        'crm' => ['activity', 'note', 'task', 'feedback'],
        'support' => ['service', 'ticket', 'hold', 'defect', 'escalation', 'exception', 'ack'],
    ];

    public function inspect(?string $connection = null, bool $smart = false): array
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
                    $flag = 'FK:'.$fkMap[$col['name']];
                }

                $cols[] = [
                    $col['name'],
                    strtoupper($col['type'] ?? $col['type_name'] ?? ''),
                    $flag,
                    $col['comment'] ?? '',
                ];
            }

            $result[$name] = [
                'cat' => $smart ? $this->categorizeSmart($name) : $this->categorize($name),
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

    private function categorizeSmart(string $table): string
    {
        foreach (explode('_', $table) as $segment) {
            $variants = array_unique([$segment, Str::singular($segment)]);
            foreach (self::SMART_RULES as $category => $keywords) {
                if (array_intersect($variants, $keywords) !== []) {
                    return $category;
                }
            }
        }

        return 'other';
    }
}
