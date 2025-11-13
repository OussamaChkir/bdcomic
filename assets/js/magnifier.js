function zoom(e) {
    const zoomer = e.currentTarget;
    const rect = zoomer.getBoundingClientRect();
    const x = ((e.clientX - rect.left) / rect.width) * 100;
    const y = ((e.clientY - rect.top) / rect.height) * 100;
    zoomer.style.backgroundPosition = `${x}% ${y}%`;
  }