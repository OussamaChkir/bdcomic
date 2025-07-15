jQuery(document).ready(function($) {
    function updateFirstThreePosts() {
        $('.post-teaser').removeClass('post-teaser-first');
        $('.post-teaser:visible').slice(0, 3).addClass('post-teaser-first');
    }

    updateFirstThreePosts();

    $('.filter-btn').on('click', function(e) {
        e.preventDefault();

        const selectedTag = $(this).data('tag');

        $('.filter-btn').removeClass('active');
        $(this).addClass('active');

        if (selectedTag === 'all') {
            $('.post-teaser').show();
        } else {
            $('.post-teaser').hide().filter('.tag-' + selectedTag).show();
        }

        updateFirstThreePosts();
    });
});