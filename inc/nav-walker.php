<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class GH_Nav_Walker extends Walker_Nav_Menu {

    public function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) {
        $classes      = empty( $item->classes ) ? [] : (array) $item->classes;
        $has_children = in_array( 'menu-item-has-children', $classes, true );

        $class_str = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        $class_str = $class_str ? ' class="gh-nav-item ' . esc_attr( $class_str ) . '"' : ' class="gh-nav-item"';

        $menu_id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
        $id_attr = $menu_id ? ' id="' . esc_attr( $menu_id ) . '"' : '';

        $output .= '<li' . $id_attr . $class_str . '>';

        $atts           = [];
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target ) ? $item->target : '';
        if ( '_blank' === $item->target && empty( $item->xfn ) ) {
            $atts['rel'] = 'noopener noreferrer';
        } else {
            $atts['rel'] = $item->xfn;
        }
        $atts['href']  = ! empty( $item->url ) ? $item->url : '';
        $atts['class'] = 'gh-nav-link';
        $atts          = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
                $value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $before     = isset( $args->before ) ? $args->before : '';
        $after      = isset( $args->after ) ? $args->after : '';
        $link_before = isset( $args->link_before ) ? $args->link_before : '';
        $link_after  = isset( $args->link_after ) ? $args->link_after : '';
        $title       = apply_filters( 'the_title', $item->title, $item->ID );
        $title       = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

        $item_output  = $before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $link_before . $title . $link_after;
        $item_output .= '</a>';

        if ( $has_children ) {
            $item_output .= '<button class="gh-dropdown-toggle" aria-expanded="false" aria-label="সাবমেনু">';
            $item_output .= '<i class="fas fa-chevron-down" aria-hidden="true"></i>';
            $item_output .= '</button>';
        }

        $item_output .= $after;
        $output      .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

    public function start_lvl( &$output, $depth = 0, $args = [] ) {
        $output .= '<ul class="gh-dropdown sub-menu">';
    }

    public function end_lvl( &$output, $depth = 0, $args = [] ) {
        $output .= '</ul>';
    }
}

function gh_fallback_menu() {
    echo '<ul class="gh-nav-menu">';
    echo '<li class="gh-nav-item"><a class="gh-nav-link" href="' . esc_url( home_url( '/' ) ) . '">হোম</a></li>';
    if ( function_exists( 'wc_get_page_permalink' ) ) {
        echo '<li class="gh-nav-item"><a class="gh-nav-link" href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '">সব পণ্য</a></li>';
    }
    echo '<li class="gh-nav-item"><a class="gh-nav-link" href="' . esc_url( home_url( '/blog' ) ) . '">ব্লগ</a></li>';
    echo '<li class="gh-nav-item"><a class="gh-nav-link" href="' . esc_url( home_url( '/contact' ) ) . '">যোগাযোগ</a></li>';
    echo '</ul>';
}

function gh_fallback_mobile_menu() {
    echo '<ul class="gh-mobile-nav-list">';
    echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">হোম</a></li>';
    if ( function_exists( 'wc_get_page_permalink' ) ) {
        echo '<li><a href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '">সব পণ্য</a></li>';
    }
    echo '<li><a href="' . esc_url( home_url( '/blog' ) ) . '">ব্লগ</a></li>';
    echo '<li><a href="' . esc_url( home_url( '/contact' ) ) . '">যোগাযোগ</a></li>';
    echo '</ul>';
}
