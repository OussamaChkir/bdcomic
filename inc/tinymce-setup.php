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

        $arr['block_formats'] = 'Paragraph=p;Heading 1=h1;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5';

        $default_colors = '"ffffff", "White",
                            "000000", "Black",
                            "E42328", "Red"';

        $arr['textcolor_map'] = '['.$default_colors.']';

        $style_formats = array(
    		// array(
    		// 	'title' => 'Title H2 Small',
            //     'selector' => 'h2',  
    		// 	'block' => 'h2',
    		// 	'classes' => 'h4',
    		// 	'wrapper' => true,
            // ),
            array(
    			'title' => 'Text Default',
                'selector' => 'p',  
    			'classes' => 'text-default',
    			'wrapper' => true,
            ),
            array(
    			'title' => 'Text Medium',
                'selector' => 'p',  
    			'classes' => 'text-medium',
    			'wrapper' => true,
            ),
            array(
    			'title' => 'List Check',
                'selector' => 'ul',  
    			'classes' => 'list-check',
    			'wrapper' => true,
            ),
            array(
    			'title' => 'List Check Inline',
                'selector' => 'ul',  
    			'classes' => 'list-check-inline',
    			'wrapper' => true,
            ),
            array(
    			'title' => 'List Ok',
                'selector' => 'li',  
    			'classes' => 'list-yes',
    			'wrapper' => true,
            ),
            array(
    			'title' => 'List No',
                'selector' => 'li',  
    			'classes' => 'list-no',
    			'wrapper' => true,
            ),
    	);

    	$arr['style_formats'] = json_encode( $style_formats );
        
        return $arr;
    }
}

new Goldland_TinyMCE_Setup();
