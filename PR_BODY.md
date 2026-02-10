PR: Feature/homepage-polish — Arabic homepage polish

This PR adds:
- `front-page.php` — Arabic hero and featured books grid (improved CTA, ARIA attributes)
- responsive and RTL CSS tweaks in `css/library-style.css`
- placeholder SVG cover `images/placeholder-book.svg`
- basic CI workflow `.github/workflows/ci-basic.yml`

Notes:
- Header and footer credit are provided by MU-plugin `library-theme-custom.php`.
- Polylang is configured programmatically; Arabic set as default and front page linked.
- Recommend reviewing images and replacing placeholder covers with actual media.
