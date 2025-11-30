$filePath = "c:\xampp\htdocs\comicbd\wp-content\themes\bdcomic_theme\template-parts\content-livre-grid.php"
$lines = Get-Content $filePath

$newLines = @()
$i = 0

while ($i -lt $lines.Count) {
    # 1. Add $is_read and $is_owned initialization
    if ($lines[$i] -match '\$is_loaned = false;') {
        $newLines += $lines[$i]
        $newLines += '$is_read = false;'
        $newLines += '$is_owned = false;'
        $i++
    }
    # 2. Add $is_read and $is_owned calculation
    elseif ($lines[$i] -match '\$is_loaned = is_book_in_user_list\(\$current_user_id, \$post_id, ''loaned''\);') {
        $newLines += $lines[$i]
        $newLines += '    $is_read = is_book_in_user_list($current_user_id, $post_id, ''read'');'
        $newLines += '    $is_owned = is_book_in_user_list($current_user_id, $post_id, ''owned'');'
        $i++
    }
    # 3. Add icons in the HTML
    elseif ($lines[$i] -match '<div class="livre-status-icons">') {
        $newLines += $lines[$i]
        $newLines += '                    <?php if ($is_owned) : ?>'
        $newLines += '                        <span class="status-icon owned" title="<?php _e(''Possédé'', ''bdcomic_theme''); ?>">'
        $newLines += '                            <span class="dashicons dashicons-yes"></span>'
        $newLines += '                        </span>'
        $newLines += '                    <?php endif; ?>'
        $newLines += ''
        $newLines += '                    <?php if ($is_read) : ?>'
        $newLines += '                        <span class="status-icon read" title="<?php _e(''Lu'', ''bdcomic_theme''); ?>">'
        $newLines += '                            <span class="dashicons dashicons-visibility"></span>'
        $newLines += '                        </span>'
        $newLines += '                    <?php endif; ?>'
        $i++
    }
    else {
        $newLines += $lines[$i]
        $i++
    }
}

$newLines | Set-Content $filePath

Write-Host "Added read and owned icons to book grid template!"
