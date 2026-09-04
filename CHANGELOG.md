# Changelog

## 3.8.10.48
- Fixed the Theme Settings General-tab password panel so its CSS is loaded as admin CSS rather than displayed as text.
- Hardened the resumable sector-media repair against unavailable WordPress image-admin helpers and clears stale repair errors after a successful batch.
- Refined shared card density, directory alignment, responsive spacing, dark-mode contrast and compact in-image gallery pagination.

## 3.8.10.47
- Child-only layout density, spacing, contrast and gallery refinements.
- Improved WooCommerce single-product template reliability where applicable.

## 3.8.10.46
- Prevents heavy legacy sector-image migrations from running synchronously in the WordPress dashboard; a resumable small-batch worker now performs repairs safely.
- Keeps frontend password protection inside **Theme Settings → General**.
- Places compact gallery thumbnails inside the bottom of the main image and retains the accessible full-screen modal.
- Restores active-sector demo, directory and managed Blog imagery after switching child themes, with Hotel posts using hospitality imagery.
- Includes parent/plugin compatibility fixes for WordPress 6.7+ translation timing and ACF initialization.

## 3.8.10.45

- Normalised full-width section rhythm to 80px desktop, 64px tablet and 52px mobile, with a 50px minimum gap through linked stats/proof groups.
- Added explicit light- and dark-mode card typography so headings, descriptions, metadata and links remain readable inside every sector layout.
- Top-aligned and rescaled About/media rows across the shared page system.
- Reassigned demo blog, directory, hero, gallery and page media from the active child theme so sector switching cannot leave images from another industry.
- Added richer thumbnail galleries and a keyboard/touch-accessible full-screen modal to Hotel rooms and other directory themes using the same card/single layout.
- Preserved the configurable frontend password protection introduced in 3.8.10.44.

## 3.8.10.44

- Standardised the default-on frontend password gate across all 13 child themes.
- The initial frontend password is `wp@demo`; it is stored only as a WordPress password hash.
- Added enable/disable and password-change controls at Settings → Theme Settings (`/wp-admin/options-general.php?page=wp-theme-settings`).
- Added signed 24-hour access cookies, administrator bypass, no-cache headers and safe exemptions for wp-admin, AJAX, cron, REST and XML-RPC requests.
- Existing protection settings and changed passwords from 3.8.10.42/3.8.10.43 are preserved.
- Visual alignment, colour and media corrections from 3.8.10.43 are unchanged.

## 3.8.10.43

- Added the shared card/icon alignment pass used by all 13 child themes.
- Fixed dark-mode directory-card links, metadata values, labels and action contrast.
- Top-aligned media/text rows, removed generated block gaps and normalised proof/stat flow.
- Removed duplicated single-item excerpts and aligned request/detail panels to the page grid.
- Added deterministic eager loading for visible hero, gallery, blog, directory and product images.
- Added an active-theme-aware media refresh so shared AVIF/WebP/JPEG demo attachments are rewritten after child-theme switches and receive current titles/alt text.
- Plugins and parent-theme requirements are unchanged.

## 3.8.10.42

- Rebuilt demo/blog featured-image set from this child theme's own sector media.
- Added v381041 one-time media refresh so existing optimized AVIF/WebP/JPEG attachments are rewritten and blog thumbnails are reassigned.
- Plugins are unchanged.

## 3.8.10.40
- Fixed stale imported demo images when WordPress/image optimizers changed attachment originals to AVIF/WebP.
- Refresh now resolves bundled photography by filename stem and regenerates attachment sub-sizes/metadata.
- Retains the sector-specific photography, outline SVG icon system and select/finder UI fixes.

## 3.8.10.38

## 3.8.10.40
- Theme-only visual QA release with sector-correct photographic demo media.
- Refreshed imported-media migration so existing demo attachments are updated in place.
- Replaced legacy filled icon assets with a consistent modern outline SVG set.
- Hardened select chevrons and finder action alignment.
- Improved media sharpness and contact-detail rendering.

- Restored visible select chevrons with 18px right-edge spacing and aligned filter buttons.
- Re-runs realistic demo-media migration so already-imported attachments receive the corrected sector photography.

## 3.8.10.36

## 3.8.10.38
- Select controls now use a consistent custom chevron positioned 18px inside the right edge, with 48px right padding so filter/contact labels never crowd the arrow.
- Added a matching light chevron for dark theme controls. No plugin changes.

- Forced realistic media refresh for already imported demos.
- Ensured sector-specific photographic demo/blog/page imagery.

## 3.8.10.34
- Fixed legacy motion blocks remaining invisible after load by restoring `.is-visible` compatibility.
- Re-runs the admin-only realistic-media upgrade so existing demo/blog/page thumbnails pick up the refreshed sector photography.
- Keeps WordPress image metadata APIs loaded before attachment insertion to avoid `wp_generate_attachment_metadata()` fatals.

## 3.8.10.32
- Fixed realistic-media attachment creation so WordPress image-admin functions load before `wp_insert_attachment()` fires `add_attachment`.
- The one-time media upgrade now runs from normal WP Admin initialization only, not `wp_loaded`, AJAX, or cron.

## 3.8.10.30
- Dark-mode form and WooCommerce polish.
- Project-preview imagery and improved icon sizing.

## 3.8.10.29
- Fixed WooCommerce header icon controls and My Account layout.
- Added demo import refresh and empty-home integrity guard.

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

