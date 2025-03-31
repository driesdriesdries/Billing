<?php
/**
 * Script: generate-context.php
 * Description: Scans the current directory and subdirectories for all PHP files (excluding this one)
 *              and writes their contents into a single file called context.txt.
 */

$plugin_dir = __DIR__;
$output_file = $plugin_dir . '/context.txt';
$self = basename(__FILE__);

$php_files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($plugin_dir, FilesystemIterator::SKIP_DOTS)
);

$context = '';

foreach ($php_files as $file) {
    if ($file->getExtension() === 'php' && $file->getFilename() !== $self) {
        $relative_path = str_replace($plugin_dir . '/', '', $file->getPathname());
        $context .= "/* === File: {$relative_path} === */\n";
        $context .= file_get_contents($file->getPathname()) . "\n\n";
    }
}

file_put_contents($output_file, $context);

echo "Context generated successfully into context.txt\n";