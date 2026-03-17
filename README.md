# York Alumni Theme

A production-grade custom WordPress theme converted from the York Alumni HTML design. Built with zero page builders, fully dynamic via WordPress Meta Boxes, and following all WordPress Coding Standards.

---

## Requirements

| Requirement | Version |
|---|---|
| PHP | 8.1 or higher |
| WordPress | 6.0 or higher |

---

## Installation

1. Upload the `york-theme` folder to `wp-content/themes/`
2. Go to **WP Admin → Appearance → Themes**
3. Activate **York Alumni Theme**
4. Go to **Settings → Reading** → set Front page to a static page (e.g. "Home")
5. Go to **Appearance → Menus** → create a menu → assign to **Primary Navigation**

---

## Architecture Overview

The theme follows a clean separation of concerns. `functions.php` only loads include files from `inc/` — no logic lives directly in it. All WordPress hooks are registered through their respective include files. Templates use `get_template_part()` for reusable sections, and all home page content is fully dynamic via custom Meta Boxes with no hardcoded text.

---

## Folder Structure

```
york-theme/
├── style.css                        ← Theme header + compiled CSS
├── functions.php                    ← Loads all inc/ files
├── index.php                        ← Fallback template
├── front-page.php                   ← Home page template
├── page.php                         ← Generic page template
├── singular.php                     ← Single post/CPT fallback
├── archive.php                      ← Archive listing
├── header.php                       ← Site header + Walker nav
├── footer.php                       ← Copyright footer
│
├── inc/
│   ├── theme-setup.php              ← Theme supports, image sizes, nav menus
│   ├── enqueue.php                  ← Scripts and styles
│   ├── walker-nav-menu.php          ← Custom Walker extending Walker_Nav_Menu
│   ├── meta-boxes.php               ← All meta boxes (tabbed UI + repeaters)
│   ├── template-functions.php       ← Helper functions for templates
│   └── performance.php              ← Remove unused WP scripts/styles
│
├── template-parts/
│   ├── home/
│   │   ├── hero.php                 ← Hero banner + intro text
│   │   ├── usp.php                  ← USP blocks (repeater)
│   │   ├── benefits.php             ← Alumni benefits (repeater)
│   │   ├── content-media.php        ← Content + image section
│   │   ├── voices.php               ← Alumni videos (repeater)
│   │   ├── testimonials.php         ← Testimonials slider (repeater)
│   │   ├── inspire.php              ← Inspire CTA section
│   │   ├── bottom-blocks.php        ← Bottom CTA blocks (repeater)
│   │   └── events.php               ← Upcoming events from plugin CPT
│   ├── event-meta.php               ← Event details on single event page
│   ├── content-post.php             ← Generic post card
│   └── content-none.php             ← No results found
│
├── scss/
│   ├── _variables.scss              ← Colors, fonts, breakpoints, spacing
│   ├── _mixins.scss                 ← respond-to(), fluid-type(), section-pad()
│   ├── base/
│   │   ├── _reset.scss
│   │   └── _typography.scss
│   ├── components/
│   │   ├── _buttons.scss
│   │   └── _cards.scss
│   └── layouts/
│       ├── _header.scss
│       ├── _footer.scss
│       └── _grid.scss
│
└── assets/
    ├── css/                         ← bootstrap, fontawesome, owl, fonts, events
    ├── js/                          ← jquery, bootstrap, owl, slick, york-main, york-admin
    ├── fonts/                       ← Graphik, York Grot (woff2)
    └── images/                      ← All theme images and SVGs
```

---

## Navigation

Navigation is handled by a **Custom Walker** (`York_Walker_Nav_Menu`) that extends `Walker_Nav_Menu`. It adds full ARIA accessibility attributes:

- `aria-haspopup="true"` on dropdown parent items
- `aria-expanded="false"` on dropdown anchors (updated to `true` via JS on hover/focus)
- `role="menubar"` on the nav list
- `role="menu"` on dropdown submenus

The **mobile hamburger toggle** is implemented in **vanilla JS only** — no jQuery dependency — so it works immediately regardless of script load order.

To add menu items: **WP Admin → Appearance → Menus → Primary Navigation**

---

## Home Page — Dynamic Sections

All home page content is controlled via **WP Admin → Pages → Edit Home Page**. A tabbed meta box appears with 8 tabs:

| Tab | Controls |
|---|---|
| 🏠 Hero | Title, caption, CTA button (label + URL), background image, intro paragraphs |
| 📊 USP | Background color, repeater: heading + description + icon per block |
| 🎓 Benefits | Section title, repeater: heading + description per benefit |
| 🖼️ Content | Section title, 2 paragraphs, CTA button, image |
| 🎥 Voices | Section title + description, repeater: title + desc + YouTube URL + thumbnail |
| 💬 Testimonials | Section title, repeater: quote + name + designation + photo |
| ✨ Inspire | Title, 2 paragraphs, CTA button, image |
| 📦 CTA Blocks | Repeater: title + button (label + URL) + icon per block |

### Repeater Fields
All repeating content (USP blocks, benefits, videos, testimonials, CTA blocks) uses a custom repeater UI — add, remove, and reorder items without any plugin.

### Image Fields
All image fields use the **WordPress Media Library** picker — click "Select Image", choose from library, done.

### Link Fields
All CTA buttons use a combined **Link Field** with separate "Button Label" and "URL" inputs grouped together.

---

## Upcoming Events Section

The home page automatically shows upcoming events from the **Event Booking Manager Pro** plugin (if installed and active). It queries the `event` CPT for future dates, sorted ascending. Each card shows date, time, location, available seats, and booking status badge.

---

## SCSS Architecture

The theme uses structured SCSS partials — no single monolithic file. The compiled output is `style.css` in the theme root.

To recompile (requires Sass):
```bash
sass scss/style.scss style.css --style=compressed --source-map
```

CSS custom properties (`--color-blue`, `--font-heading` etc.) are used for runtime theming and can be overridden in a child theme via `:root`.

---

## Performance

`inc/performance.php` removes unused WordPress defaults:

- WordPress emoji scripts (~15 KB saved)
- `wlwmanifest_link` (Windows Live Writer)
- `wp_generator` (hides WP version for security)
- `wp-block-library` CSS (Gutenberg block styles)
- `classic-theme-styles` (WP 6.1+)
- `global-styles` (WP 5.9+)

Non-critical JS files (`york-main.js`, `york-slick`, `york-owl-carousel`) have `defer` attribute added automatically.

---

## Responsive Breakpoints

| Breakpoint | Width |
|---|---|
| Mobile | 375px |
| Tablet | 768px |
| Desktop | 1440px |

Fluid typography and spacing use `clamp()` throughout — values scale smoothly between breakpoints without media query jumps.

---

## Browser Support

All modern browsers. IE not supported.

---

## License

GPL-2.0+
