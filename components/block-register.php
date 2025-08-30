<div class="register-form-wrapper">
                <?php 
                // Check if Ultimate Member plugin is active
                if (function_exists('UM')) {
                    echo do_shortcode('[facetwp facet="new_facet_artiste"]');
                } else {
                    echo '<div class="alert alert-warning">' . __('Ultimate Member plugin is not active. Please install and activate the Ultimate Member plugin to display the registration form.', 'bdcomic_theme') . '</div>';
                }

                echo do_shortcode('[facetwp template="new_template_artiste"]');
                ?>
            </div>