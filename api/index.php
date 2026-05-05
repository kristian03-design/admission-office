<?php

/**
 * Vercel Entry Point for Laravel
 *
 * Vercel's filesystem is read-only except for /tmp.
 * We must redirect all writable Laravel paths to /tmp before bootstrapping.
 */

// Point all writable paths to /tmp (the only writable dir on Vercel)
$_ENV['APP_STORAGE_PATH'] = '/tmp/storage';

// Create the necessary writable directories in /tmp if they don't exist
$tmpDirs = [
    '/tmp/storage',
    '/tmp/storage/app',
    '/tmp/storage/app/public',
    '/tmp/storage/framework',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/testing',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
    '/tmp/bootstrap',
    '/tmp/bootstrap/cache',
];

foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
}

// Copy the bootstrap/cache files so they are writable
$bootstrapCache = __DIR__ . '/../bootstrap/cache';
if (is_dir($bootstrapCache)) {
    foreach (glob($bootstrapCache . '/*.php') as $file) {
        $dest = '/tmp/bootstrap/cache/' . basename($file);
        if (!file_exists($dest)) {
            copy($file, $dest);
        }
    }
}

// Override storage and bootstrap paths before Laravel boots
$_ENV['APP_BASE_PATH']      = __DIR__ . '/..';
$_ENV['APP_BOOTSTRAP_PATH'] = '/tmp/bootstrap';

// Boot Laravel
require __DIR__ . '/../public/index.php';
