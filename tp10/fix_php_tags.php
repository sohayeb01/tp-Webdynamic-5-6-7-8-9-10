<?php
// Start output buffering
ob_start();

echo "<h1>PHP Files Tag Checker</h1>";
echo "<p>This script checks for PHP closing tags that might cause 'headers already sent' errors.</p>";

// Get all PHP files recursively
function get_php_files($dir) {
    $result = [];
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    
    foreach ($files as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $result[] = $file->getPathname();
        }
    }
    
    return $result;
}

// Check a file for PHP closing tag and trailing whitespace
function check_php_file($file) {
    $content = file_get_contents($file);
    $has_closing_tag = (bool) preg_match('/\?>(\s*)$/', $content);
    $has_trailing_whitespace = (bool) preg_match('/\S\s+$/', $content);
    
    return [
        'file' => $file,
        'has_closing_tag' => $has_closing_tag,
        'has_trailing_whitespace' => $has_trailing_whitespace
    ];
}

// Fix a PHP file by removing closing tag and trailing whitespace
function fix_php_file($file) {
    $content = file_get_contents($file);
    
    // Remove closing tag and trailing whitespace
    $fixed_content = preg_replace('/\?>\s*$/', '', $content);
    
    // Remove any trailing whitespace
    $fixed_content = rtrim($fixed_content) . "\n";
    
    // Save fixed content
    file_put_contents($file, $fixed_content);
    
    return true;
}

// Get all PHP files
$php_files = get_php_files(__DIR__);

echo "<h2>Found " . count($php_files) . " PHP files</h2>";

// Check and display results
echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
echo "<tr><th>File</th><th>Has Closing Tag</th><th>Has Trailing Whitespace</th><th>Action</th></tr>";

$files_with_issues = [];

foreach ($php_files as $file) {
    $result = check_php_file($file);
    $relative_path = str_replace(__DIR__ . '/', '', $file);
    
    echo "<tr>";
    echo "<td>" . htmlspecialchars($relative_path) . "</td>";
    echo "<td>" . ($result['has_closing_tag'] ? "Yes" : "No") . "</td>";
    echo "<td>" . ($result['has_trailing_whitespace'] ? "Yes" : "No") . "</td>";
    
    if ($result['has_closing_tag'] || $result['has_trailing_whitespace']) {
        $files_with_issues[] = $file;
        echo "<td>Needs fixing</td>";
    } else {
        echo "<td>OK</td>";
    }
    
    echo "</tr>";
}

echo "</table>";

// Fix files if requested
if (isset($_GET['fix']) && $_GET['fix'] === 'true') {
    echo "<h2>Fixing issues...</h2>";
    echo "<ul>";
    
    foreach ($files_with_issues as $file) {
        fix_php_file($file);
        $relative_path = str_replace(__DIR__ . '/', '', $file);
        echo "<li>Fixed: " . htmlspecialchars($relative_path) . "</li>";
    }
    
    echo "</ul>";
    echo "<p>All issues fixed! <a href='fix_php_tags.php'>Check again</a></p>";
} elseif (!empty($files_with_issues)) {
    echo "<p>Found " . count($files_with_issues) . " files with issues.</p>";
    echo "<p><a href='fix_php_tags.php?fix=true'>Fix all issues</a></p>";
} else {
    echo "<p>No issues found! Your PHP files are clean.</p>";
}

echo "<p><a href='index.php'>Return to homepage</a></p>";

// End output buffering
ob_end_flush(); 