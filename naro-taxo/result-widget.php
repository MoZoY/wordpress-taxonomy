<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Elementor_Result_Widget extends \Elementor\Widget_Base {
	public function get_name() {
		return 'naro_taxo_results';
	}
	public function get_title() {
		return __( 'Naro Taxonomy Results', 'naro-taxo' );
	}
	public function get_icon() {
		return 'eicon-post-list';
	}
	public function get_categories() {
		return [ 'general' ];
	}
	public function get_keywords() {
		return [ 'taxonomy', 'results', 'naro' ];
	}

	protected function register_controls() {
        // Register the content section
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Layout', 'naro-taxo' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,

			]
		);
		$this->add_control(
			'loop_item_id',
			[
				'label' => __( 'Loop Template ID', 'naro-taxo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
			]
		);
        $this->add_control(
			'items_per_page',
			[
				'label' => __( 'Items Per Page', 'naro-taxo' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 10,
				'min' => 1,
			]
		);
        $this->add_control(
            'no_results_text',
            [
                'label' => __( 'No Results Text', 'naro-taxo' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'No results found.', 'naro-taxo' ),
            ]
        );
        $this->add_responsive_control(
            'number_columns',
            [
                'label' => __( 'Number of Columns', 'naro-taxo' ),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'default' => 3,
                'min' => 1,
                'max' => 6,
                'step' => 1,
            ]
        );
        $this->add_responsive_control(
            'spacing',
            [
                'label' => __( 'Spacing', 'naro-taxo' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'unit' => 'px',
                'default' => [
                    'size' => 30,
                    'unit' => 'px',
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
            ]
        );
        $this->end_controls_section();

        // Register the style section
		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Heading', 'naro-taxo' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_responsive_control(
            'margin_heading',
            [
                'label' => __( 'Margin', 'naro-taxo' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'default' => [
                    'unit' => 'px',
					'isLinked' => true,
                ],
                'selectors' => [
					'{{WRAPPER}} h1, {{WRAPPER}} h2, {{WRAPPER}} h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
            ]
        );
        $this->add_responsive_control(
            'padding_heading',
            [
                'label' => __( 'Padding', 'naro-taxo' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'default' => [
                    'unit' => 'px',
					'isLinked' => true,
                ],
                'selectors' => [
					'{{WRAPPER}} h1, {{WRAPPER}} h2, {{WRAPPER}} h3' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
            ]
        );
        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} h1, {{WRAPPER}} h2, {{WRAPPER}} h3',
			]
		);
        $this->add_control(
            'title_color',
            [
                'label' => __( 'Color', 'naro-taxo' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} h1, {{WRAPPER}} h2, {{WRAPPER}} h3' => 'color: {{VALUE}};',
				],
                'global' => [
                    'default' => \Elementor\Core\Kits\Documents\Tabs\Global_Colors::COLOR_PRIMARY,
                ],
            ]
        );
        $this->end_controls_section();

        // Register the see more style section
		$this->start_controls_section(
			'section_more_style',
			[
				'label' => __( 'See More Button', 'naro-taxo' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'more_typography',
				'selector' => '{{WRAPPER}} span a',
			]
		);
        $this->add_control(
            'more_color',
            [
                'label' => __( 'Color', 'naro-taxo' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} span a' => 'color: {{VALUE}};',
				],
                'global' => [
                    'default' => '',
                ],
            ]
        );
        $this->end_controls_section();
	}

	protected function render() {
		// Nonce check for GET requests
		if (isset($_GET['naro_taxo_filter_nonce']) && !wp_verify_nonce($_GET['naro_taxo_filter_nonce'], 'naro_taxo_filter_form')) {
			echo esc_html__('Security check failed.', 'naro-taxo');
			return;
		}
		$settings = $this->get_settings_for_display();
		$loop_item_id = !empty($settings['loop_item_id']) ? $settings['loop_item_id'] : '';
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
			'posts_per_page' => $settings['items_per_page'],
		);
		$query = new WP_Query($args);
		if ($query->have_posts()) {
			$gridStyleColumns = 'grid-template-columns: repeat(' . esc_attr($settings['number_columns']) . ', 1fr);';
			$gridStyleGapColumns = 'grid-column-gap: ' . esc_attr($settings['spacing']['size']) . 'px;';
			$gridStyleGapRows = 'grid-row-gap: ' . esc_attr($settings['spacing']['size']) . 'px;';
			$gridStyle = 'display: grid;' . $gridStyleGapRows . $gridStyleGapColumns . $gridStyleColumns;
			?>
			<div class="result-item" style="<?php echo esc_attr($gridStyle); ?>">
			<?php
			while ($query->have_posts()) {
				$query->the_post();
					if ($loop_item_id) {
						echo do_shortcode('[elementor-template id="' . esc_attr($loop_item_id) . '"]');
					} else {
						the_title('<h3>', '</h3>');
					}
			}
			?>
			</div>
			<?php
			wp_reset_postdata();
		} else {
			echo esc_html($settings['no_results_text']);
		}
	}
}
