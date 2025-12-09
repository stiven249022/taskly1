<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminLogsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $path = storage_path('logs/laravel.log');
        $entries = [];
        if (is_file($path)) {
            $lines = @file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
            $lines = array_slice($lines, max(count($lines) - 2000, 0));
            foreach ($lines as $line) {
                $parsed = $this->parseLogLine($line);
                $entries[] = $parsed;
            }
        }

        $filters = [
            'level' => strtolower($request->get('level', 'all')),
            'date' => $request->get('date'),
            'q' => $request->get('q')
        ];

        $entries = array_values(array_filter($entries, function ($e) use ($filters) {
            if ($filters['level'] && $filters['level'] !== 'all') {
                if (strtolower($e['level']) !== $filters['level']) {
                    return false;
                }
            }
            if ($filters['date']) {
                $ts = $e['timestamp'] ?? '';
                if (substr($ts, 0, 10) !== $filters['date']) {
                    return false;
                }
            }
            if ($filters['q']) {
                $text = ($e['message'] ?? '') . ' ' . ($e['raw'] ?? '');
                if (stripos($text, $filters['q']) === false) {
                    return false;
                }
            }
            return true;
        }));

        $total = count($entries);
        $perPage = (int) $request->get('per_page', 25);
        if ($perPage < 1) { $perPage = 25; }
        if ($perPage > 100) { $perPage = 100; }
        $page = (int) $request->get('page', 1);
        if ($page < 1) { $page = 1; }
        $offset = ($page - 1) * $perPage;
        $items = array_slice($entries, $offset, $perPage);

        $paginator = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('admin.logs', [
            'entries' => $paginator,
            'filters' => $filters,
            'user' => Auth::user(),
        ]);
    }

    public function download()
    {
        $path = storage_path('logs/laravel.log');
        if (!is_file($path)) {
            return abort(404);
        }
        return response()->download($path, 'laravel.log');
    }

    public function clear()
    {
        $path = storage_path('logs/laravel.log');
        if (is_file($path)) {
            @file_put_contents($path, '');
        }
        return redirect()->route('admin.logs');
    }

    public function refresh(Request $request)
    {
        return redirect()->route('admin.logs', $request->query());
    }

    private function parseLogLine(string $line): array
    {
        $level = 'info';
        $timestamp = null;
        $message = null;
        if (preg_match('/\[(.*?)\]\s+[^.]*\.(\w+):\s+(.*)/', $line, $m)) {
            $timestamp = $m[1];
            $level = strtolower($m[2]);
            $message = $m[3];
        }
        return [
            'level' => $level,
            'timestamp' => $timestamp,
            'message' => $message,
            'raw' => $line,
        ];
    }
}

