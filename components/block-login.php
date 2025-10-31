<div class="profile-form-wrapper">
                <?php 
                // Check if Ultimate Member plugin is active
                if (function_exists('UM')) {
                    echo do_shortcode('[ultimatemember form_id="42"]');
                } else {
                    echo '<div class="alert alert-warning">' . __('Ultimate Member plugin is not active. Please install and activate the Ultimate Member plugin to display the login form.', 'bdcomic_theme') . '</div>';
                }
                ?>
            </div>