<?php
/**
 * Plugin Name:       Naro Taxonomy
 * Description:       A custom plugin to register taxonomies and provide AJAX-based filtering for posts.
 * Version:           0.2.20250831.162645
 * Author:            Naro
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Enable WP_DEBUG for this plugin execution
if (!defined('WP_DEBUG')) define('WP_DEBUG', true);
if (!defined('WP_DEBUG_DISPLAY')) define('WP_DEBUG_DISPLAY', true);
ini_set('display_errors', 1);


// Settings page and dynamic taxonomy registration
add_action('admin_menu', function() {
    add_options_page(
        'Naro Taxonomies',
        'Naro Taxonomies',
        'manage_options',
        'naro-taxonomies',
        'naro_taxonomies_settings_page'
    );
});

function naro_taxonomies_settings_page() {
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized');
    }
    
    // Handle form submission
    if (isset($_POST['naro_taxo_save']) && check_admin_referer('naro_taxo_save_taxonomies')) {
        $taxonomies = array();
        if (!empty($_POST['taxonomies']) && is_array($_POST['taxonomies'])) {
            foreach ($_POST['taxonomies'] as $tax) {
                if (empty($tax['label'])) continue;
                $label = sanitize_text_field($tax['label']);
                $name = !empty($tax['name']) ? $tax['name'] : $label;
                // If the last character is `y` then pluralize with `ies` else `s`
                $plural = (substr($label, -1) === 'y') ? substr($label, 0, -1) . 'ies' : $label . 's';
                $label_plural = !empty($tax['label_plural']) ? sanitize_text_field($tax['label_plural']) : $plural  ;
                $label_front = !empty($tax['label_front']) ? sanitize_text_field($tax['label_front']) : $label;
                $taxonomies[] = array(
                    'name' => sanitize_key($name),
                    'label' => $label,
                    'label_plural' => $label_plural,
                    'label_front' => $label_front,
                    'hierarchical' => !empty($tax['hierarchical']) ? true : false,
                );
            }
        }
        update_option('naro_taxo_custom_taxonomies', $taxonomies);
        echo '<div class="updated"><p>Taxonomies saved.</p></div>';
    }
    $taxonomies = get_option('naro_taxo_custom_taxonomies', array());

    // You can add some default taxonomies here :
    // $taxonomies = get_option('naro_taxo_custom_taxonomies', array(
    //     array('name'=>'qualification','label'=>'Qualification','hierarchical'=>true),
    //     array('name'=>'discipline','label'=>'Discipline','hierarchical'=>true),
    //     array('name'=>'modality','label'=>'Modality','hierarchical'=>true),
    // ));

    ?>
    <div class="wrap">
        <h1>Naro Custom Taxonomies</h1>
        <form method="post">
            <?php wp_nonce_field('naro_taxo_save_taxonomies'); ?>
            <table class="form-table" id="naro-taxo-table">
                <thead>
                    <tr>
                        <th>Label</th>
                        <th>Plural Label</th>
                        <th>Form Label</th>
                        <th>Hierarchical</th>
                        <th>Slug</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($taxonomies as $i => $tax) : ?>
                    <tr>
                        <td><input name="taxonomies[<?php echo $i; ?>][label]" value="<?php echo esc_attr($tax['label']); ?>" required></td>
                        <td><input name="taxonomies[<?php echo $i; ?>][label_plural]" value="<?php echo esc_attr($tax['label_plural']); ?>"></td>
                        <td><input name="taxonomies[<?php echo $i; ?>][label_front]" value="<?php echo esc_attr($tax['label_front']); ?>"></td>
                        <td><input type="checkbox" name="taxonomies[<?php echo $i; ?>][hierarchical]" value="1" <?php checked($tax['hierarchical']); ?>></td>
                        <td><input name="taxonomies[<?php echo $i; ?>][name]" value="<?php echo esc_attr($tax['name']); ?>"></td>
                        <td><button class="remove-taxonomy button">Remove</button></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <button id="add-taxonomy" class="button">Add Taxonomy</button>
            <p class="submit"><input type="submit" name="naro_taxo_save" class="button-primary" value="Save Changes"></p>
        </form>
    </div>
    <script>
    document.getElementById('add-taxonomy').addEventListener('click', function(e) {
        e.preventDefault();
        var table = document.getElementById('naro-taxo-table').getElementsByTagName('tbody')[0];
        var idx = table.rows.length;
        row = table.insertRow();
        row.innerHTML = `<td><input name="taxonomies[${idx}][label]" required></td><td><input name="taxonomies[${idx}][label_plural]"></td><td><input name="taxonomies[${idx}][label_front]"></td><td><input type="checkbox" name="taxonomies[${idx}][hierarchical]" value="1"></td><td><input name="taxonomies[${idx}][name]"></td><td><button class="remove-taxonomy button">Remove</button></td>`;
    });
    document.getElementById('naro-taxo-table').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-taxonomy')) {
            e.preventDefault();
            e.target.closest('tr').remove();
        }
    });
    </script>
    <?php
}

// Register taxonomies dynamically
add_action('init', function() {
    $taxonomies = get_option('naro_taxo_custom_taxonomies', array());
    foreach ($taxonomies as $tax) {
        $labels = array(
            'name' => $tax['name'],
            'singular_name' => $tax['label'],
            'search_items' => 'Search ' . $tax['label_plural'],
            'all_items' => 'All ' . $tax['label_plural'],
            'parent_item' => 'Parent ' . $tax['label'],
            'parent_item_colon' => 'Parent ' . $tax['label'] . ':',
            'edit_item' => 'Edit ' . $tax['label'],
            'update_item' => 'Update ' . $tax['label'],
            'add_new_item' => 'Add New ' . $tax['label'],
            'new_item_name' => 'New ' . $tax['label'] . ' Name',
            'menu_name' => $tax['label_plural'],
        );
        $args = array(
            'hierarchical' => !empty($tax['hierarchical']),
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => $tax['name']),
        );
        register_taxonomy($tax['name'], array('post'), $args);
    }
});

/**
 * Generates and returns an HTML form with dropdowns for custom taxonomies.
 *
 * @param array $atts Shortcode attributes.
 * @return string The HTML for the custom search form.
 */
function create_custom_taxonomy_search_form($atts) {
    // Define default attributes
    $atts = shortcode_atts(
        array(
            'result_page_id' => '', // Default empty
        ),
        $atts,
        'custom_search_form'
    );

    $is_redirect_form = !empty($atts['result_page_id']);
    $form_action = $is_redirect_form ? esc_url(get_permalink($atts['result_page_id'])) : '';

    $taxonomies = get_option('naro_taxo_custom_taxonomies', array());
    ob_start();
    ?>
    <form id="custom-taxonomy-search-form" class="custom-taxonomy-search-form" action="<?php echo $form_action; ?>" method="get">
        <input type="hidden" name="s" value="" />
        <?php foreach ($taxonomies as $tax) : ?>
        <div class="form-group">
            <label for="<?php echo esc_attr($tax['name']); ?>"><?php echo esc_html($tax['label_front']); ?></label>
            <select name="<?php echo esc_attr($tax['name']); ?>">
                <option value=""></option>
                <?php
                $terms = get_terms($tax['name'], array('hide_empty' => true));
                if (!is_wp_error($terms)) {
                    foreach ($terms as $term) {
                        echo '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <?php endforeach; ?>
        <button type="submit">Search</button>
    </form>
    <?php if (!$is_redirect_form): ?>
    <div id="Rcustom-search-results">Pouet</div>
    <?php endif; ?>
    <?php
    return ob_get_clean();
}
add_shortcode('custom_search_form', 'create_custom_taxonomy_search_form');

/**
 * Displays the filtered results based on the selected taxonomy terms.
 * 
 * @param array $atts Shortcode attributes.
 */
function display_filtered_results_shortcode($atts) {

    $atts = shortcode_atts(array(
        'loop_item_id' => '',
    ), $atts);

    $taxonomies = get_option('naro_taxo_custom_taxonomies', array());
    $tax_query = array('relation' => 'AND');
    foreach ($taxonomies as $tax) {
        $slug = $tax['name'];
        if (!empty($_GET[$slug])) {
            $tax_query[] = array(
                'taxonomy' => $slug,
                'field'    => 'slug',
                'terms'    => sanitize_text_field($_GET[$slug]),
            );
        }
    }
    $args = array(
        'post_type'      => 'post',
        'tax_query'      => $tax_query,
        'orderby'        => 'title',
        'order'          => 'ASC',
        'posts_per_page' => 30,
    );
    $query = new WP_Query($args);
    ob_start();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            echo do_shortcode('[elementor-template id="' . esc_attr($atts['loop_item_id']) . '"]');
        }
        wp_reset_postdata();
    } else {
        echo 'Aucun résultat trouvé.';
    }
    return ob_get_clean();
}
add_shortcode('custom_search_results', 'display_filtered_results_shortcode');
