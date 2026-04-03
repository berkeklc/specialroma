<?php
// symlink.php - Upload this to your public folder and visit it ONCE, then delete it!

$targetPath  = __DIR__ . '/../../specialroma_core/storage/app/public';
$linkPath    = __DIR__ . '/storage';

if (!is_link($linkPath)) {
    if (symlink($targetPath, $linkPath)) {
        echo '<p style="color:green;font-weight:bold">✅ Storage symlink created successfully! Now delete this file.</p>';
    } else {
        echo '<p style="color:red;font-weight:bold">❌ Failed to create symlink. Your host may not support symlinks.</p>';
        echo '<p>Target: ' . $targetPath . '</p>';
        echo '<p>Link: ' . $linkPath . '</p>';
    }
} else {
    echo '<p style="color:orange;font-weight:bold">⚠️ Symlink already exists. Delete this file.</p>';
}
