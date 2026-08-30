import { initHeader } from './components/header.js';
import { observeMotion } from './components/motion.js';
import { initBlog } from './components/blog.js';

function boot() {
  initHeader();
  observeMotion();
  initBlog();
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', boot, { once: true });
} else {
  boot();
}
