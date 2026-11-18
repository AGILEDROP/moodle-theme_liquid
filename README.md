# Liquid - Moodle Theme

**Liquid** is a sleek, modern, and highly customizable Moodle theme designed to elevate the user experience. With an automatic dark mode and a streamlined front page featuring configurable sliders and FAQ blocks, Liquid offers a polished and sophisticated aesthetic while enhancing Moodle’s core functionalities.

Perfect for organizations and institutions seeking a visually refined and user-friendly online learning environment, Liquid delivers flexibility and innovative features for today’s digital education landscape.

## Key Features

- **Customizable Design:**
  Extensive and intuitive theme settings allow administrators to tailor the look and feel of Moodle to align with institutional branding.

- **Automatic Dark Mode:**
  Effortlessly switch between light and dark themes based on user preferences, with seamless color adjustments for a cohesive experience.

- **Flexible Navigation:**
  Choose between a contemporary sidebar menu or the traditional top menu to optimize site navigation.

- **Dynamic Front Page Slider:**
  Captivate users from the moment they land on your site with an elegant, customizable image slider and engaging captions.

- **Informative FAQ Block:**
  Provide quick access to essential information with an optional front-page FAQ block to enhance user engagement and reduce support queries.

## Theme Settings

Easily accessible via **Site Administration > Appearance > Themes > Liquid (cog wheel)**, the theme provides comprehensive customization options:

1. **Color Settings:**
   Define your custom color scheme for a unified visual identity.

2. **Typography Settings:**
   Select fonts and adjust text sizes to create a visually appealing interface.

3. **Border Settings:**
   Customize border shapes and sizes to fine-tune the design.

4. **Front Page Slider:**
   Enable and configure the front-page slider with custom images and captions.

5. **FAQ Block:**
   Activate and populate the front-page FAQ block with key user information.

6. **Additional Settings:**
   Manage the navigation menu location and customize social icons.

7. **Advanced Options:**
   Access additional settings for advanced customization needs.

## Compatibility

Liquid 1.2.0 supports Moodle 5.0.x, 5.1.x and 5.2.x.

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

## Usage

1. Go to **Site Administration > Appearance > Themes**.
2. Select **Liquid** as your default theme by clicking the "Select theme" button.

## Dependencies

Liquid is built upon and requires the **Boost theme** to function properly.

## Contributing

We welcome contributions to help enhance the Liquid theme and ensure it meets the evolving needs of Moodle users. Here’s how you can contribute:

- **Report Issues:**
  Encountered a bug or have a feature suggestion? [Submit an issue on GitHub](https://github.com/AGILEDROP/moodle-theme_liquid/issues/new).

- **Submit Code:**
  [Fork the repository](https://github.com/AGILEDROP/moodle-theme_liquid/fork), implement your changes, and create a pull request for review.

- **Translate:**
  Help us provide translations by contributing via the [Moodle AMOS translator](https://lang.moodle.org/local/amos/view.php).

## License

Liquid is licensed under the **GPL v3 (GNU General Public License)**, ensuring open and collaborative development.

We hope Liquid helps you create a more engaging and visually stunning Moodle experience. Thank you for choosing Liquid!
