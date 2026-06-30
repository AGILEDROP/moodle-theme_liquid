# Changelog

All notable changes to the Liquid theme are documented in this file.

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