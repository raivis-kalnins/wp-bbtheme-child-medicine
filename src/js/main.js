import { initHeader } from './components/header.js';
import { observeMotion } from './components/motion.js';

function boot() {
  initHeader();
  observeMotion();
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', boot, { once: true });
} else {
  boot();
}
