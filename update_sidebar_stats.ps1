$filePath = "c:\xampp\htdocs\comicbd\wp-content\themes\bdcomic_theme\page-with-sidebar.php"
$lines = Get-Content $filePath

$newLines = @()
$i = 0
$skip = $false

while ($i -lt $lines.Count) {
    # Look for the start of the collection_wishlist block
    if ($lines[$i] -match 'user_stats\[''collection_wishlist''\]') {
        # We found the line with collection_wishlist count.
        # The block usually starts a few lines up with <div class="sidebar-stat-item">
        # But since we are iterating line by line, we need to be careful.
        
        # Actually, let's look for the specific block structure.
        # The previous block ends with </div>.
        
        # Let's try a simpler approach: replace the specific lines.
        
        # We want to replace:
        # <div class="sidebar-stat-item">
        #     <span class="stat-number"><?php echo $user_stats['collection_wishlist']; ?></span>
        #     <span class="stat-label"><?php echo $list_labels['collection_wishlist']; ?></span>
        # </div>
        
        # Since I can't easily match multi-line in this loop without state, I'll use a different approach.
        # I will look for the line containing 'collection_wishlist' and replace the surrounding div block.
        
        # However, simply replacing the content of the block is easier if I can identify it.
        
        # Let's try to find the line with 'collection_wishlist' and 'stat-number'
        
        $newLines += "                                    <div class=""sidebar-stat-item"">"
        $newLines += "                                        <span class=""stat-number""><?php echo `$user_stats['owned_books']; ?></span>"
        $newLines += "                                        <span class=""stat-label""><?php echo `$list_labels['owned']; ?></span>"
        $newLines += "                                    </div>"
        $newLines += "                                    <div class=""sidebar-stat-item"">"
        $newLines += "                                        <span class=""stat-number""><?php echo `$user_stats['loaned_books']; ?></span>"
        $newLines += "                                        <span class=""stat-label""><?php echo `$list_labels['loaned']; ?></span>"
        $newLines += "                                    </div>"
        
        # Skip the next 2 lines (stat-label and closing div)
        # But wait, I need to be sure I'm replacing the right lines.
        # The original code:
        # <div class="sidebar-stat-item">  <-- I need to consume this if I haven't added it yet.
        #     <span class="stat-number"><?php echo $user_stats['collection_wishlist']; ?></span>
        #     <span class="stat-label"><?php echo $list_labels['collection_wishlist']; ?></span>
        # </div>
        
        # My loop is at the line with 'collection_wishlist'.
        # So I should have NOT added the previous line if it was just <div class="sidebar-stat-item">.
        # This is tricky with line-by-line.
        
        # Better approach: Read the whole file as a string and do a regex replace.
        $i++ # Skip the current line (stat-number)
        $i++ # Skip the next line (stat-label)
        $i++ # Skip the closing div
    }
    elseif ($lines[$i] -match '<div class="sidebar-stat-item">' -and $lines[$i + 1] -match 'user_stats\[''collection_wishlist''\]') {
        # Found the start of the block
        # Add the new blocks
        $newLines += "                                    <div class=""sidebar-stat-item"">"
        $newLines += "                                        <span class=""stat-number""><?php echo `$user_stats['owned_books']; ?></span>"
        $newLines += "                                        <span class=""stat-label""><?php echo `$list_labels['owned']; ?></span>"
        $newLines += "                                    </div>"
        $newLines += "                                    <div class=""sidebar-stat-item"">"
        $newLines += "                                        <span class=""stat-number""><?php echo `$user_stats['loaned_books']; ?></span>"
        $newLines += "                                        <span class=""stat-label""><?php echo `$list_labels['loaned']; ?></span>"
        $newLines += "                                    </div>"
        
        $i += 4 # Skip the 4 lines of the old block
    }
    else {
        $newLines += $lines[$i]
        $i++
    }
}

$newLines | Set-Content $filePath

Write-Host "Sidebar stats updated successfully!"
