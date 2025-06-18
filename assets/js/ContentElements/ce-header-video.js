document.addEventListener('DOMContentLoaded', function () {
  const videos = document.querySelectorAll('.video-click-toggle');

  videos.forEach(video => {
    video.addEventListener('click', function () {
        const wrapper = video.closest('.block-header-video');

        if (video.paused) {
            video.play();
            wrapper.classList.remove('paused');
        } else {
            video.pause();
            wrapper.classList.add('paused');
        }
    });
  });
});

function scrollToNextSection() {
  const headerEl = document.querySelector('header');
  const adminBar = document.getElementById('wpadminbar');
  const adminBarHeight = adminBar ? adminBar.offsetHeight : 0;
  const headerHeight = headerEl ? headerEl.offsetHeight : 0;

  const totalOffset = headerHeight - adminBarHeight;

  const block = document.querySelector('.block-header-video');

  if (block) {
    const bottom = block.offsetTop + block.offsetHeight - totalOffset;

    window.scrollTo({
      top: bottom,
      behavior: 'smooth'
    });
  }
}