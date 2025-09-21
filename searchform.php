<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label for="search-input" class="screen-reader-text"><?php esc_html_e('Search for:', 'bdcomic'); ?></label>
    <input type="search" id="search-input" class="search-field" placeholder="<?php esc_attr_e('Search comics, posts...', 'bdcomic'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
    <button type="submit" class="search-submit"><?php esc_html_e('Search', 'bdcomic'); ?></button>
</form>