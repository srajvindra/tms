<?php

namespace Modules\SchemaAnalyser\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Modules\SchemaAnalyser\Services\SchemaInspector;

class SchemaAnalyserController extends Controller
{
    public function index(Request $request, SchemaInspector $inspector): View
    {
        $requestedConnection = $request->query('connection');
        $requestedDatabase = $request->query('database');

        $connection = $this->resolveConnection($requestedConnection, $requestedDatabase);
        $smart = $request->boolean('smart');
        $schema = $inspector->inspect($connection, $smart);
        
        $displayConnection = $requestedConnection ?? config('database.default');

        return view('schemaanalyser::index', [
            'schema' => $schema,
            'connectionName' => $displayConnection,
            'databaseName' => DB::connection($connection)->getDatabaseName(),
            'availableConnections' => array_keys(config('database.connections', [])),
            'smartMode' => $smart,
        ]);
    }

    private function resolveConnection(?string $connection, ?string $database): ?string
    {
        if ($connection !== null) {
            return $connection;
        }

        if ($database === null || $database === '') {
            return null;
        }

        $default = config('database.default');
        $base = config("database.connections.{$default}");
        $runtimeName = 'schemaanalyser_runtime';

        Config::set("database.connections.{$runtimeName}", array_merge($base, [
            'database' => $database,
        ]));

        DB::purge($runtimeName);

        return $runtimeName;
    }
}
