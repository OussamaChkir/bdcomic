<?php
class Goldland_ACF_JSON_Setup {

    public function __construct() {
        add_filter('acf/settings/save_json', array( $this, 'my_acf_json_save_point') );
        add_filter('acf/settings/load_json', array( $this, 'my_acf_json_load_point') );
    }

    public function my_acf_json_save_point( $path ) {
        // update path
        $path = get_stylesheet_directory() . '/acf-json';

        // return
        return $path;
    }

    public function my_acf_json_load_point( $paths ) {
        // remove original path (optional)
        unset($paths[0]);

        // append path
        $paths[] = get_stylesheet_directory() . '/acf-json';

        // return
        return $paths;
    }
}

new Goldland_ACF_JSON_Setup();