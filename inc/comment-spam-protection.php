<?php
/**
 * Advanced Comment Spam Protection
 * 
 * Multi-layered spam protection system for WordPress comments
 * 
 * @package HelloElementorChild
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Comment_Spam_Protection {
    
    /**
     * Spam keywords to block
     */
    private $spam_keywords = array(
        'viagra', 'cialis', 'casino', 'poker', 'lottery', 'forex',
        'crypto', 'bitcoin', 'investment', 'earn money', 'make money fast',
        'click here', 'buy now', 'limited offer', 'act now', 'free money',
        'work from home', 'weight loss', 'diet pills', 'enlargement',
        'replica', 'rolex', 'payday loan', 'credit score', 'seo service',
        'backlinks', 'link building', 'essay writing', 'assignment help'
    );
    
    /**
     * Settings
     */
    private $max_links;
    private $min_time;
    private $max_comments_per_ip;
    private $rate_limit_window;
    private $min_length;
    
    /**
     * Constructor
     */
    public function __construct() {
        // Initialize settings from database
        $this->init_settings();
        
        // Add honeypot field (if enabled)
        if ( get_option( 'spam_protection_enable_honeypot', 1 ) ) {
            add_action( 'comment_form_after_fields', array( $this, 'add_honeypot_field' ) );
            add_action( 'comment_form_logged_in_after', array( $this, 'add_honeypot_field' ) );
        }
        
        // Add time-based validation (if enabled)
        if ( get_option( 'spam_protection_enable_time_check', 1 ) ) {
            add_action( 'comment_form_after_fields', array( $this, 'add_time_validation' ) );
            add_action( 'comment_form_logged_in_after', array( $this, 'add_time_validation' ) );
        }
        
        // Add simple math CAPTCHA (if enabled)
        if ( get_option( 'spam_protection_enable_captcha', 1 ) ) {
            add_action( 'comment_form_after_fields', array( $this, 'add_math_captcha' ) );
            add_action( 'comment_form_logged_in_after', array( $this, 'add_math_captcha' ) );
        }
        
        // Validate comment before posting
        add_filter( 'preprocess_comment', array( $this, 'validate_comment' ), 1 );
        
        // Additional spam checks
        add_filter( 'comment_post_redirect', array( $this, 'check_duplicate_comment' ), 10, 2 );
        
        // Enqueue custom styles for spam protection
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
    }
    
    /**
     * Initialize settings from database
     */
    private function init_settings() {
        $this->max_links = get_option( 'spam_protection_max_links', 2 );
        $this->min_time = get_option( 'spam_protection_min_time', 3 );
        $this->max_comments_per_ip = get_option( 'spam_protection_max_comments', 3 );
        $this->rate_limit_window = get_option( 'spam_protection_time_window', 5 ) * 60; // Convert minutes to seconds
        $this->min_length = get_option( 'spam_protection_min_length', 10 );
        
        // Merge custom keywords
        $custom_keywords_str = get_option( 'spam_protection_custom_keywords', '' );
        if ( ! empty( $custom_keywords_str ) ) {
            $custom_keywords = array_map( 'trim', explode( "\n", $custom_keywords_str ) );
            $this->spam_keywords = array_merge( $this->spam_keywords, $custom_keywords );
        }
    }
    
    /**
     * Add honeypot field (hidden field that bots fill but humans don't)
     */
    public function add_honeypot_field() {
        echo '<p class="comment-form-honeypot" style="display:none !important; visibility:hidden !important; position:absolute !important; left:-9999px !important;">
            <label for="comment_website_url">Leave this field empty</label>
            <input type="text" name="comment_website_url" id="comment_website_url" value="" tabindex="-1" autocomplete="off" />
        </p>';
    }
    
    /**
     * Add time-based validation field
     */
    public function add_time_validation() {
        $timestamp = time();
        $token = wp_hash( $timestamp . 'comment_time_check' );
        
        echo '<input type="hidden" name="comment_timestamp" value="' . esc_attr( $timestamp ) . '" />';
        echo '<input type="hidden" name="comment_token" value="' . esc_attr( $token ) . '" />';
    }
    
    /**
     * Add simple math CAPTCHA
     */
    public function add_math_captcha() {
        // Generate two random numbers
        $num1 = rand( 1, 10 );
        $num2 = rand( 1, 10 );
        $answer = $num1 + $num2;
        
        // Store answer in session
        if ( ! session_id() ) {
            session_start();
        }
        $_SESSION['math_captcha_answer'] = $answer;
        
        echo '<p class="comment-form-math-captcha">
            <label for="math_captcha">' . sprintf( 
                __( 'Anti-Spam Verification: What is %d + %d?', 'hello-elementor-child' ), 
                $num1, 
                $num2 
            ) . ' <span class="required">*</span></label>
            <input type="number" name="math_captcha" id="math_captcha" required="required" />
        </p>';
    }
    
    /**
     * Validate comment before posting
     */
    public function validate_comment( $commentdata ) {
        // Skip validation for logged-in admins
        if ( current_user_can( 'moderate_comments' ) ) {
            return $commentdata;
        }
        
        // 1. Check honeypot field (if enabled)
        if ( get_option( 'spam_protection_enable_honeypot', 1 ) ) {
            if ( ! empty( $_POST['comment_website_url'] ) ) {
                wp_die( 
                    __( 'Spam detected. Your comment was not posted.', 'hello-elementor-child' ),
                    __( 'Spam Protection', 'hello-elementor-child' ),
                    array( 'response' => 403, 'back_link' => true )
                );
            }
        }
        
        // 2. Validate time-based check (if enabled)
        if ( get_option( 'spam_protection_enable_time_check', 1 ) ) {
            if ( isset( $_POST['comment_timestamp'] ) && isset( $_POST['comment_token'] ) ) {
                $timestamp = intval( $_POST['comment_timestamp'] );
                $token = sanitize_text_field( $_POST['comment_token'] );
                $expected_token = wp_hash( $timestamp . 'comment_time_check' );
                
                // Verify token
                if ( $token !== $expected_token ) {
                    wp_die( 
                        __( 'Security check failed. Please try again.', 'hello-elementor-child' ),
                        __( 'Security Error', 'hello-elementor-child' ),
                        array( 'response' => 403, 'back_link' => true )
                    );
                }
                
                // Check if comment was submitted too quickly
                $time_elapsed = time() - $timestamp;
                if ( $time_elapsed < $this->min_time ) {
                    wp_die( 
                        __( 'You are posting too quickly. Please wait a moment and try again.', 'hello-elementor-child' ),
                        __( 'Slow Down', 'hello-elementor-child' ),
                        array( 'response' => 403, 'back_link' => true )
                    );
                }
                
                // Check if timestamp is too old (more than 1 hour)
                if ( $time_elapsed > 3600 ) {
                    wp_die( 
                        __( 'Your session has expired. Please refresh the page and try again.', 'hello-elementor-child' ),
                        __( 'Session Expired', 'hello-elementor-child' ),
                        array( 'response' => 403, 'back_link' => true )
                    );
                }
            }
        }
        
        // 3. Validate math CAPTCHA (if enabled)
        if ( get_option( 'spam_protection_enable_captcha', 1 ) ) {
            if ( ! session_id() ) {
                session_start();
            }
            
            if ( ! isset( $_POST['math_captcha'] ) || ! isset( $_SESSION['math_captcha_answer'] ) ) {
                wp_die( 
                    __( 'Please answer the anti-spam math question.', 'hello-elementor-child' ),
                    __( 'Verification Required', 'hello-elementor-child' ),
                    array( 'response' => 403, 'back_link' => true )
                );
            }
            
            $user_answer = intval( $_POST['math_captcha'] );
            $correct_answer = intval( $_SESSION['math_captcha_answer'] );
            
            if ( $user_answer !== $correct_answer ) {
                wp_die( 
                    __( 'Incorrect answer to the math question. Please try again.', 'hello-elementor-child' ),
                    __( 'Verification Failed', 'hello-elementor-child' ),
                    array( 'response' => 403, 'back_link' => true )
                );
            }
            
            // Clear the session variable
            unset( $_SESSION['math_captcha_answer'] );
        }
        
        // 4. Check for spam keywords
        $comment_content = strtolower( $commentdata['comment_content'] );
        foreach ( $this->spam_keywords as $keyword ) {
            if ( ! empty( $keyword ) && strpos( $comment_content, strtolower( $keyword ) ) !== false ) {
                wp_die( 
                    __( 'Your comment contains prohibited content and cannot be posted.', 'hello-elementor-child' ),
                    __( 'Spam Detected', 'hello-elementor-child' ),
                    array( 'response' => 403, 'back_link' => true )
                );
            }
        }
        
        // 5. Check link count
        $link_count = substr_count( $commentdata['comment_content'], 'http' );
        if ( $link_count > $this->max_links ) {
            wp_die( 
                sprintf( 
                    __( 'Your comment contains too many links (maximum %d allowed). Please remove some links and try again.', 'hello-elementor-child' ),
                    $this->max_links
                ),
                __( 'Too Many Links', 'hello-elementor-child' ),
                array( 'response' => 403, 'back_link' => true )
            );
        }
        
        // 6. Rate limiting by IP
        $user_ip = $this->get_user_ip();
        $recent_comments = $this->get_recent_comments_by_ip( $user_ip );
        
        if ( count( $recent_comments ) >= $this->max_comments_per_ip ) {
            wp_die( 
                sprintf( 
                    __( 'You are posting comments too frequently. Please wait %d minutes before posting again.', 'hello-elementor-child' ),
                    ceil( $this->rate_limit_window / 60 )
                ),
                __( 'Rate Limit Exceeded', 'hello-elementor-child' ),
                array( 'response' => 429, 'back_link' => true )
            );
        }
        
        // 7. Check for very short comments (likely spam)
        if ( strlen( trim( $commentdata['comment_content'] ) ) < $this->min_length ) {
            wp_die( 
                sprintf( 
                    __( 'Your comment is too short. Please write at least %d characters.', 'hello-elementor-child' ),
                    $this->min_length
                ),
                __( 'Comment Too Short', 'hello-elementor-child' ),
                array( 'response' => 403, 'back_link' => true )
            );
        }
        
        // 8. Check for all caps (common spam pattern)
        $comment_text = trim( $commentdata['comment_content'] );
        if ( strlen( $comment_text ) > 20 && $comment_text === strtoupper( $comment_text ) ) {
            wp_die( 
                __( 'Please do not write your comment in all capital letters.', 'hello-elementor-child' ),
                __( 'Invalid Format', 'hello-elementor-child' ),
                array( 'response' => 403, 'back_link' => true )
            );
        }
        
        return $commentdata;
    }
    
    /**
     * Check for duplicate comments
     */
    public function check_duplicate_comment( $location, $comment ) {
        // Get the comment object
        $comment_obj = get_comment( $comment->comment_ID );
        
        // Check for duplicate comments in the last hour
        $duplicate_check = get_comments( array(
            'author_email' => $comment_obj->comment_author_email,
            'post_id' => $comment_obj->comment_post_ID,
            'date_query' => array(
                array(
                    'after' => '1 hour ago'
                )
            ),
            'count' => true
        ) );
        
        // If more than 1 comment (including the current one), it's a duplicate attempt
        if ( $duplicate_check > 1 ) {
            // Get the previous comment
            $previous_comments = get_comments( array(
                'author_email' => $comment_obj->comment_author_email,
                'post_id' => $comment_obj->comment_post_ID,
                'date_query' => array(
                    array(
                        'after' => '1 hour ago'
                    )
                ),
                'number' => 2,
                'orderby' => 'comment_date',
                'order' => 'DESC'
            ) );
            
            // Check if content is similar
            if ( count( $previous_comments ) >= 2 ) {
                $similarity = similar_text( 
                    $previous_comments[0]->comment_content, 
                    $previous_comments[1]->comment_content,
                    $percent
                );
                
                if ( $percent > 80 ) {
                    // Delete the duplicate comment
                    wp_delete_comment( $comment->comment_ID, true );
                    
                    wp_die( 
                        __( 'Duplicate comment detected. You have already posted this comment.', 'hello-elementor-child' ),
                        __( 'Duplicate Comment', 'hello-elementor-child' ),
                        array( 'response' => 409, 'back_link' => true )
                    );
                }
            }
        }
        
        return $location;
    }
    
    /**
     * Get user IP address (Cloudflare compatible)
     */
    private function get_user_ip() {
        $ip = '';
        
        // Check for Cloudflare IP first (Most reliable if using Cloudflare)
        if ( isset( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } 
        // Check for forwarded for (Proxy)
        elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            // X-Forwarded-For can be a comma separated list of IPs
            $ip_list = explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] );
            $ip = trim( $ip_list[0] );
        } 
        // Standard IP
        elseif ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } 
        // Direct IP
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        // Validate IP format
        return filter_var( $ip, FILTER_VALIDATE_IP ) ? $ip : '0.0.0.0';
    }
    
    /**
     * Get recent comments by IP address
     */
    private function get_recent_comments_by_ip( $ip ) {
        global $wpdb;
        
        $time_threshold = time() - $this->rate_limit_window;
        $date_threshold = date( 'Y-m-d H:i:s', $time_threshold );
        
        $comments = $wpdb->get_results( $wpdb->prepare(
            "SELECT comment_ID FROM $wpdb->comments 
            WHERE comment_author_IP = %s 
            AND comment_date_gmt >= %s
            AND comment_approved != 'spam'",
            $ip,
            $date_threshold
        ) );
        
        return $comments;
    }
    
    /**
     * Enqueue custom styles
     */
    public function enqueue_styles() {
        if ( is_singular() && comments_open() ) {
            wp_add_inline_style( 'hello-elementor-child-style', '
                .comment-form-math-captcha {
                    margin-bottom: 1rem;
                    padding: 10px;
                    background: #f9f9f9;
                    border-radius: 4px;
                    border-left: 3px solid #0073aa;
                }
                
                .comment-form-math-captcha label {
                    display: block;
                    margin-bottom: 0.5rem;
                    font-weight: 600;
                    color: #333;
                }
                
                .comment-form-math-captcha input[type="number"] {
                    width: 100px;
                    padding: 0.5rem;
                    border: 1px solid #ddd;
                    border-radius: 4px;
                    font-size: 1rem;
                    transition: border-color 0.3s ease;
                }
                
                .comment-form-math-captcha input[type="number"]:focus {
                    outline: none;
                    border-color: #0073aa;
                    box-shadow: 0 0 0 1px #0073aa;
                }
                
                .comment-form-math-captcha .required {
                    color: #d63638;
                }
                
                /* Ensure honeypot is completely hidden */
                .comment-form-honeypot {
                    display: none !important;
                    visibility: hidden !important;
                    position: absolute !important;
                    left: -9999px !important;
                    opacity: 0 !important;
                    height: 0 !important;
                    width: 0 !important;
                    z-index: -1 !important;
                }
            ' );
        }
    }
}

// Initialize the spam protection
new Comment_Spam_Protection();
