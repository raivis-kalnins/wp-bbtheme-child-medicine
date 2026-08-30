/**
 * Navigation runtime is owned by the neutral wp-bbtheme parent.
 * Child themes only provide presentation so behaviour stays consistent.
 */
function initHeader() {
  return undefined;
}

const MOTION_SELECTOR = '[data-motion], .motion-fade-up, .motion-fade-left, .motion-fade-right, .motion-scale-in, .motion-reveal';

function show(node) {
  node.classList.remove('motion-pending');
  node.classList.add('motion-in');
}

function initMotion(root = document) {
  const reducedMotion = window.matchMedia
    && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const nodes = [...root.querySelectorAll(MOTION_SELECTOR)];

  if (reducedMotion || !('IntersectionObserver' in window)) {
    nodes.forEach(show);
    return;
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      show(entry.target);
      observer.unobserve(entry.target);
    });
  }, {
    threshold: 0.12,
    rootMargin: '0px 0px -48px 0px',
  });

  nodes.forEach((node, index) => {
    if (node.dataset.motionReady) return;
    node.dataset.motionReady = '1';
    node.classList.add('motion-pending');
    if (!node.style.getPropertyValue('--motion-delay')) {
      node.style.setProperty('--motion-delay', `${Math.min(index % 6, 5) * 70}ms`);
    }
    observer.observe(node);
  });
}

function observeMotion() {
  initMotion(document);
  if (!window.MutationObserver || !document.body) return;

  let timer = 0;
  const mutationObserver = new MutationObserver((records) => {
    if (!records.some((record) => record.addedNodes.length > 0)) return;
    window.clearTimeout(timer);
    timer = window.setTimeout(() => initMotion(document), 90);
  });

  mutationObserver.observe(document.body, { childList: true, subtree: true });
}

function initBlog() {
  document.querySelectorAll('[data-blog-app]').forEach((app) => {
    const form = app.querySelector('[data-blog-search-form]');
    const input = form?.querySelector('input[type="search"]');
    const filters = app.querySelector('[data-blog-filters]');
    const list = app.querySelector('[data-blog-list]');
    const results = app.querySelector('[data-blog-results]');
    const count = app.querySelector('[data-blog-count]');
    const empty = app.querySelector('[data-blog-empty]');
    const loadWrap = app.querySelector('[data-blog-load-wrap]');
    const loadButton = app.querySelector('[data-blog-load-more]');
    if (!list || !results) return;

    const state = {
      search: input?.value || '',
      category: filters?.querySelector('.is-active')?.getAttribute('data-blog-category') || '',
      page: 1,
      loading: false,
    };

    const setBusy = (busy) => {
      state.loading = busy;
      results.classList.toggle('is-loading', busy);
      results.setAttribute('aria-busy', busy ? 'true' : 'false');
      if (loadButton) {
        loadButton.disabled = busy;
        loadButton.textContent = busy ? 'Loading…' : 'Load more';
      }
    };

    const updateUrl = () => {
      const url = new URL(window.location.href);
      if (state.search) url.searchParams.set('blog_search', state.search); else url.searchParams.delete('blog_search');
      if (state.category) url.searchParams.set('blog_category', state.category); else url.searchParams.delete('blog_category');
      url.searchParams.delete('paged');
      window.history.replaceState({}, '', url.toString());
    };

    const request = async (page, append = false) => {
      if (state.loading) return;
      setBusy(true);
      const data = new FormData();
      data.set('action', 'wp_theme_blog_filter');
      data.set('nonce', app.dataset.nonce || '');
      data.set('search', state.search);
      data.set('category', state.category);
      data.set('page', String(page));
      data.set('per_page', app.dataset.perPage || '6');
      try {
        const response = await fetch(app.dataset.ajaxUrl || '/wp-admin/admin-ajax.php', {
          method: 'POST',
          credentials: 'same-origin',
          body: data,
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
        });
        if (!response.ok) throw new Error('Request failed');
        const json = await response.json();
        if (!json?.success || !json.data) throw new Error('Invalid response');
        if (append) list.insertAdjacentHTML('beforeend', json.data.html || '');
        else list.innerHTML = json.data.html || '';
        state.page = page;
        if (count) count.textContent = json.data.countLabel || '';
        if (empty) empty.hidden = Boolean((json.data.html || '').trim()) || append;
        if (loadWrap) loadWrap.hidden = !json.data.hasMore;
        if (loadButton) loadButton.dataset.page = String(json.data.nextPage || page + 1);
        updateUrl();
        document.dispatchEvent(new CustomEvent('wpTheme:blogUpdated', { detail: { app, page, append } }));
      } catch (error) {
        if (!append) {
          const url = new URL(window.location.href);
          if (state.search) url.searchParams.set('blog_search', state.search);
          if (state.category) url.searchParams.set('blog_category', state.category);
          window.location.assign(url.toString());
        }
      } finally {
        setBusy(false);
      }
    };

    form?.addEventListener('submit', (event) => {
      event.preventDefault();
      state.search = input?.value.trim() || '';
      state.page = 1;
      request(1, false);
    });

    filters?.addEventListener('click', (event) => {
      const button = event.target.closest('[data-blog-category]');
      if (!button) return;
      filters.querySelectorAll('[data-blog-category]').forEach((item) => {
        const active = item === button;
        item.classList.toggle('is-active', active);
        item.setAttribute('aria-pressed', active ? 'true' : 'false');
      });
      state.category = button.getAttribute('data-blog-category') || '';
      state.page = 1;
      request(1, false);
    });

    loadButton?.addEventListener('click', () => {
      const next = Number.parseInt(loadButton.dataset.page || String(state.page + 1), 10);
      request(Number.isFinite(next) ? next : state.page + 1, true);
    });
  });

  document.querySelectorAll('[data-copy-article-link]').forEach((button) => {
    button.addEventListener('click', async () => {
      const url = button.getAttribute('data-url') || window.location.href;
      const original = button.textContent;
      try {
        await navigator.clipboard.writeText(url);
        button.textContent = 'Copied';
      } catch (error) {
        window.prompt('Copy this link', url);
      }
      window.setTimeout(() => { button.textContent = original; }, 1600);
    });
  });

  document.querySelectorAll('[data-blog-single]').forEach((article) => {
    const content = article.querySelector('[data-blog-article-content]');
    const toc = article.querySelector('[data-blog-toc]');
    const tocList = article.querySelector('[data-blog-toc-list]');
    if (!content || !toc || !tocList) return;
    const headings = Array.from(content.querySelectorAll('h2, h3'));
    if (headings.length < 2) {
      toc.hidden = true;
      return;
    }
    const used = new Set();
    headings.forEach((heading, index) => {
      let id = heading.id || heading.textContent.toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') || `section-${index + 1}`;
      const base = id;
      let suffix = 2;
      let existing = document.getElementById(id);
      while (used.has(id) || (existing && existing !== heading)) {
        id = `${base}-${suffix++}`;
        existing = document.getElementById(id);
      }
      used.add(id);
      heading.id = id;
      const link = document.createElement('a');
      link.href = `#${id}`;
      link.textContent = heading.textContent;
      if (heading.tagName === 'H3') link.classList.add('is-subheading');
      tocList.appendChild(link);
    });
  });
}




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

