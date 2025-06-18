<?php

class Goldland_TinyMCE_Setup {

    public function __construct() {
        add_filter( 'mce_buttons', array( $this, 'mce_buttons' ) );

        add_filter( 'tiny_mce_before_init', array( $this, 'tiny_mce_before_init' ) );
    }

    public function mce_buttons( $buttons ) {
        $my_buttons = array(
            'formatselect', 'bold', 'italic', 'bullist', 'numlist', 'hr', 'aligncenter', 'alignleft', 'alignright', 'link', 'unlink', 'removeformat', 'charmap', 'forecolor','styleselect','removeformat'
        );
        return $my_buttons;
    }

    public function tiny_mce_before_init( $arr ) {

        $arr['block_formats'] = 'Paragraph=p;Heading 1=h1;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6';

        $default_colors = '"ffffff", "White",
                            "182748", "Blue"';

        $arr['textcolor_map'] = '['.$default_colors.']';

        $style_formats = array(
            array(
    			'title' => 'Text Medium',
                'selector' => 'p',  
    			'classes' => 'text-medium',
    			'wrapper' => true,
            ),
    	);

    	$arr['style_formats'] = json_encode( $style_formats );
        
        return $arr;
    }
}

new Goldland_TinyMCE_Setup();
