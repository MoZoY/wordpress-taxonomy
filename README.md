# WordPress Taxonomy & Filter Plugin

This project provides dynamic custom taxonomies to a WordPress site, including a filter form and results display, with Elementor widget integration.

## Structure

- `naro-taxo/`: The main WordPress plugin.
- `build.sh` / `build.ps1`: Scripts to build and package the plugin.
- `release/`: Build artifacts and packaged plugins.

## Installation

1. **Build the Plugin**
   - Run the script to update the version and create a ZIP package:
     ```sh
     sh ./build.sh
     ```
     or
     ```pwsh
     pwsh ./build.ps1
     ```
2. **Install the Plugin**
   - Upload the generated ZIP from `release/` to your WordPress site (Plugins > Add New > Upload Plugin).
   - Activate the plugin.
   - **Requires:** Elementor (free or Pro).

## Configuration

1. Go to **Settings > Naro Taxonomies** in the WordPress admin.
2. Add, edit, or remove custom taxonomies:
   - **Label:** Singular name (e.g., "Discipline").
   - **Plural Label:** Plural name (e.g., "Disciplines").
   - **Form Label:** Label for the filter form (e.g., "Choose a discipline").
   - **Hierarchical:** Check for parent/child support.
   - **Slug:** Unique identifier (used in URLs and queries).
3. Save changes. Taxonomies are registered dynamically and appear in the post editor and filter forms.

## Shortcodes

### Filter Form
```
[custom_search_form result_page_id="123"]
```
- `result_page_id`: (optional) ID of the page to redirect to for results.

### Results Display
```
[custom_search_results loop_item_id="456"]
```
- `loop_item_id`: (optional) Elementor Loop Template ID for rendering each result.

## Elementor Widgets

After activating the plugin, two widgets are available in Elementor:

- **Naro Taxonomy Filter**: Drag to any page to display the filter form. Uses your dynamic taxonomies.
- **Naro Taxonomy Results**: Drag to a results page. Configure the Loop Template ID and display options in the widget panel.

## Security

- All forms include a WordPress nonce for CSRF protection.
- All user input is sanitized and validated before use.

## Troubleshooting

- **Widgets not showing?** Make sure Elementor is installed and activated.
- **Taxonomies not appearing?** Check the settings page and ensure you have saved at least one taxonomy.
- **No results?** Make sure posts are assigned to the relevant taxonomies and the filter form is submitting the correct values.
- **Debugging:** WP_DEBUG is enabled by default in this plugin for development. Disable in production.

## Extending

- You can add or remove taxonomies at any time from the settings page.
- Developers can customize the filter/results logic by editing the plugin files or extending the widgets.

## License

MIT or as specified in individual files.

---

**Changelog**

- 0.2.x: Elementor widget integration, dynamic taxonomies, security improvements.
- 0.1.x: Initial version with shortcode-based filter and results.
