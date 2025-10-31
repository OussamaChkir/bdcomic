function setTheme(theme) {
  document.documentElement.setAttribute('data-theme', theme);
  document.cookie = "theme=" + theme + "; path=/; max-age=31536000"; // 1 year
}

function getCookie(name) {
  const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
  return match ? match[2] : null;
}

// Immediately apply theme to prevent flash of unstyled content
(function() {
  const savedTheme = getCookie('theme');

  if (savedTheme === 'dark' || savedTheme === 'light') {
    document.documentElement.setAttribute('data-theme', savedTheme);
  } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
    document.documentElement.setAttribute('data-theme', 'dark');
  } else {
    document.documentElement.setAttribute('data-theme', 'light');
  }
})();

// Wait for DOM ready to setup toggle button event
document.addEventListener('DOMContentLoaded', function () {
  const toggle = document.getElementById('theme-toggle');
  if (toggle) {
    toggle.addEventListener('click', function () {
      const current = document.documentElement.getAttribute('data-theme');
      const newTheme = current === 'dark' ? 'light' : 'dark';
      setTheme(newTheme); // Save user choice in cookie
    });
  }
});