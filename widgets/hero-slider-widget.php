<?php
/**
 * Hero Slider Widget for Elementor
 * 
 * A sophisticated hero slider with background images, text content, and image carousel
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Hello_Elementor_Hero_Slider_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'hero_slider';
    }

    public function get_title() {
        return __('Hero Slider', 'hello-elementor-child');
    }

    public function get_icon() {
        return 'eicon-slider-push';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_keywords() {
        return ['hero', 'slider', 'carousel', 'banner'];
    }

    public function get_style_depends() {
        return ['hero-slider-styles'];
    }

    public function get_script_depends() {
        return ['hero-slider-script'];
    }

    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Slides', 'hello-elementor-child'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'slide_title',
            [
                'label' => __('Title', 'hello-elementor-child'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Slide Title', 'hello-elementor-child'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'slide_description',
            [
                'label' => __('Description', 'hello-elementor-child'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('Slide description goes here...', 'hello-elementor-child'),
                'rows' => 5,
            ]
        );

        $repeater->add_control(
            'slide_image',
            [
                'label' => __('Slide Image', 'hello-elementor-child'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'background_image',
            [
                'label' => __('Background Image', 'hello-elementor-child'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'slide_label',
            [
                'label' => __('Image Label', 'hello-elementor-child'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('LABEL', 'hello-elementor-child'),
            ]
        );

        $repeater->add_control(
            'button_text',
            [
                'label' => __('Button Text', 'hello-elementor-child'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('EXPLORE MORE', 'hello-elementor-child'),
            ]
        );

        $repeater->add_control(
            'button_link',
            [
                'label' => __('Button Link', 'hello-elementor-child'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'hello-elementor-child'),
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $this->add_control(
            'slides',
            [
                'label' => __('Slides', 'hello-elementor-child'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'slide_title' => __('The Art of making Pizza', 'hello-elementor-child'),
                        'slide_description' => __('Explore the rich history and diverse styles of pizzas from around the world.', 'hello-elementor-child'),
                        'slide_label' => __('PIZZA', 'hello-elementor-child'),
                    ],
                    [
                        'slide_title' => __('The Perfect Burger', 'hello-elementor-child'),
                        'slide_description' => __('Dive into the world of burgers, where creativity meets tradition.', 'hello-elementor-child'),
                        'slide_label' => __('BURGER', 'hello-elementor-child'),
                    ],
                ],
                'title_field' => '{{{ slide_title }}}',
            ]
        );

        $this->end_controls_section();

        // Style Section - General
        $this->start_controls_section(
            'style_general',
            [
                'label' => __('General', 'hello-elementor-child'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'slider_height',
            [
                'label' => __('Slider Height', 'hello-elementor-child'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 300,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 50,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'vh',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .hero-slider' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'overlay_opacity',
            [
                'label' => __('Overlay Opacity', 'hello-elementor-child'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'size' => 0.6,
                ],
                'selectors' => [
                    '{{WRAPPER}} .hero-overlay' => 'background: rgba(0, 0, 0, {{SIZE}});',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Typography
        $this->start_controls_section(
            'style_typography',
            [
                'label' => __('Typography', 'hello-elementor-child'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __('Title Typography', 'hello-elementor-child'),
                'selector' => '{{WRAPPER}} .title-item h1',
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'hello-elementor-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .title-item h1' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => __('Description Typography', 'hello-elementor-child'),
                'selector' => '{{WRAPPER}} .desc-item p',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __('Description Color', 'hello-elementor-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .desc-item p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Button
        $this->start_controls_section(
            'style_button',
            [
                'label' => __('Button', 'hello-elementor-child'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label' => __('Background Color', 'hello-elementor-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#eeeeee',
                'selectors' => [
                    '{{WRAPPER}} .button-widget .btn' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __('Text Color', 'hello-elementor-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .button-widget .btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_bg_color',
            [
                'label' => __('Hover Background Color', 'hello-elementor-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .button-widget .btn:hover' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Navigation
        $this->start_controls_section(
            'style_navigation',
            [
                'label' => __('Navigation', 'hello-elementor-child'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'nav_button_color',
            [
                'label' => __('Button Color', 'hello-elementor-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#e74c3c',
                'selectors' => [
                    '{{WRAPPER}} .nav-btn' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nav_button_hover_color',
            [
                'label' => __('Button Hover Color', 'hello-elementor-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#c0392b',
                'selectors' => [
                    '{{WRAPPER}} .nav-btn:hover' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dot_color',
            [
                'label' => __('Dot Color', 'hello-elementor-child'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .dot' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .as-bar::before' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $slides = $settings['slides'];
        
        if (empty($slides)) {
            return;
        }

        $widget_id = $this->get_id();
        
        // Prepare background images array for JavaScript
        $bg_images = array();
        foreach ($slides as $slide) {
            $bg_images[] = $slide['background_image']['url'] ?? $slide['slide_image']['url'];
        }
        ?>
        <section class="hero-slider hero-slider-<?php echo esc_attr($widget_id); ?>">
            <!-- Background Slider -->
            <div class="as-slider-background"></div>

            <!-- Overlay -->
            <div class="hero-overlay"></div>

            <!-- Main Content -->
            <div class="hero-content">
                <!-- Left Section - Text and Navigation -->
                <div class="hero-left">
                    <!-- Navigation Dots -->
                    <div class="as-bar"></div>

                    <!-- Text Content -->
                    <div class="text-container">
                        <div class="changing-widget titles-widget">
                            <?php foreach ($slides as $index => $slide) : ?>
                                <div class="title-item">
                                    <h1><?php echo esc_html($slide['slide_title']); ?></h1>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="changing-widget desc-widget">
                            <?php foreach ($slides as $index => $slide) : ?>
                                <div class="desc-item">
                                    <p><?php echo esc_html($slide['slide_description']); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="changing-widget button-widget">
                            <?php foreach ($slides as $index => $slide) : 
                                $button_link = $slide['button_link']['url'] ?? '#';
                                $is_external = $slide['button_link']['is_external'] ?? false;
                                $nofollow = $slide['button_link']['nofollow'] ?? false;
                            ?>
                                <a href="<?php echo esc_url($button_link); ?>" 
                                   class="btn"
                                   <?php echo $is_external ? 'target="_blank"' : ''; ?>
                                   <?php echo $nofollow ? 'rel="nofollow"' : ''; ?>>
                                    <?php echo esc_html($slide['button_text']); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Right Section - Image Carousel -->
                <div class="hero-right">
                    <div class="swiper food-slider food-slider-<?php echo esc_attr($widget_id); ?>">
                        <div class="swiper-wrapper">
                            <?php foreach ($slides as $index => $slide) : ?>
                                <div class="swiper-slide" data-index="<?php echo esc_attr($index); ?>">
                                    <img src="<?php echo esc_url($slide['slide_image']['url']); ?>" 
                                         alt="<?php echo esc_attr($slide['slide_label']); ?>" />
                                    <span><?php echo esc_html($slide['slide_label']); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="nav-buttons">
                        <button class="nav-btn nav-prev nav-prev-<?php echo esc_attr($widget_id); ?>">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="nav-btn nav-next nav-next-<?php echo esc_attr($widget_id); ?>">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <style>
        /* Force Font Awesome icons to display */
        .hero-slider-<?php echo esc_attr($widget_id); ?> .nav-btn i {
            font-family: "Font Awesome 6 Free", "Font Awesome 5 Free", "FontAwesome" !important;
            font-weight: 900 !important;
            display: inline-block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
        .hero-slider-<?php echo esc_attr($widget_id); ?> .nav-btn i::before {
            display: inline-block !important;
        }
        </style>


        <script>
        jQuery(document).ready(function($) {
            if (typeof window.initHeroSlider === 'function') {
                window.initHeroSlider(
                    '<?php echo esc_js($widget_id); ?>',
                    <?php echo json_encode($bg_images); ?>,
                    <?php echo count($slides); ?>
                );
            }
        });
        </script>
        <?php
    }
}
