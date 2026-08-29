# Medicine v3.3

v3.3 adds the complete demo-page layer: richer About history, Contact form/map/details, five sector demo articles with local media, homepage case studies and Swiper gallery, exclusive FAQ behavior, scroll-to-top, icon-only header search, refreshed Appearance screenshot and additional archive/account polish where relevant. Run **Appearance → Starter Setup → Import / Refresh Starter Website** after upgrading so the stored demo content is rebuilt.

## 3.3.0
- Added complete Contact/About/Blog demos, case studies, gallery Swiper, exclusive FAQ accordion, scroll-to-top, icon-only header search, stronger Woo/account/archive styling and refreshed theme previews.

# v3.2 update note

See the suite README for the v3.2 navigation, demo-switching and visual-system changes.

# WP BBTheme Medicine 3.0.0

Non-WooCommerce medical directory child theme for `wp-bbtheme`.

## Included

- Doctor directory CPT with speciality and location taxonomies.
- AJAX doctor search/filter and doctor profile pages.
- WP BBuilder provider/service/date/time appointment booking.
- Starter demo doctors, appointments page, menus and sector content.
- Swiper hero and Gutenberg patterns.
- Polylang-ready shared header/footer through the parent.
- Clean source in `src/scss` and `src/js`.

## Build

```bash
yarn prod
```

The command writes hashed production assets and `dist/.vite/manifest.json`. There are no package dependencies; Node 18+ and Yarn are sufficient.
