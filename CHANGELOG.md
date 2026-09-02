## 3.8.10.28

- Increased the open/up submenu chevron offset so the arrow sits farther right inside the mobile submenu toggle.
- Keeps the closed/down chevron position unchanged.

## 3.8.10.27
- Submenu toggle: when the chevron is in its open/up state, add 3px left margin so the arrow is visually aligned inside the toggle.

## 3.8.10.26
- Mobile mega-menu: open and hover states now explicitly set `transform: none` at the same selector specificity as the desktop mega-menu rule, while retaining the `translate` reset.

## 3.8.10.25
- Alignment and layout cleanup: nested column containers align to the column edge, hero pagination sits inside the hero surface, client-result arrows center on card boxes, partner Swipers no longer center the entire track, empty generated paragraphs/CTA wrappers are removed, legal-page spacing is reduced, dark search titles are readable, and mobile mega-menu translate is reset.

## 3.8.10.23

## 3.8.10.24

- Centres partner/logo content per slide without centring the Swiper track.
- Vertically centres client-results navigation against the actual card viewport.
- Reduces Privacy / Terms / Cookies spacing and forces legal copy onto the full content grid.
- Adds spacing above home hero actions and increases FAQ typography.
- Hardens mobile mega-menu width, wrapping and generated Bootstrap/grid alignment.
- Fixes dark-mode search-result title contrast.

- Fixed the header light/dark control so the sun/moon glyph always renders, including dark mode.
- Legal-policy columns now ignore generated Bootstrap offsets and use the full content grid; media/text actions stay aligned to the copy edge.
- Partner/logo items are horizontally centred, gallery slide captions share a consistent baseline, and dark hero/proof/case metrics use readable contrast.
- Business demo strings now use the maintained Latvian JSON dictionary, child-owned patterns are translatable, and the client-results carousel includes a fourth slide so navigation has a real next state.

## 3.8.10.22
- Alignment pass: section heading rules, Insights hero/browser grid, proof/stat grids and What Guides Us cards now share the same column edges.
- Mobile Services mega menu is constrained to the drawer and stacks its internal columns without horizontal overflow.
- Dark-mode contact cards explicitly use light label/value/link colours.
- Share copy-link uses the same 40px square geometry as LinkedIn, Facebook and X.
- Added SEO guardrails: missing Yoast page meta descriptions are backfilled/fallback-generated without overwriting custom copy, and frontend output is normalized to exactly one H1.

## 3.8.10.20
- Child-only final layout pass: full-grid FAQ/legal/single-post/search, padded left-aligned Latest Thinking, dense Insights archive, readable app badges, visible partner logos, testimonial navigation gutters, sitemap link treatment, dialog close control, and Mega Menu SEO exclusions.
- Parent theme remains unchanged at 3.8.10.18.

## 3.8.10.19

- Child-only visual fixes: full-width FAQ, padded/left-aligned Latest Thinking cards, dense Insights archive, full-grid single posts, readable footer install badges, visible partner logos, and testimonial arrow gutters.
- Parent theme remains unchanged at 3.8.10.18.

## 3.8.10.17
- Final visible-layout corrections: full-width FAQ grid, padded left-aligned Latest Thinking cards, full-width single-post editorial content, gap-free featured archive card, readable PWA install badges, full-size partner logo marks, and testimonial navigation gutters.
- WordPress admin theme screenshots regenerated from the previously approved consistent illustration system.

## 3.8.10.16
- Final visual QA pass: restored premium spacing, card geometry, forms, icons, header/footer, FAQ, article/blog grids, dark mode, search results and 404 presentation.
- Child themes remain fully presentation-owned; parent remains frontend-CSS free.
- Restored approved 1200x900 WordPress admin theme screenshots and Yarn 1 compatible child build metadata.

## 3.8.10.15 — Premium UI + Yarn 1 build compatibility

- Removed the Yarn 3/Corepack package-manager pin so `yarn prod` works with global Yarn 1.22.x.
- Restored the previously supplied WordPress Appearance screenshot for this child theme.
- Added the final child-owned premium UI layer for spacing, buttons, icons, cards, forms, FAQ, blog, footer and dark mode.
- Buttons now stay content-width (`fit-content`) instead of stretching accidentally.
- Larger, better-proportioned card/partner icons and aligned case/proof metrics.
- Parent remains presentation-free.

## 3.8.10.14 — Premium visual contract

- Restored premium child-theme geometry, spacing, borders, cards, forms, search, blog/article layouts, footer and dark mode.
- Added compact language dropdown and consistent search-field treatment.
- Parent remains presentation-free; all frontend presentation is owned by the child theme.
- Build fallback now includes functional UI and the final child design-system layer deterministically.

## 3.8.10.13
- Rebuilt the WordPress admin theme preview as a consistent 1200×900 illustrated sector screenshot shared across the maintained suite.


## 3.8.10.10
- Fixed managed header row alignment and removed the duplicate mobile language switcher from desktop markup.
- The single language switcher now remains in the right-side header actions across responsive sizes.
## 3.8.10.9
- Restores the maintained-suite managed header/footer after the clean-SCSS refactor.
- Restores shared blog preview grids, full-width article reading layout, CTA/card alignment and floating utility constraints from clean selectors (no `!important`, no `clamp()`).
- Keeps legacy Garilla/bespoke children outside the managed suite while recognising renamed current-suite child folders by theme metadata.

## 3.8.10.8
- Clean SCSS architecture inherited from the parent suite: no active `!important`, no `clamp()`, fluid responsive type helper, and no version-specific fix partials.
- Shared Latest Thinking grid receives 30px bottom breathing room and the blog-search submit icon is vertically centred at 50%.
- Rebuilt production CSS from the cleaned modular source.

## 3.8.10.7
- Cross-theme final QA: shared alignment/search/translation/cache fixes from the parent.

## 3.8.10 - 2026-08-31
- Compatibility rebuild for WP BBTheme parent 3.8.10 shared FAQ, Blog search, Polylang translation repair, mega-menu hover bridge and single-post duplicate-image fixes.
- No sector-specific Woo Support changes; WP Theme Woo Support remains unchanged at 3.4.0.

## 3.8.9 - 2026-08-31
- Inherits parent full-width single-post layout, Polylang starter translation repair and mobile CTA/alignment fixes.

## 3.8.8 - 2026-08-31
- Inherits the parent automatic Polylang Starter Setup, flag-dropdown language switcher, header/mobile-menu repair, translated search shell and shared spacing/alignment refinements.
- Woo Support remains unchanged at 3.4.0.

## 3.8.7 - 2026-08-31
- Added a Starter Setup language bar with English-first European, Baltic and Nordic presets; configured Polylang languages become live links.
- Refined shared spacing, card geometry, Blog/archive/single-post alignment, gallery cards, footer forms and tablet/mobile navigation.
- Expanded the native motion engine with Garilla-style reveal, directional, stagger and restrained editorial float effects across all child themes.
- Strengthened the body-level full-viewport lightbox and kept Woo Support unchanged at 3.4.0.

## 3.8.6 - 2026-08-31
- Consolidated cross-theme spacing, card, header, mobile-drawer and footer geometry.
- Refined hero actions, partner strip, process/stat/case layouts and Blog toolbar.
- Gallery cards now stay image-first and the shared lightbox uses the full viewport.
- Improved tablet/mobile navigation and Polylang switcher presentation.

# Changelog

## 3.8.5
- Shared parent maintenance compatibility: full-viewport gallery, multilingual header reliability and layout/alignment cleanup.

## 3.8.4
- Inherits the parent 3.8.4 full-viewport gallery, Polylang-aware header/search, corrected light/dark action icons, mobile navigation state and shared spacing/icon refinements.

## 3.8.3
- Inherits the parent 3.8.3 gallery lightbox, mobile-header, dark-mode contrast, spacing and form/control alignment fixes.

## 3.8.2
- Inherits the parent surface-based light/dark contrast fixes, aligned header/buttons/forms, improved blog featured-card proportions and accessible full-screen item-gallery viewer.

## 3.8.1
- Added shared sector/product thumbnail galleries for items with multiple images.
- Added the child-theme project favicon, Apple touch icon and PWA install icons.
- Inherits the parent native motion system and expanded Forms pattern/demo library.

## 3.8.0
- Integrated shared Site Map, Privacy/Terms, cookie consent, Apple/Android install/PWA, enhanced blog archive/single-post tools and editor productivity from WP BBTheme Core 3.8.0.
- Updated starter homepage composition for a full-width hero plus reusable BBuilder/Swiper partner-logo section.
- Added suite-wide visual/form/blog refinements while preserving this child theme's sector-specific functionality.

## 3.7.2
- Polished spacing rhythm, alignment, controls, icon contrast, cards, editorial archive layout, footer forms and responsive presentation across the starter site.

## 3.7.1
- Fixed responsive header/menu breakpoints, dark-mode controls, mobile icon states, section alignment and homepage article cards.

## 3.7.0 - 2026-08-29
- Keeps the Pharmacy Product, Pharmacy Category and Pharmacy Quote custom content model with six starter quote-catalogue products.
- Migrated the doctor-directory demo/archive from a shortcode block to the reusable WP BBuilder Sector Finder block; the legacy shortcode remains available for backward compatibility.
- Keeps the BBuilder Pharmacy Catalogue homepage/pattern content, quote-first product flow, navigation, mega-menu/search integration and responsive pharmacy styling.
## 3.5.0 - 2026-08-29
- Added premium Health Insights AJAX archive and editorial single-post presentation.
- Reworked mobile header behaviour so hamburger and dark-mode controls appear only below 992px without desktop overlap.
- Normalised appointment/contact/newsletter form grids, field heights, checkbox/radio sizing and responsive stacking.

## 3.3.0
- Added complete Contact/About/Blog demos, case studies, gallery Swiper, exclusive FAQ accordion, scroll-to-top, icon-only header search, stronger Woo/account/archive styling and refreshed theme previews.

## 3.2.0
- Premium visual-system pass: corrected desktop navigation alignment, stronger section hierarchy, sector-specific presentation, richer icon cards and refreshed theme screenshot.

# Changelog

## 3.1.0 - 2026-08-28
- Added dedicated doctor archive/single templates and Doctors/Appointments/Patient Info mega-menu content.
- Repaired demo patterns and kept booking provider/service/date/time workflow in WP BBuilder.

## 3.0.0 — 2026-08-28

- Initial Medicine child theme with doctor CPT, specialities, locations, AJAX directory and appointment booking integration.
- Bootstrap/Gutenberg demo, Swiper hero, local demo artwork, clean SCSS/JS source and Yarn production build.
## 3.8.10.11
- Restored the cascade contract after the clean-SCSS refactor: shared parent geometry now uses low-specificity `:where()` selectors and loads before child presentation CSS.
- Current-suite child CSS explicitly loads after the parent clean UI layer; sector spacing, borders, colours, mobile navigation and dark-mode rules can override the shared geometry again.
- Added a restrained shared dark-mode baseline for parent-only use while preserving child-specific dark palettes.

