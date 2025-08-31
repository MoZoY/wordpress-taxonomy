<?php
/**
 * Plugin Name:       Naro Taxonomy
 * Description:       A custom plugin to register taxonomies and provide AJAX-based filtering for posts.
 * Version:           0.1.20250831.125724
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

/**
 * Registers the 'qualification' taxonomy for posts.
 *
 * This taxonomy is hierarchical and is used to categorize posts by Qualifications.
 */
function register_qualification_taxonomy() {
    $labels = array(
        'name'              => 'Qualifications',
        'singular_name'     => 'Qualification',
        'search_items'      => 'Search Qualifications',
        'all_items'         => 'All Qualifications',
        'parent_item'       => 'Parent Qualification',
        'parent_item_colon' => 'Parent Qualification:',
        'edit_item'         => 'Edit Qualification',
        'update_item'       => 'Update Qualification',
        'add_new_item'      => 'Add New Qualification',
        'new_item_name'     => 'New Qualification Name',
        'menu_name'         => 'Qualification',
    );
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'qualification' ),
    );
    register_taxonomy( 'qualification', array( 'post' ), $args );
}
add_action( 'init', 'register_qualification_taxonomy' );

function register_discipline_taxonomy() {
    $labels = array(
        'name'              => 'Disciplines',
        'singular_name'     => 'Discipline',
        'search_items'      => 'Search Disciplines',
        'all_items'         => 'All Disciplines',
        'parent_item'       => 'Parent Discipline',
        'parent_item_colon' => 'Parent Discipline:',
        'edit_item'         => 'Edit Discipline',
        'update_item'       => 'Update Discipline',
        'add_new_item'      => 'Add New Discipline',
        'new_item_name'     => 'New Discipline Name',
        'menu_name'         => 'Discipline',
    );
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'discipline' ),
    );
    register_taxonomy( 'discipline', array( 'post' ), $args );
}
add_action( 'init', 'register_discipline_taxonomy' );

/** Registers the 'modality' taxonomy for posts.
 *
 * This taxonomy is hierarchical and is used to categorize posts by Modalities.
 */
function register_modality_taxonomy() {
    $labels = array(
        'name'              => 'Modalities',
        'singular_name'     => 'Modality',
        'search_items'      => 'Search Modalities',
        'all_items'         => 'All Modalities',
        'edit_item'         => 'Edit Modality',
        'update_item'       => 'Update Modality',
        'add_new_item'      => 'Add New Modality',
        'new_item_name'     => 'New Modality Name',
        'menu_name'         => 'Modality',
    );
    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'modality' ),
    );
    register_taxonomy( 'modality', array( 'post' ), $args );
}
add_action( 'init', 'register_modality_taxonomy' );

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

    ob_start(); // Start output buffering
    ?>
    <form id="custom-taxonomy-search-form" class="custom-taxonomy-search-form" action="<?php echo $form_action; ?>" method="get">
        <input type="hidden" name="s" value="" />
        <div class="form-group">
            <label for="qualification">Je suis ...</label>
            <select name="qualification">
                <option value=""></option>
                <?php
                $terms = get_terms('qualification', array('hide_empty' => true));
                foreach ($terms as $term) {
                    echo '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="discipline">Je suis intéressé(e) par ...</label>
            <select name="discipline">
                <option value=""></option>
                <?php
                $terms = get_terms('discipline', array('hide_empty' => true));
                foreach ($terms as $term) {
                    echo '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="modality">Je veux être formé(e) ...</label>
            <select name="modality">
                <option value=""></option>
                <?php
                $terms = get_terms('modality', array('hide_empty' => true));
                foreach ($terms as $term) {
                    echo '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
                }
                ?>
            </select>
        </div>
        <button type="submit">Search</button>
    </form>
    <?php if (!$is_redirect_form): ?>
    <div id="Rcustom-search-results">Pouet</div>
    <?php endif; ?>
    <?php
    return ob_get_clean(); // Return the buffered content
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

    $tax_query = array('relation' => 'AND');

    if (!empty($_GET['qualification'])) {
        $tax_query[] = array(
            'taxonomy' => 'qualification',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($_GET['qualification']),
        );
    }
    if (!empty($_GET['discipline'])) {
        $tax_query[] = array(
            'taxonomy' => 'discipline',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($_GET['discipline']),
        );
    }
    if (!empty($_GET['modality'])) {
        $tax_query[] = array(
            'taxonomy' => 'modality',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($_GET['modality']),
        );
    }

    $args = array(
        'post_type'      => 'post',
        'tax_query'      => $tax_query,
        'orderby'        => 'title',
        'order'          => 'ASC',
        'posts_per_page' => 30, // Or your desired limit
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
