<?php
/**
 * Spam Protection Admin Settings
 * 
 * Admin interface for configuring comment spam protection
 * 
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Spam_Protection_Admin {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_options_page(
            __( 'Spam Protection Settings', 'hello-elementor-child' ),
            __( 'Spam Protection', 'hello-elementor-child' ),
            'manage_options',
            'spam-protection-settings',
            array( $this, 'settings_page' )
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        // General settings
        register_setting( 'spam_protection_settings', 'spam_protection_max_links' );
        register_setting( 'spam_protection_settings', 'spam_protection_min_time' );
        register_setting( 'spam_protection_settings', 'spam_protection_max_comments' );
        register_setting( 'spam_protection_settings', 'spam_protection_time_window' );
        register_setting( 'spam_protection_settings', 'spam_protection_min_length' );
        register_setting( 'spam_protection_settings', 'spam_protection_custom_keywords' );
        register_setting( 'spam_protection_settings', 'spam_protection_enable_captcha' );
        register_setting( 'spam_protection_settings', 'spam_protection_enable_honeypot' );
        register_setting( 'spam_protection_settings', 'spam_protection_enable_time_check' );
        
        // Settings section
        add_settings_section(
            'spam_protection_main',
            __( 'Spam Protection Configuration', 'hello-elementor-child' ),
            array( $this, 'section_callback' ),
            'spam-protection-settings'
        );
        
        // Enable/Disable features
        add_settings_field(
            'spam_protection_enable_captcha',
            __( 'Enable Math CAPTCHA', 'hello-elementor-child' ),
            array( $this, 'checkbox_field' ),
            'spam-protection-settings',
            'spam_protection_main',
            array( 'name' => 'spam_protection_enable_captcha', 'default' => 1 )
        );
        
        add_settings_field(
            'spam_protection_enable_honeypot',
            __( 'Enable Honeypot Field', 'hello-elementor-child' ),
            array( $this, 'checkbox_field' ),
            'spam-protection-settings',
            'spam_protection_main',
            array( 'name' => 'spam_protection_enable_honeypot', 'default' => 1 )
        );
        
        add_settings_field(
            'spam_protection_enable_time_check',
            __( 'Enable Time-Based Validation', 'hello-elementor-child' ),
            array( $this, 'checkbox_field' ),
            'spam-protection-settings',
            'spam_protection_main',
            array( 'name' => 'spam_protection_enable_time_check', 'default' => 1 )
        );
        
        // Numeric settings
        add_settings_field(
            'spam_protection_max_links',
            __( 'Maximum Links Allowed', 'hello-elementor-child' ),
            array( $this, 'number_field' ),
            'spam-protection-settings',
            'spam_protection_main',
            array( 'name' => 'spam_protection_max_links', 'default' => 2, 'min' => 0, 'max' => 10 )
        );
        
        add_settings_field(
            'spam_protection_min_time',
            __( 'Minimum Time Before Submit (seconds)', 'hello-elementor-child' ),
            array( $this, 'number_field' ),
            'spam-protection-settings',
            'spam_protection_main',
            array( 'name' => 'spam_protection_min_time', 'default' => 3, 'min' => 0, 'max' => 30 )
        );
        
        add_settings_field(
            'spam_protection_max_comments',
            __( 'Max Comments Per IP', 'hello-elementor-child' ),
            array( $this, 'number_field' ),
            'spam-protection-settings',
            'spam_protection_main',
            array( 'name' => 'spam_protection_max_comments', 'default' => 3, 'min' => 1, 'max' => 20 )
        );
        
        add_settings_field(
            'spam_protection_time_window',
            __( 'Rate Limit Time Window (minutes)', 'hello-elementor-child' ),
            array( $this, 'number_field' ),
            'spam-protection-settings',
            'spam_protection_main',
            array( 'name' => 'spam_protection_time_window', 'default' => 5, 'min' => 1, 'max' => 60 )
        );
        
        add_settings_field(
            'spam_protection_min_length',
            __( 'Minimum Comment Length (characters)', 'hello-elementor-child' ),
            array( $this, 'number_field' ),
            'spam-protection-settings',
            'spam_protection_main',
            array( 'name' => 'spam_protection_min_length', 'default' => 10, 'min' => 1, 'max' => 100 )
        );
        
        add_settings_field(
            'spam_protection_custom_keywords',
            __( 'Custom Spam Keywords', 'hello-elementor-child' ),
            array( $this, 'textarea_field' ),
            'spam-protection-settings',
            'spam_protection_main',
            array( 'name' => 'spam_protection_custom_keywords' )
        );
    }
    
    /**
     * Section callback
     */
    public function section_callback() {
        echo '<p>' . __( 'Configure the spam protection settings for your comment system. All changes take effect immediately.', 'hello-elementor-child' ) . '</p>';
    }
    
    /**
     * Checkbox field
     */
    public function checkbox_field( $args ) {
        $name = $args['name'];
        $default = isset( $args['default'] ) ? $args['default'] : 0;
        $value = get_option( $name, $default );
        
        echo '<label class="spam-protection-toggle">';
        echo '<input type="checkbox" name="' . esc_attr( $name ) . '" value="1" ' . checked( 1, $value, false ) . ' />';
        echo '<span class="slider"></span>';
        echo '</label>';
    }
    
    /**
     * Number field
     */
    public function number_field( $args ) {
        $name = $args['name'];
        $default = isset( $args['default'] ) ? $args['default'] : 0;
        $min = isset( $args['min'] ) ? $args['min'] : 0;
        $max = isset( $args['max'] ) ? $args['max'] : 100;
        $value = get_option( $name, $default );
        
        echo '<input type="number" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" min="' . esc_attr( $min ) . '" max="' . esc_attr( $max ) . '" class="small-text" />';
    }
    
    /**
     * Textarea field
     */
    public function textarea_field( $args ) {
        $name = $args['name'];
        $value = get_option( $name, '' );
        
        echo '<textarea name="' . esc_attr( $name ) . '" rows="5" cols="50" class="large-text">' . esc_textarea( $value ) . '</textarea>';
        echo '<p class="description">' . __( 'Enter one keyword per line. These will be added to the default spam keyword list.', 'hello-elementor-child' ) . '</p>';
    }
    
    /**
     * Settings page
     */
    public function settings_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        
        // Get spam statistics
        $stats = $this->get_spam_stats();
        
        ?>
        <div class="wrap spam-protection-admin">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            
            <!-- Statistics Dashboard -->
            <div class="spam-stats-dashboard">
                <h2><?php _e( 'Protection Statistics', 'hello-elementor-child' ); ?></h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">💬</div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo esc_html( $stats['total_comments'] ); ?></div>
                            <div class="stat-label"><?php _e( 'Total Comments', 'hello-elementor-child' ); ?></div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">✅</div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo esc_html( $stats['approved_comments'] ); ?></div>
                            <div class="stat-label"><?php _e( 'Approved Comments', 'hello-elementor-child' ); ?></div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">🚫</div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo esc_html( $stats['spam_comments'] ); ?></div>
                            <div class="stat-label"><?php _e( 'Spam Blocked', 'hello-elementor-child' ); ?></div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">📊</div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo esc_html( $stats['spam_percentage'] ); ?>%</div>
                            <div class="stat-label"><?php _e( 'Spam Rate', 'hello-elementor-child' ); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Settings Form -->
            <form method="post" action="options.php" class="spam-protection-form">
                <?php
                settings_fields( 'spam_protection_settings' );
                do_settings_sections( 'spam-protection-settings' );
                submit_button( __( 'Save Settings', 'hello-elementor-child' ) );
                ?>
            </form>
            
            <!-- Help Section -->
            <div class="spam-protection-help">
                <h2><?php _e( 'Protection Features', 'hello-elementor-child' ); ?></h2>
                <div class="help-grid">
                    <div class="help-card">
                        <h3>🍯 <?php _e( 'Honeypot Field', 'hello-elementor-child' ); ?></h3>
                        <p><?php _e( 'Hidden field that traps bots. Invisible to humans, but bots fill it out automatically.', 'hello-elementor-child' ); ?></p>
                    </div>
                    <div class="help-card">
                        <h3>⏱️ <?php _e( 'Time Validation', 'hello-elementor-child' ); ?></h3>
                        <p><?php _e( 'Ensures comments aren\'t submitted instantly. Bots submit forms immediately, humans need a few seconds.', 'hello-elementor-child' ); ?></p>
                    </div>
                    <div class="help-card">
                        <h3>➕ <?php _e( 'Math CAPTCHA', 'hello-elementor-child' ); ?></h3>
                        <p><?php _e( 'Simple math question (e.g., "What is 5 + 3?"). Easy for humans, hard for basic bots.', 'hello-elementor-child' ); ?></p>
                    </div>
                    <div class="help-card">
                        <h3>🔗 <?php _e( 'Link Limiting', 'hello-elementor-child' ); ?></h3>
                        <p><?php _e( 'Restricts the number of links in comments. Spam typically contains many promotional links.', 'hello-elementor-child' ); ?></p>
                    </div>
                    <div class="help-card">
                        <h3>🛑 <?php _e( 'Rate Limiting', 'hello-elementor-child' ); ?></h3>
                        <p><?php _e( 'Limits comments per IP address in a time window. Prevents rapid-fire spam attacks.', 'hello-elementor-child' ); ?></p>
                    </div>
                    <div class="help-card">
                        <h3>📝 <?php _e( 'Content Filtering', 'hello-elementor-child' ); ?></h3>
                        <p><?php _e( 'Blocks spam keywords, all-caps text, and very short comments. Encourages quality discussion.', 'hello-elementor-child' ); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="spam-protection-footer">
                <p>
                    <?php _e( 'For detailed documentation, see', 'hello-elementor-child' ); ?> 
                    <code>SPAM-PROTECTION-GUIDE.md</code>
                </p>
            </div>
        </div>
        <?php
    }
    
    /**
     * Get spam statistics
     */
    private function get_spam_stats() {
        $total_comments = wp_count_comments();
        
        $stats = array(
            'total_comments' => $total_comments->total_comments,
            'approved_comments' => $total_comments->approved,
            'spam_comments' => $total_comments->spam,
            'spam_percentage' => 0
        );
        
        if ( $stats['total_comments'] > 0 ) {
            $stats['spam_percentage'] = round( ( $stats['spam_comments'] / $stats['total_comments'] ) * 100, 1 );
        }
        
        return $stats;
    }
    
    /**
     * Enqueue admin styles
     */
    public function enqueue_admin_styles( $hook ) {
        if ( 'settings_page_spam-protection-settings' !== $hook ) {
            return;
        }
        
        wp_add_inline_style( 'wp-admin', '
            .spam-protection-admin {
                max-width: 1200px;
            }
            
            .spam-stats-dashboard {
                background: #fff;
                padding: 20px;
                margin: 20px 0;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 20px;
                margin-top: 20px;
            }
            
            .stat-card {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 20px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                gap: 15px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            }
            
            .stat-card:nth-child(2) {
                background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            }
            
            .stat-card:nth-child(3) {
                background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            }
            
            .stat-card:nth-child(4) {
                background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            }
            
            .stat-icon {
                font-size: 2.5rem;
            }
            
            .stat-value {
                font-size: 2rem;
                font-weight: bold;
                line-height: 1;
            }
            
            .stat-label {
                font-size: 0.875rem;
                opacity: 0.9;
                margin-top: 5px;
            }
            
            .spam-protection-form {
                background: #fff;
                padding: 20px;
                margin: 20px 0;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            
            .spam-protection-toggle {
                position: relative;
                display: inline-block;
                width: 60px;
                height: 34px;
            }
            
            .spam-protection-toggle input {
                opacity: 0;
                width: 0;
                height: 0;
            }
            
            .spam-protection-toggle .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ccc;
                transition: .4s;
                border-radius: 34px;
            }
            
            .spam-protection-toggle .slider:before {
                position: absolute;
                content: "";
                height: 26px;
                width: 26px;
                left: 4px;
                bottom: 4px;
                background-color: white;
                transition: .4s;
                border-radius: 50%;
            }
            
            .spam-protection-toggle input:checked + .slider {
                background-color: #2196F3;
            }
            
            .spam-protection-toggle input:checked + .slider:before {
                transform: translateX(26px);
            }
            
            .spam-protection-help {
                background: #fff;
                padding: 20px;
                margin: 20px 0;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            
            .help-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 20px;
                margin-top: 20px;
            }
            
            .help-card {
                border: 1px solid #e0e0e0;
                padding: 20px;
                border-radius: 8px;
                transition: transform 0.2s, box-shadow 0.2s;
            }
            
            .help-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            }
            
            .help-card h3 {
                margin-top: 0;
                color: #2271b1;
            }
            
            .spam-protection-footer {
                background: #f0f0f1;
                padding: 15px;
                margin: 20px 0;
                border-radius: 8px;
                text-align: center;
            }
            
            .spam-protection-footer code {
                background: #fff;
                padding: 2px 8px;
                border-radius: 4px;
            }
        ' );
    }
}

// Initialize admin settings
if ( is_admin() ) {
    new Spam_Protection_Admin();
}
