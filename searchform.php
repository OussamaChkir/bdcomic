<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label for="search-input" class="screen-reader-text"><?php esc_html_e('Rechercher:', 'bdcomic'); ?></label>
    <input type="search" id="search-input" class="search-field" placeholder="<?php esc_attr_e('Rechercher...', 'bdcomic'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
    <button type="submit" class="search-submit"><svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 20 20"><path fill="#000000" d="M12.9 14.32a8 8 0 1 1 1.41-1.41l5.35 5.33l-1.42 1.42l-5.33-5.34zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/></svg></button>
</form>