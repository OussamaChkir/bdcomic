function setTheme(theme) {
  document.documentElement.setAttribute('data-theme', theme);
  document.cookie = "theme=" + theme + "; path=/; max-age=31536000"; // 1 year
}

function getCookie(name) {
  const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
  return match ? match[2] : null;
}

document.addEventListener('DOMContentLoaded', function () {
  const savedTheme = getCookie('theme');

  if (savedTheme === 'dark' || savedTheme === 'light') {
    setTheme(savedTheme); // User preference saved in cookie
  } else {
    // Browser/system preference
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    setTheme(prefersDark ? 'dark' : 'light');
  }

  const toggle = document.getElementById('theme-toggle');
  if (toggle) {
    toggle.addEventListener('click', function () {
      const current = document.documentElement.getAttribute('data-theme');
      const newTheme = current === 'dark' ? 'light' : 'dark';
      setTheme(newTheme); // Save user choice in cookie
    });
  }
});
