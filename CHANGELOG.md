# Changelog

All notable changes to the Liquid theme are documented in this file.

## 1.2.0

Compatible with Moodle 5.0, 5.1 and 5.2. Moodle 5.3 is supported by Liquid 2.0.

### Fixed

- Notification page: the resource link is only shown when it has a URL.
- Messages: the sender name is readable in dark mode.
- Both drawers open at once no longer overlap the page content.
- Removed outdated overrides of the More menu, course section content, Timeline block and form element
  templates, so core's current markup is used for each Moodle version. Form labels keep their right-aligned
  layout through SCSS.
- Page header supports header extras and uses Bootstrap 5 spacing utilities.
- Front page FAQ opens and closes with Bootstrap 5 `data-bs-*` attributes, skips incomplete questions and
  expands the first one.
- Front page slider skips disabled and empty slides, hides itself when nothing is left, uses the fallback
  image with and without a link, and has no default link.
- Privacy export recognises the stored `light` and `dark` preference values.
- Search button, calendar and other duplicate styles were cleaned up.

### Changed

- Login page: accessible error and information messages, focus handling, password visibility toggle, and
  Bootstrap 5 classes.
- Password recovery page: heading, field layout, mobile presentation and dark mode colours.
- Edit mode and dark mode use Bootstrap 5 switches, with a compact menu on small screens. The duplicate
  switch in the footer was removed.
- Navigation bar, user menu, drawer positioning, stacking, animations and shadows.
- Front page course cards use a responsive grid and link to the full list of courses.
- Site home sections without content are hidden outside editing mode.
- Dashboard grid adapts to the available width.
- Social links are available on the front page, have accessible names and follow the colour mode.
- Updated colour palette, surfaces, borders and shadows.

### Added

- Local Inter fonts. Google Fonts are used only when an administrator enables them, with URL validation and
  privacy metadata.
- Compact banner slider option, and slide text colour and size settings.

## 1.1.0

Compatible with Moodle 5.0, 5.1 and 5.2.

This release adds full Moodle 5.0+ compatibility, a comprehensive dark mode
overhaul across the LMS, custom login and signup page branding, and a new
smart-positioning tooltip system.

### Compatibility

- Ensured compatibility with Moodle 5.0, 5.1 and 5.2 by refactoring the theme
  to account for core API and Bootstrap 5 changes.
- Migrated the `add_htmlattributes` callback to the
  `core\hook\output\before_html_attributes` hook.

### Added

- Custom login page layout with Agiledrop branding, a responsive
  identity-provider grid, and dark mode support.
- Signup and forgot-password page branding with an improved, full-width form
  layout.
- Smart tooltip system with automatic positioning, hover-only triggering,
  keyboard (Escape) dismissal, and dark mode styling.
- Theme settings for primary and secondary button colors.
- Bundled Font Awesome 6.7.2 stylesheet.

### Improved

- Extensive dark mode readability fixes across quizzes, grades, the calendar,
  question UI, the file manager, the TinyMCE toolbar, tables, dropdowns, cards,
  badges, and the navbar.
- Dark mode preference now persists reliably and no longer flickers on page
  load.
- Edit-mode switch restyled to match the theme toggle.
- Navbar spacing, form-control widths, and button alignment refinements.
- Removed excessive empty space on the homepage after a standard installation.

### Fixed

- Fixed dropdown menu overflow.
- Fixed collapse buttons, forgot-password page styling, and a missing
  description string.
- Fixed type inconsistency for the dark mode setting.

### Internal

- Resolved Moodle code checker, PHPDoc, and stylelint issues; sorted language
  strings alphabetically.
- Added privacy metadata, third-party libs declaration, README, and license
  file.
- Added GitHub Actions for Moodle CI.