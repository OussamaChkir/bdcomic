$filePath = "c:\xampp\htdocs\comicbd\wp-content\themes\bdcomic_theme\components\my-collections-grid.php"
$lines = Get-Content $filePath

$newLines = @()
$i = 0
while ($i -lt $lines.Count) {
    # Check if we're at the line that needs to be replaced (line 82, index 81)
    if ($i -eq 81 -and $lines[$i] -match '\$group_key = \$sous_collection') {
        # Add the new conversion logic before the $group_key assignment
        $newLines += "        "
        $newLines += "        // Convert sous_collection to string if it's an object or array"
        $newLines += "        `$sous_collection_name = '';"
        $newLines += "        if (`$sous_collection) {"
        $newLines += "            if (is_object(`$sous_collection)) {"
        $newLines += "                `$sous_collection_name = isset(`$sous_collection->post_title) ? `$sous_collection->post_title : '';"
        $newLines += "            } elseif (is_array(`$sous_collection)) {"
        $newLines += "                `$sous_collection_name = isset(`$sous_collection['post_title']) ? `$sous_collection['post_title'] : '';"
        $newLines += "            } else {"
        $newLines += "                `$sous_collection_name = (string)`$sous_collection;"
        $newLines += "            }"
        $newLines += "        }"
        $newLines += "        "
        $newLines += "        `$group_key = `$sous_collection_name ? `$sous_collection_name : `$collection_name;"
        $i++  # Skip the original line
    }
    # Update line 87 (index 86) to use $sous_collection_name instead of $sous_collection
    elseif ($i -eq 86 -and $lines[$i] -match 'is_sous_collection') {
        $newLines += "                'is_sous_collection' => !empty(`$sous_collection_name),"
        $i++
    }
    else {
        $newLines += $lines[$i]
        $i++
    }
}

$newLines | Set-Content $filePath

Write-Host "File updated successfully!"
