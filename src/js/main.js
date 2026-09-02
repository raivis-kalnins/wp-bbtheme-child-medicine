import { initHeader } from './components/header.js';
import { observeMotion } from './components/motion.js';
import { initBlog } from './components/blog.js';


function syncSwiperNavigationCenter(block) {
  const swiper = Array.from(block.children).find((child) => child.classList && child.classList.contains('swiper')) || block.querySelector('.swiper');
  if (!swiper) return;

  const slide = swiper.querySelector('.swiper-slide-active.wpbb-swiper-slide, .swiper-slide-visible.wpbb-swiper-slide, .wpbb-swiper-slide');
  let y = 0;
  if (slide) {
    const blockRect = block.getBoundingClientRect();
    const slideRect = slide.getBoundingClientRect();
    if (slideRect.height > 0) y = (slideRect.top - blockRect.top) + (slideRect.height / 2);
  }
  if (!(Number.isFinite(y) && y > 0)) y = swiper.offsetTop + (swiper.clientHeight / 2);
  if (Number.isFinite(y) && y > 0) block.style.setProperty('--wpbb-swiper-nav-y', `${Math.round(y)}px`);
}


function normalizePartnerSwipers() {
  document.querySelectorAll('.wp-theme-partners-section .swiper').forEach((swiperElement) => {
    const swiper = swiperElement.swiper;
    if (!swiper || !swiper.params) return;

    const normalize = (params) => {
      if (!params) return;
      params.centeredSlides = false;
      params.centeredSlidesBounds = false;
      params.centerInsufficientSlides = false;
      params.slidesOffsetBefore = 0;
      params.slidesOffsetAfter = 0;
    };
    normalize(swiper.params);
    normalize(swiper.originalParams);
    swiper.update();
  });
}

function syncAllSwiperNavigationCenters() {
  document.querySelectorAll('.wpbb-swiper-block--nav-gutter').forEach(syncSwiperNavigationCenter);
}

function moveTestimonialNavigationToOuterGutter() {
  document.querySelectorAll('.wpbb-swiper-block').forEach((block) => {
    const cards = block.querySelector('.wpbb-swiper--cards');
    const explicitTestimonials = block.closest('.business-testimonials, .wp-theme-testimonials-section');
    if (!cards && !explicitTestimonials) return;
    if (block.querySelector('.wpbb-swiper--hero, .wpbb-swiper--gallery, .wpbb-swiper--logos')) return;

    const prev = block.querySelector('.swiper-button-prev');
    const next = block.querySelector('.swiper-button-next');
    if (!prev && !next) return;

    block.classList.add('wpbb-swiper-block--nav-gutter');
    if (prev && prev.parentElement !== block) block.appendChild(prev);
    if (next && next.parentElement !== block) block.appendChild(next);
    syncSwiperNavigationCenter(block);
  });
}

function initPresentationGeometry() {
  let resizeTimer = 0;
  const run = () => {
    moveTestimonialNavigationToOuterGutter();
    normalizePartnerSwipers();
    window.setTimeout(() => {
      moveTestimonialNavigationToOuterGutter();
      normalizePartnerSwipers();
      syncAllSwiperNavigationCenters();
    }, 600);
  };
  window.addEventListener('resize', () => {
    window.clearTimeout(resizeTimer);
    resizeTimer = window.setTimeout(() => {
      normalizePartnerSwipers();
      syncAllSwiperNavigationCenters();
    }, 100);
  }, { passive: true });
  if (document.readyState === 'complete') run();
  else window.addEventListener('load', run, { once: true });
}

function boot() {
  initHeader();
  observeMotion();
  initBlog();
  initPresentationGeometry();
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', boot, { once: true });
} else {
  boot();
}
