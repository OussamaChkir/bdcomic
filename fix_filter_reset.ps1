$filePath = "c:\xampp\htdocs\comicbd\wp-content\themes\bdcomic_theme\components\my-collections-grid.php"
$lines = Get-Content $filePath

$newLines = @()
$i = 0
while ($i -lt $lines.Count) {
    # Line 436: Add code to show all collections and books first
    if ($i -eq 435 -and $lines[$i] -match 'var viewFilter') {
        $newLines += $lines[$i]  # Add the viewFilter line
        $newLines += "            "
        $newLines += "            // First, show all collections and books"
        $newLines += "            `$('.collection-group').show();"
        $newLines += "            `$('.book-item').show();"
        $i++
    }
    else {
        $newLines += $lines[$i]
        $i++
    }
}

$newLines | Set-Content $filePath

Write-Host "Filter fix applied successfully!"
