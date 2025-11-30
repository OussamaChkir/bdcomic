$filePath = "c:\xampp\htdocs\comicbd\wp-content\themes\bdcomic_theme\components\my-collections-grid.php"
$lines = Get-Content $filePath

$newLines = @()
$i = 0
while ($i -lt $lines.Count) {
    # Line 64: Change total_owned to count only wishlist books
    if ($i -eq 63 -and $lines[$i] -match '\$total_owned = count\(\$owned_books\)') {
        $newLines += "`$total_owned = count(`$wishlist_books);  // Only count wishlist books as 'owned'"
        $i++
    }
    # Line 436: Add console.log and reset code
    elseif ($i -eq 435 -and $lines[$i] -match 'var viewFilter') {
        $newLines += $lines[$i]  # Add the viewFilter line
        $newLines += "            "
        $newLines += "            console.log('Filter called with viewFilter:', viewFilter);"
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

Write-Host "Both fixes applied successfully!"
Write-Host "1. Comics détenus now counts only wishlist books"
Write-Host "2. Filter now resets display before applying filters"
