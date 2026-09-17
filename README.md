## 3.8.11.43 deterministic media ownership + final live rail repair
- Replaces fragile `<picture>/<source>` runtime handling with one canonical image owner per hero, doctor and pharmacy card, fixing the blank media areas seen on the live homepage.
- Adds a new wide AVIF hero composition so the clinician is no longer enlarged across the whole banner; keeps the Events v140 measured rail and hero proportions.
- Neutralises nested Bootstrap container max-widths inside homepage sections so headings, cards, booking, trust, pharmacy and editorial sections share one horizontal rail.
- Normalises Medicine button/link/eyebrow colours and section rhythm while retaining the Events 3.8.11.40 geometry owner.

## 3.8.11.42 final Events-parity alignment, AVIF and Medicine colour repair
- Keeps the Events 3.8.11.40 `suite-v140.css/js` byte-for-byte as the sole shared geometry owner; removes the interim v141 Medicine layer instead of stacking another rail calculation.
- Corrects the hero crop by using the native wide source ratio with a 430–560px responsive height, the Events measured content edge and a softer copy gradient.
- Adds one Medicine-only v142 layer for the sections Events does not own: booking, doctor directory, trust, pharmacy, about/media-text and Medicine-specific 4/3/3-column grids.
- Replaces visible homepage media at runtime with local high-resolution AVIF assets, including eight unique clinician portraits and six unique pharmacy illustrations.
- Restores the original Medicine palette (`#117b8b`, `#0b5865`, `#10283a`) and replaces the legacy bright-green newsletter band with the intended dark navy footer treatment.
- Preserves two-column CTA headings while expanding single-column section headings to the common rail, fixing the narrow trust/about/pharmacy alignment regressions.

## 3.8.11.41 Events-parity layout + AVIF media finish
- Restores `suite-v140.css/js` to the exact clean Events 3.8.11.40 layout owner so Medicine no longer carries a second competing v140 geometry system.
- Adds one Medicine-only v141 layer for the full-bleed Events-style hero, wide shared section rail, consistent 64px section rhythm, card grids, booking/directory widths and Medicine colour tokens.
- Replaces visible hero, about, doctor, pharmacy, gallery and editorial media at runtime with bundled local AVIF assets; hero files are 1920x1080 and card photography is cropped consistently for each component.
- Uses a wide 1320px fallback rail and rejects accidentally narrow header measurements, preventing sections from collapsing into the narrow centre column seen in the previous build.
- Future managed-demo profiles also point at the new AVIF hero/about/gallery media.

## 3.8.11.40 Events-parity layout recovery
- Ports the proven Events 3.8.11.40 recovery architecture: v118 is the stable frontend base and one v140 layer owns late layout geometry.
- Retires shared v119-v139 frontend geometry plus Medicine v137/v138 asset owners so old emergency fixes no longer compete over the same hero, rows and gutters.
- Restores the v97 hero finder removed by v138, keeps the slider DOM intact and forces the two bundled 2560px v118 hero images for a crisp, non-blank hero.
- Measures the live header container and uses that exact left/right rail for every homepage section, booking area and repeated card grid, matching the Events theme alignment model.
- Makes the immediate card row the only grid owner for services, specialities, pathways, trust, doctors, pharmacy, stats, process, gallery and editorial cards.
- Restores the Medicine profile palette (brand #176b87, soft #f2f8f8) instead of the generic sector-green fallback, with consistent white/soft section surfaces.
- Hides the redundant standalone clinical-search CTA when the hero finder is present and keeps the doctor, pharmacy and gallery media treatments responsive and crisp.

## 3.8.11.38 Medicine homepage repair
- Replaces the conflicting legacy hero/finder treatment with one deterministic split hero and a guaranteed high-resolution clinical image.
- Removes the duplicate detached hero search row; the dedicated Medicine search panel is now the single search bridge below the hero.
- Forces stats, trust, doctors, pharmacy, gallery, case studies and insights onto the same 1180px section line with consistent responsive gutters.
- Fixes the clipped fourth stat and makes the trust section a full-width intro plus four-card grid.
- Renders doctor and pharmacy SVG artwork directly and crisply, removes the broken mini-gallery treatment from doctor cards, and restores missing gallery/blog images from bundled high-resolution media.
- Normalises repeated photography to a consistent 4:3 crop and equal-height card bodies/actions.

## 3.8.11.37 Medicine section consistency
- All medicine homepage card collections now use the same responsive 1/2/3-or-4-column grid system and shared 24px desktop gutter.
- Search, booking, doctor, pharmacy and BBuilder section shells align to the same 1180px content line.
- Trust cards expand to the full section grid instead of being constrained to a split-column sub-grid.
- About and gallery media use bundled high-resolution medicine photography at a consistent 4:3 presentation; doctor and pharmacy vector/product media retain crisp native rendering.

## 3.8.11.16 reset-safe final release fixes
- Demo reset/import now re-runs the canonical managed BBuilder page rebuild and all v116 repairs automatically.
- The old migration that removed legitimate responsive BBuilder column widths is disabled; desktop multi-column layouts survive a clean demo reset.
- Desktop mega menus use the measured header bottom plus a hover bridge, matching the close Jobs positioning across all children.
- Home hero sliders use three distinct child-owned images, visible pagination, 8.5-second autoplay and pause-on-hover with sharp natural-scale rendering.
- Managed demo/editorial/catalogue/gallery/Woo media is restored from bundled files; Woo Clothes keeps the complete bundled product-image pool.
- Quote drawers use resilient trigger detection and sit flush to the right viewport edge on quote-enabled themes.
- Cookie-consent acceptance persists across reloads using a stable browser marker.
- Legal pages remain left-aligned on the normal grid; Latest Thinking media/card edges are normalized.
- Partner/brand serialization is normalized idempotently to prevent repeated wrappers and the `wpbb/column` validation warning.
- Theme Settings retain child-owned controls for disabling dark mode and keeping English-only Polylang content.

## 3.8.11.14 child-only settings, editor, legal, editorial and hero finish

- Latest Thinking card rows now use the same 1320px grid as their headings; the 1440px row override that shifted the first card left has been removed.
- Hero images use a direct child-owned native source, stronger left-edge gradient masking and no CSS blur/viewport stretching.
- Appearance > Theme Settings adds switches to disable dark mode and disable translations/keep English only. Enabling English-only moves non-English Polylang Pages and Posts to Trash and hides the language switcher.
- Privacy/Terms/Cookies content spans the normal site grid and is left-aligned instead of being forced into a centered narrow column.
- The known raw partner-heading serialization defect is repaired in imported pages and on future page saves, resolving the wpbb/column validation error.
- Child editor CSS is moved from enqueue_block_editor_assets to enqueue_block_assets for the WordPress editor iframe.

## 3.8.11.13 child-only hero and editorial grid finish

- Latest Thinking / related editorial cards use the full 1440px site grid; the legacy outer BBuilder row and list start padding can no longer create a first-card left inset.
- Homepage heroes use a native-resolution child asset without viewport-width stretching.
- A stronger white-to-transparent hero gradient crosses the photograph's left edge so the image seam is hidden.
- Existing and translated managed hero blocks are refreshed from the same child-owned asset after upgrade.

## 3.8.11.12 child-only visual/media fixes

- Latest Thinking card grid now inherits the section grid with no first-card left inset.
- Sharper child-owned hero source and late frontend override.
- Managed catalogue and editorial media are re-synchronised from bundled child assets.
- Media/text CTA buttons align to the copy edge.

## 3.8.11.11 media and WooCommerce finalisation

- Repairs missing demo media from bundled local assets, including cloned-site WooCommerce product images and Automotive vehicle finder thumbnails.
- Forces WooCommerce filters, ranges, compare controls and product actions to the child theme accent instead of the plugin blue fallback.
- Uses a two-column desktop Basket, Checkout and My Account shell with mobile stacking only below 821px.
- Uses the highest-resolution bundled hero source during managed demo rebuilds; Business uses the 1600x1000 office source.
- Requires parent WP BBTheme 3.8.10.23 for reliable My Account header URLs on cloned sites.

## 3.8.10.82 suite consistency

Requires WP BBuilder 5.6.9+ for palette inheritance and the shared hCaptcha verifier. This release keeps the sector's individual brand colour while using the same 1440px canvas, card/form rhythm, dark-mode baseline and footer/newsletter hierarchy as the rest of the 15-theme suite. The one-time cleanup is restricted to records explicitly marked as theme-managed demo content.

## 3.8.10.65

- Fixes the Theme Settings frontend-protection panel so its CSS is loaded in the admin head instead of appearing as visible text.
- Makes sector media repair load the WordPress image API safely before generating attachment metadata.
- Refines shared card, directory, gallery and responsive alignment.

## 3.8.10.47

- More compact and consistent section spacing, cards and responsive layouts.
- Smaller in-frame gallery thumbnail pagination and improved light/dark contrast.
- Reliable child-owned WooCommerce product shells where the theme includes commerce.

# WP BBTheme Medicine 3.8.10.65
Non-WooCommerce medical directory, doctor booking and pharmacy quote starter built on WP BBTheme Core and WP BBuilder.

## v3.7 Pharmacy

- `pharmacy_product` CPT with `pharmacy_category` taxonomy and six seeded demo products.
- `pharmacy_quote` admin CPT stores product quote requests without turning the medical project into WooCommerce.
- Quote form captures contact/organisation/requirements, sends the admin notification and stores the request for follow-up.
- Pharmacy is added to navigation, mega-menu content and shared AJAX header search.
- BBuilder **Pharmacy Catalogue** variation/pattern and homepage catalogue section.
- Dedicated pharmacy archive/single presentation and responsive quote-form styling.

Doctor CPT/search, provider/service/date/time appointment booking, Health Insights AJAX blog, Contact/map, About timeline and sector patterns remain included.

Run `yarn prod`. After upgrading, run **Appearance → Starter Setup → Import / Refresh Starter Website** so Pharmacy demo products/navigation are seeded and rewrite rules are refreshed.

### 3.8.10.46
- Dashboard-safe, resumable sector media repair; no synchronous bulk image regeneration on `admin_init`.
- Password protection controls live under **Theme Settings → General**.
- Thumbnail navigation is overlaid inside the main gallery image.
- Active-sector Blog and directory media are repaired after child-theme switching.

## SCSS structure (3.8.10.9)

Frontend styles are split into `tokens`, `tools`, `base`, `header`, `footer`, `components`, `swiper`, `motion`, `forms`, `blog`, `quality`, `sector`, `responsive` and `features`. Fluid typography uses the suite `fluid-font()` mixin and explicit viewport guards rather than `clamp()`. The generated production CSS intentionally contains no `!important` declarations.

### Build compatibility

The child build is dependency-free and works with Yarn 1.22.x as well as newer Yarn versions. No Corepack step is required. Use:

```sh
yarn prod
```

The command runs `node tools/build.mjs` and rebuilds the hashed CSS/JS manifest directly.


### 3.8.10.45
- Consistent 80/64/52px section rhythm and explicit light/dark card contrast.
- Active-theme sector media repair for demo pages, blogs, directories and galleries.
- Top-aligned About imagery plus thumbnail and modal galleries on supported directory cards and single pages.

### 3.8.10.44
- Frontend password protection is enabled by default with password `wp@demo`.
- Administrators can disable it or set a new password in **Settings → Theme Settings** at `/wp-admin/options-general.php?page=wp-theme-settings`.
- Successful visitors receive a signed access cookie valid for 24 hours by default.
- Purge full-page/server/CDN caches after changing the protection setting.

### 3.8.10.42
- Replaced demo feature icons with Tabler Icons v3.46.0 outline SVGs, sized for normal UI use and coloured from the child-theme brand token.
- Single-column imported demo rows are repaired to 12 columns at every breakpoint.
- Dark-mode demo cards use explicit dark surfaces/readable text.
- Optional frontend-only demo password protection is available in Settings → Theme Settings (default password `wp@demo`).
### 3.8.10.43
- Shared alignment and dark-mode contrast fixes across service, solution, process, directory, blog and commerce cards.
- Current child-theme media is reapplied after child-theme switches, including optimised AVIF/WebP files.
- Visible slider/grid images are loaded deterministically and duplicate single-item summary text is removed.

## 3.8.10.65 BBuilder demo system
This release expects WP BBuilder 5.6.4+ and standardises demo editing around BBuilder Row/Column, Div, Icon Card, Swiper and selected native WordPress content blocks. Legacy Group/Columns demo markup is migrated automatically.


## 3.8.11.08
WooCommerce shop, basket, checkout and account layouts were normalised across the sector suite; theme preview artwork was refreshed and package documentation was reduced to this README.
