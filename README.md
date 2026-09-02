# WP BBTheme Medicine 3.7.0

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

## SCSS structure (3.8.10.9)

Frontend styles are split into `tokens`, `tools`, `base`, `header`, `footer`, `components`, `swiper`, `motion`, `forms`, `blog`, `quality`, `sector`, `responsive` and `features`. Fluid typography uses the suite `fluid-font()` mixin and explicit viewport guards rather than `clamp()`. The generated production CSS intentionally contains no `!important` declarations.

### Build compatibility

The child build is dependency-free and works with Yarn 1.22.x as well as newer Yarn versions. No Corepack step is required. Use:

```sh
yarn prod
```

The command runs `node tools/build.mjs` and rebuilds the hashed CSS/JS manifest directly.
