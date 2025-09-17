<?php

/**
 * Export Helper Functions
 * Performance optimization utilities for Excel export
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * Optimize database connections for export performance
 * Uses Laravel's built-in optimizations instead of MySQL query cache
 */
function optimizeDatabaseConnections()
{
    try {
        // Disable buffered queries for large datasets
        DB::connection()->getPdo()->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);

        // Set connection timeout for long-running queries
        DB::connection()->getPdo()->setAttribute(\PDO::ATTR_TIMEOUT, 300);

        // Optimize MySQL connection settings (remove problematic query cache settings)
        DB::statement("SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'");
        DB::statement("SET SESSION innodb_lock_wait_timeout = 300");

        \Log::info('Database connection optimized for export');
    } catch (\Exception $e) {
        \Log::warning('Database optimization failed, continuing with defaults: ' . $e->getMessage());
    }
}

/**
 * Clear summary export cache
 */
function clearSummaryCache()
{
    try {
        // Clear all summary export caches
        Cache::forget('summary_export_*');

        // Note: Query cache operations removed as they're not supported in this MySQL configuration

        \Log::info('Summary export cache cleared');
    } catch (\Exception $e) {
        \Log::warning('Cache clear failed: ' . $e->getMessage());
    }
}

/**
 * Get optimized database configuration for export
 */
function getExportDatabaseConfig()
{
    return [
        'memory_limit' => '1024M',
        'max_execution_time' => 600,
        'chunk_size' => 1000,
        'cache_ttl' => 300, // 5 minutes
    ];
}

/**
 * Preload frequently used data into cache
 */
function preloadExportData()
{
    try {
        // Cache material categories
        Cache::remember('material_categories', 3600, function() {
            return DB::table('materials')
                ->join('categories', 'materials.category_id', '=', 'categories.id')
                ->select('materials.id', 'categories.name as category_name')
                ->get()
                ->keyBy('id');
        });

        // Cache project data
        Cache::remember('export_projects', 3600, function() {
            return DB::table('projects')
                ->select('id', 'name', 'code')
                ->get()
                ->keyBy('id');
        });

        \Log::info('Export data preloaded into cache');
    } catch (\Exception $e) {
        \Log::warning('Data preload failed: ' . $e->getMessage());
    }
}

/**
 * Optimize memory usage for large exports
 */
function optimizeMemoryForExport()
{
    // Force garbage collection
    if (function_exists('gc_enable')) {
        gc_enable();
        gc_collect_cycles();
    }

    // Clear any existing output buffers
    while (ob_get_level()) {
        ob_end_clean();
    }

    \Log::info('Memory optimized for export');
}

/**
 * Get performance metrics for export operations
 */
function getExportPerformanceMetrics($startTime, $dataCount = 0)
{
    $endTime = microtime(true);
    $duration = $endTime - $startTime;

    return [
        'duration_seconds' => round($duration, 2),
        'duration_ms' => round($duration * 1000, 2),
        'memory_usage_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2),
        'data_count' => $dataCount,
        'timestamp' => now()->toISOString(),
    ];
}

/**
 * Log export performance for monitoring
 */
function logExportPerformance($operation, $metrics)
{
    \Log::info("Export Performance: {$operation}", $metrics);
}

/**
 * Safe UTF-8 sanitization for Excel export
 */
function sanitizeForExcel($string)
{
    if (empty($string)) {
        return '';
    }

    // Convert to string if not already
    $string = (string) $string;

    // Handle malformed UTF-8 by converting to UTF-8 with error handling
    if (!mb_check_encoding($string, 'UTF-8')) {
        // Try to detect encoding
        $encoding = mb_detect_encoding($string, ['UTF-8', 'ISO-8859-1', 'Windows-1252', 'CP1252'], true);
        if ($encoding && $encoding !== 'UTF-8') {
            $string = mb_convert_encoding($string, 'UTF-8', $encoding);
        } else {
            // If detection fails, assume it's UTF-8 and clean it
            $string = iconv('UTF-8', 'UTF-8//IGNORE', $string);
        }
    }

    // Remove control characters and other problematic bytes that can cause coordinate issues
    $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F-\x9F]/u', '', $string);

    // Remove specific problematic UTF-8 sequences that PhpSpreadsheet doesn't like
    $string = preg_replace('/[\xCE\x89]/u', '', $string);
    $string = preg_replace('/[\x80-\xFF][\x80-\xBF]*/u', '', $string);

    // Remove characters that could be interpreted as Excel coordinates - more aggressive patterns
    $string = preg_replace('/[A-Za-z]\d+/u', '', $string); // Remove patterns like A1, B2, etc.
    $string = preg_replace('/\$[A-Za-z]\$[0-9]+/u', '', $string); // Remove absolute references like $A$1
    $string = preg_replace('/[A-Za-z]+\d+/u', '', $string); // More general pattern
    $string = preg_replace('/\d+[A-Za-z]+/u', '', $string); // Reverse patterns like 1A, 2B
    $string = preg_replace('/[^\x20-\x7E\xA0-\xFF]/u', '', $string); // Remove non-printable/non-ASCII

    // Remove or replace problematic characters
    $string = htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, 'UTF-8');

    // Replace common problematic characters with safe alternatives
    $replacements = [
        '�' => '',
        '�' => '',
        '–' => '-',
        '—' => '-',
        '"' => '"',
        '"' => '"',
        "'" => "'",
        "'" => "'",
        '…' => '...',
        '™' => '(TM)',
        '®' => '(R)',
        '©' => '(C)',
        '€' => 'EUR',
        '£' => 'GBP',
        '¥' => 'JPY',
        '§' => '',
        '¶' => '',
        '†' => '',
        '‡' => '',
        '•' => '-',
        '‰' => '%',
        '‹' => '<',
        '›' => '>',
        '«' => '"',
        '»' => '"',
        '„' => '"',
        '‟' => '"',
        '‚' => "'",
        '‛' => "'",
        '″' => '"',
        '‴' => '"',
        '‵' => "'",
        '‶' => "'",
        '‷' => "'",
        '‹' => '<',
        '›' => '>',
        '※' => '',
        '⁂' => '',
        '⁑' => '',
        '⁎' => '',
        '⁏' => '',
        '⁕' => '',
        '⁖' => '',
        '⁘' => '',
        '⁙' => '',
        '⁜' => '',
        '⁝' => '',
        '⁞' => '',
    ];

    $string = str_replace(array_keys($replacements), array_values($replacements), $string);

    // Remove any remaining non-ASCII characters that could cause issues
    $string = preg_replace('/[^\x20-\x7E\xA0-\xFF]/u', '', $string);

    // Final validation - ensure it's valid UTF-8
    if (!mb_check_encoding($string, 'UTF-8')) {
        $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8');
    }

    // Final cleanup - remove any remaining problematic characters
    $string = preg_replace('/[^\x20-\x7E\xA0-\xFF]/u', '', $string);

    return trim($string);
}

/**
 * Safely sanitize array data for JSON encoding
 */
function sanitizeArrayForJson(array $data): array
{
    $sanitized = [];
    foreach ($data as $key => $value) {
        $sanitizedKey = is_string($key) ? sanitizeForExcel($key) : $key;

        if (is_array($value)) {
            $sanitized[$sanitizedKey] = sanitizeArrayForJson($value);
        } elseif (is_string($value)) {
            $sanitized[$sanitizedKey] = sanitizeForExcel($value);
        } elseif (is_object($value) && method_exists($value, 'toArray')) {
            $sanitized[$sanitizedKey] = sanitizeArrayForJson($value->toArray());
        } elseif (is_object($value)) {
            // Convert objects to string representation safely
            $sanitized[$sanitizedKey] = sanitizeForExcel((string) $value);
        } else {
            $sanitized[$sanitizedKey] = $value;
        }
    }
    return $sanitized;
}

/**
 * Sanitize data specifically for PhpSpreadsheet to prevent coordinate errors
 */
function sanitizeForSpreadsheet($data)
{
    if (is_array($data)) {
        return array_map('sanitizeForSpreadsheet', $data);
    }

    if (is_string($data)) {
        // First apply general UTF-8 sanitization
        $data = sanitizeForExcel($data);

        // Additional checks for PhpSpreadsheet compatibility
        // Remove characters that could be mistaken for cell references - more comprehensive
        $data = preg_replace('/^[A-Za-z]+\d+.*$/u', '', $data); // Remove strings starting with cell-like patterns
        $data = preg_replace('/.*[A-Za-z]+\d+.*$/u', '', $data); // Remove strings containing cell-like patterns
        $data = preg_replace('/^[A-Za-z]+\d+/u', '', $data); // Remove cell references at start
        $data = preg_replace('/[A-Za-z]+\d+$/u', '', $data); // Remove cell references at end
        $data = preg_replace('/\b[A-Za-z]+\d+\b/u', '', $data); // Remove standalone cell references

        // Remove any remaining problematic characters that could cause coordinate parsing issues
        $data = preg_replace('/[^\w\s\-\.\,\(\)\[\]\{\}\+\=\*\/\%\$\#\@\!\?\:\;\'\"\&]/u', '', $data);

        // Ensure the string doesn't start or end with problematic characters
        $data = trim($data, " \t\n\r\0\x0B\xA0");

        // Additional safety: if the string looks like it could be a coordinate, clear it
        if (preg_match('/^[A-Za-z]+\d+$/u', $data)) {
            return '';
        }

        return $data;
    }

    if (is_numeric($data)) {
        return $data;
    }

    if (is_bool($data)) {
        return $data;
    }

    if (is_null($data)) {
        return '';
    }

    // For any other type, convert to string and sanitize
    return sanitizeForExcel((string) $data);
}