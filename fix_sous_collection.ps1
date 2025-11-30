$filePath = "c:\xampp\htdocs\comicbd\wp-content\themes\bdcomic_theme\components\my-collections-grid.php"
$content = Get-Content $filePath -Raw

# Replace the problematic section
$oldCode = @'
        // Get sous-collection field
        $sous_collection = get_field('sous_collection', $book->ID);
        
        $group_key = $sous_collection ? $sous_collection : $collection_name;
        
        if (!isset($collections_data[$group_key])) {
            $collections_data[$group_key] = array(
                'name' => $group_key,
                'is_sous_collection' => !empty($sous_collection),
'@

$newCode = @'
        // Get sous-collection field
        $sous_collection = get_field('sous_collection', $book->ID);
        
        // Convert sous_collection to string if it's an object or array
        $sous_collection_name = '';
        if ($sous_collection) {
            if (is_object($sous_collection)) {
                $sous_collection_name = isset($sous_collection->post_title) ? $sous_collection->post_title : '';
            } elseif (is_array($sous_collection)) {
                $sous_collection_name = isset($sous_collection['post_title']) ? $sous_collection['post_title'] : '';
            } else {
                $sous_collection_name = (string)$sous_collection;
            }
        }
        
        $group_key = $sous_collection_name ? $sous_collection_name : $collection_name;
        
        if (!isset($collections_data[$group_key])) {
            $collections_data[$group_key] = array(
                'name' => $group_key,
                'is_sous_collection' => !empty($sous_collection_name),
'@

$content = $content -replace [regex]::Escape($oldCode), $newCode
Set-Content $filePath $content -NoNewline

Write-Host "File updated successfully!"
