<?php 

$space = get_field('space');

if ($space): 
    $height = $space['height'];
    $height_md = $space['height_md'];
    $height_sm = $space['height_sm'];
    $unit = $space['unit'];   
?> 

    <div class="block-space" style="height: <?= $height . $unit; ?>;">
        <style>
            <?php if ($height_md != ''): ?>
                @media (max-width: 991px) {
                    .block-space {
                        height: <?= $height_md . $unit; ?> !important;
                    }
                }
            <?php endif; ?>

            <?php if ($height_sm != ''): ?>
                @media (max-width: 767px) {
                    .block-space {
                        height: <?= $height_sm . $unit; ?> !important;
                    }
                }
            <?php endif; ?>
        </style>
    </div>

<?php endif; ?>