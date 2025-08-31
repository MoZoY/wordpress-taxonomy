<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Elementor_Filter_Widget extends \Elementor\Widget_Base {
	public function get_name() {
		return 'naro_taxo_filter';
	}
	public function get_title() {
		return __( 'Naro Taxonomy Filter', 'naro-taxo' );
	}
	public function get_icon() {
		return 'eicon-filter';
	}
	public function get_categories() {
		return [ 'general' ];
	}
	public function get_keywords() {
		return [ 'taxonomy', 'filter', 'naro' ];
	}

    protected function register_controls() {
        // Register the Layout section
		$this->start_controls_section(
            'section_layout',
            [
                'label' => __( 'Layout', 'naro-taxo' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
			'result_page_id',
			[
				'label' => __( 'Result Page ID', 'naro-taxo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
			]
		);
        $this->add_control(
			'show_label',
			[
				'label' => __( 'Show Label', 'naro-taxo' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);
        $this->end_controls_section();

        // Register the label style section
        $this->start_controls_section(
            'section_style_label',
            [
                'label' => __( 'Label', 'naro-taxo' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_label' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'spacing_label',
            [
                'label' => __( 'Spacing', 'naro-taxo' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'size' => 15,
                    'unit' => 'px',
                ],
                'selectors' => [
					'{{WRAPPER}} label' => 'margin-bottom: {{SIZE}}{{UNIT}};'
				],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} label',
                'condition' => [
                    'show_label' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'label_color',
            [
                'label' => __( 'Color', 'naro-taxo' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} label' => 'color: {{VALUE}};',
                ],
                'global' => [
                    'default' => '',
                ],
                'condition' => [
                    'show_label' => 'yes',
                ],
            ]
        );
        $this->end_controls_section();

        // Register the dropdown style section
        $this->start_controls_section(
            'section_style_dropdown',
            [
                'label' => __( 'Dropdown', 'naro-taxo' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'dropdown_typography',
                'selector' => '{{WRAPPER}} select',
            ]
        );
        $this->add_control(
            'dropdown_color',
            [
                'label' => __( 'Color', 'naro-taxo' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} select' => 'color: {{VALUE}};',
                ],
                'global' => [
                    'default' => '',
                ],
            ]
        );
        $this->add_responsive_control(
            'spacing_dropdown',
            [
                'label' => __( 'Spacing', 'naro-taxo' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'size' => 30,
                    'unit' => 'px',
                ],
                'selectors' => [
					'{{WRAPPER}} .form-group' => 'margin-bottom: {{SIZE}}{{UNIT}};'
				],
            ]
        );
        $this->add_responsive_control(
            'padding_dropdown',
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
					'{{WRAPPER}} select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
            ]
        );
        $this->add_responsive_control(
            'radius_dropdown',
            [
                'label' => __( 'Border Radius', 'naro-taxo' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'unit' => 'px',
					'isLinked' => true,
                ],
                'selectors' => [
					'{{WRAPPER}} select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
            ]
        );
        $this->end_controls_section();

        // Register the button style section
        $this->start_controls_section(
            'section_style_button',
            [
                'label' => __( 'Button', 'naro-taxo' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'button_size',
            [
                'label' => __( 'Size', 'naro-taxo' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'unit' => '%',
                ],
                'range' => [
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} button' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'spacing_button',
            [
                'label' => __( 'Spacing', 'naro-taxo' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
					'{{WRAPPER}} button' => 'margin-top: {{SIZE}}{{UNIT}};'
				],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} button',
            ]
        );
        
        $this->start_controls_tabs(
			'style_tabs'
		);
		$this->start_controls_tab(
			'style_normal_tab',
			[
				'label' => __( 'Normal', 'naro-taxo' ),
			]
		);
        $this->add_control(
            'button_color',
            [
                'label' => __( 'Color', 'naro-taxo' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} button' => 'color: {{VALUE}};',
                ],
                'global' => [
                    'default' => '',
                ],
            ]
        );
        $this->add_control(
            'button_background',
            [
                'label' => __( 'Background Color', 'naro-taxo' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} button' => 'background-color: {{VALUE}};',
                ],
                'global' => [
                    'default' => '',
                ],
            ]
        );
		$this->end_controls_tab();

        $this->start_controls_tab(
			'style_hover_tab',
			[
				'label' => __( 'Hover', 'naro-taxo' ),
			]
		);
        $this->add_control(
            'button_hover_color',
            [
                'label' => __( 'Color', 'naro-taxo' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} button:hover' => 'color: {{VALUE}};',
                ],
                'global' => [
                    'default' => '',
                ],
            ]
        );
        $this->add_control(
            'button_hover_background',
            [
                'label' => __( 'Background Color', 'naro-taxo' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} button:hover' => 'background-color: {{VALUE}};',
                ],
                'global' => [
                    'default' => '',
                ],
            ]
        );
		$this->end_controls_tab();
		$this->end_controls_tabs();
        
        $this->add_responsive_control(
            'padding_button',
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
					'{{WRAPPER}} button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} button',
            ]
        );
        $this->add_responsive_control(
            'radius_button',
            [
                'label' => __( 'Border Radius', 'naro-taxo' ),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'unit' => 'px',
					'isLinked' => true,
                ],
                'selectors' => [
					'{{WRAPPER}} button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
            ]
        );
        $this->end_controls_section();
    }

	protected function render() {
		// Use the same logic as the shortcode
        $results_page_id = $this->get_settings_for_display('result_page_id');
        $is_redirect_form = !empty($results_page_id);
        $form_action = $is_redirect_form ? esc_url(get_permalink($results_page_id)) : '';

		$taxonomies = get_option('naro_taxo_custom_taxonomies', array());
		echo '<form id="custom-taxonomy-search-form" class="custom-taxonomy-search-form" action="' . esc_url($form_action) . '" method="get">';
		echo '<input type="hidden" name="s" value="" />';
		foreach ($taxonomies as $tax) {
			echo '<div class="form-group">';
            if ($this->get_settings_for_display('show_label') === 'yes') {
                echo '<label for="' . esc_attr($tax['name']) . '">' . esc_html($tax['label_front']) . '</label>';
            }
			echo '<select name="' . esc_attr($tax['name']) . '"><option value=""></option>';
			$terms = get_terms($tax['name'], array('hide_empty' => true));
			if (!is_wp_error($terms)) {
				foreach ($terms as $term) {
					echo '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
				}
			}
			echo '</select></div>';
		}
		echo '<button type="submit">' . esc_html__('Search', 'naro-taxo') . '</button>';
		echo '</form>';
	}
}
