# WordPress Taxonomy

This project provides custom taxonomies to a WordPress site, including a filter form and a filter result short-code using an Elementor Loop Item.

## Structure

- `naro-taxo/`: The main WordPress plugin.
- `build.sh`: Shell script to build and package the plugin.
- `build.ps1`: PowerShell script to build and package the plugin.
- `release/`: Build artifacts and packaged plugins.

## Usage

1. **Build the Plugin**  
   Run the script to update the version and create a ZIP package:

   ```sh
   sh ./build.sh
   ```

   ```pwsh
   pwsh ./build.ps1
   ```

2. **Install the Plugin**  
   Upload the generated ZIP from `release/` to your WordPress site or directly from the Plugin installer.

## License

MIT or as specified in individual files.
