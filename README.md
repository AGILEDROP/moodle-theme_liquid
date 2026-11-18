# Liquid - Moodle Theme

Liquid is a free Moodle theme built on Boost, with light and dark modes,
flexible navigation and a configurable front page.

## Compatibility

| Liquid version | Moodle versions |
| --- | --- |
| 2.0.0 | 5.3 |
| 1.1.1 | 5.0, 5.1, 5.2 |
| 1.0.4 | 4.3, 4.4, 4.5 |

Choose the package for your Moodle version from
[Liquid on Moodle Marketplace](https://marketplace.moodle.com/plugins/theme_liquid/versions).
This branch contains Liquid 2.0.0 for Moodle 5.3.

## Features

- Light and dark modes, with support for the device colour preference.
- Left-side or top navigation.
- Front-page image slider with captions, text styling and a compact banner option.
- Configurable front-page FAQ and social links.
- Settings for colours, typography, borders and custom SCSS.
- Login page styling and colour-mode updates for open TinyMCE editors.

## Requirements

Liquid 2.0.0 requires Moodle 5.3 and its Boost theme
(Moodle and Boost version `2026100500` or later). No additional plugin,
paid subscription or API key is required.

## Installation

### Composer

Once a stable release is available on Packagist, run this command from the root of your Composer-managed Moodle project:

```bash
composer require agiledrop/moodle-theme_liquid
```

### ZIP

Choose a release compatible with your Moodle version. Open **Site administration → Plugins → Install plugins**, upload the plugin ZIP and follow the installation prompts.

### Manual or Git installation

Extract the plugin or check out the matching release from the [GitHub repository](https://github.com/AGILEDROP/moodle-theme_liquid) into `theme/liquid/` under Moodle's web root. If your project uses a `public/` web root, the path from the project root is `public/theme/liquid/`. The directory should contain the plugin's `version.php`.

### Complete the installation

After installing the files with Composer, manually or with Git, open **Site administration → Notifications**. Alternatively, run the following command from the Moodle project root:

```bash
php admin/cli/upgrade.php
```

After installation, select **Liquid** under **Site administration → Appearance → Themes**.

## Configuration

Open **Site administration > Appearance > Themes > Liquid** to configure colours,
typography, borders, the slider, FAQ, navigation, social links and custom SCSS.

## Upgrading

- Back up your site and test the upgrade on a staging copy first.
- Upgrade Moodle to 5.3 and deploy Liquid 2.0.0 as part of the same maintenance
  window. Use Liquid 1.1.x for Moodle 5.0–5.2.
- Replace the old theme directory with the new package, complete Notifications
  and purge caches. Keep the existing database and Moodle data directory.
- Review your theme settings, uploaded images, custom SCSS and navigation.

The upgrade transfers saved Liquid light/dark preferences to Boost without
replacing an existing Boost preference, then removes the old Liquid preference.

## Fonts and Privacy

Inter 4.1 is included locally, with normal and italic styles, under the
[SIL Open Font License 1.1](fonts/inter/LICENSE.txt). By default, the theme makes
no Google Fonts requests.

To use a Google font, enable **Use Google Fonts** in the typography settings,
enter its HTTPS stylesheet URL from `fonts.googleapis.com` in **Google font URL**,
and set **Font family** to match. This sends visitors' IP addresses and browser
request information to Google. Disabling the option stops the theme from loading
the external stylesheet. After upgrading, Google Fonts is disabled; the saved URL
is preserved so you can enable it again. Colour-mode choices use Boost's user
preference and cookie and are reported by Boost's Privacy provider.

## Support and Contributing

- [Report an issue or request a feature](https://github.com/AGILEDROP/moodle-theme_liquid/issues)
- [Source code and contributions](https://github.com/AGILEDROP/moodle-theme_liquid)
- [Translate through Moodle AMOS](https://lang.moodle.org/local/amos/view.php)
- [Changelog](CHANGELOG.md)

## License

GNU GPL v3 or later. See [LICENSE](LICENSE).
