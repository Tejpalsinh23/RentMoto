<?php
$viewsDir = __DIR__ . '/../resources/views';

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));

foreach ($iterator as $file) {
    if ($file->isFile() && strpos($file->getFilename(), '.blade.php') !== false) {
        $content = file_get_contents($file->getPathname());
        $original = $content;
        
        // Replace ${{ number_format(...) }}
        $content = str_replace('${{', '{{ App\Models\Setting::get(\'currency_symbol\', \'₹\') }}{{', $content);
        
        if ($content !== $original) {
            file_put_contents($file->getPathname(), $content);
            echo "Updated: " . $file->getPathname() . "\n";
        }
    }
}
echo "Done.\n";
