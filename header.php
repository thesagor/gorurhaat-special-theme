<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$viewport_content = apply_filters( 'hello_elementor_viewport_content', 'width=device-width, initial-scale=1' );
$whatsapp_raw     = get_option( 'whatsapp_contact_number', '+8801780884888' );
$whatsapp_clean   = preg_replace( '/[^0-9]/', '', $whatsapp_raw );
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="<?php echo esc_attr( $viewport_content ); ?>">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php do_action( 'hello_elementor_before_header' ); ?>

<?php
$skip_header = (
	is_singular() && 'elementor_canvas' === get_page_template_slug()
);

if ( ! $skip_header && ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) ) :
?>

<!-- ============================
     TOP BAR
============================= -->
<div class="gh-topbar" role="complementary" aria-label="যোগাযোগ তথ্য">
	<div class="gh-container">
		<div class="gh-topbar-left">
			<a href="tel:<?php echo esc_attr( $whatsapp_raw ); ?>" class="gh-topbar-link">
				<i class="fas fa-phone-alt" aria-hidden="true"></i>
				<span><?php echo esc_html( $whatsapp_raw ); ?></span>
			</a>
			<span class="gh-topbar-sep" aria-hidden="true">|</span>
			<span class="gh-topbar-tagline">
				<i class="fas fa-map-marker-alt" aria-hidden="true"></i>
				বাংলাদেশের বিশ্বস্ত পশু বাজার
			</span>
		</div>
		<div class="gh-topbar-right">
			<?php if ( is_user_logged_in() ) : ?>
				<a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : admin_url() ); ?>" class="gh-topbar-link">
					<i class="fas fa-user-circle" aria-hidden="true"></i> আমার অ্যাকাউন্ট
				</a>
				<a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="gh-topbar-link">লগআউট</a>
			<?php else : ?>
				<a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url() ); ?>" class="gh-topbar-link">
					<i class="fas fa-sign-in-alt" aria-hidden="true"></i> লগইন / রেজিস্ট্রেশন
				</a>
			<?php endif; ?>
		</div>
	</div>
</div>

<!-- ============================
     MAIN HEADER
============================= -->
<header id="gh-header" class="gh-header" role="banner">
	<div class="gh-container">
		<div class="gh-header-inner">

			<!-- Logo -->
			<div class="gh-logo">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					<?php if ( has_custom_logo() ) :
						the_custom_logo();
					else : ?>
						<span class="gh-logo-text"><?php bloginfo( 'name' ); ?></span>
						<?php if ( get_bloginfo( 'description' ) ) : ?>
							<span class="gh-logo-tagline"><?php bloginfo( 'description' ); ?></span>
						<?php endif; ?>
					<?php endif; ?>
				</a>
			</div>

			<!-- Search Bar -->
			<div class="gh-search-wrap">
				<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="gh-search-form">
					<?php
					$cats = function_exists( 'get_terms' ) ? get_terms( [
						'taxonomy'   => 'product_cat',
						'hide_empty' => true,
						'parent'     => 0,
						'number'     => 20,
					] ) : [];
					if ( ! is_wp_error( $cats ) && ! empty( $cats ) ) :
					?>
					<select name="product_cat" class="gh-search-cat" aria-label="ক্যাটাগরি বেছে নিন">
						<option value="">সব ক্যাটাগরি</option>
						<?php foreach ( $cats as $cat ) : ?>
							<option value="<?php echo esc_attr( $cat->slug ); ?>">
								<?php echo esc_html( $cat->name ); ?>
							</option>
						<?php endforeach; ?>
					</select>
					<?php endif; ?>
					<input
						type="search"
						name="s"
						class="gh-search-input"
						placeholder="গরু, ছাগল, দুধ খুঁজুন..."
						value="<?php echo esc_attr( get_search_query() ); ?>"
						aria-label="পণ্য খুঁজুন"
					>
					<input type="hidden" name="post_type" value="product">
					<button type="submit" class="gh-search-btn" aria-label="খোঁজ করুন">
						<i class="fas fa-search" aria-hidden="true"></i>
					</button>
				</form>
			</div>

			<!-- Header Actions -->
			<div class="gh-header-actions">

				<!-- WhatsApp -->
				<a href="https://wa.me/<?php echo esc_attr( $whatsapp_clean ); ?>"
				   target="_blank" rel="noopener noreferrer"
				   class="gh-action-btn gh-wa-btn"
				   aria-label="WhatsApp-এ যোগাযোগ করুন">
					<i class="fab fa-whatsapp" aria-hidden="true"></i>
					<span>WhatsApp</span>
				</a>

				<!-- Account -->
				<a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url() ); ?>"
				   class="gh-action-btn"
				   aria-label="অ্যাকাউন্ট">
					<i class="fas fa-user" aria-hidden="true"></i>
					<span>অ্যাকাউন্ট</span>
				</a>

				<!-- Cart -->
				<?php if ( function_exists( 'WC' ) && WC()->cart ) : ?>
				<button class="gh-action-btn gh-cart-trigger"
				        id="gh-cart-trigger"
				        aria-label="শপিং কার্ট"
				        aria-expanded="false">
					<i class="fas fa-shopping-basket" aria-hidden="true"></i>
					<span class="gh-cart-badge" id="gh-cart-badge">
						<?php echo intval( WC()->cart->get_cart_contents_count() ); ?>
					</span>
					<span>কার্ট</span>
				</button>
				<?php endif; ?>

				<!-- Mobile Toggle -->
				<button class="gh-hamburger"
				        id="gh-hamburger"
				        aria-label="মেনু খুলুন"
				        aria-expanded="false"
				        aria-controls="gh-mobile-menu">
					<span class="gh-bar"></span>
					<span class="gh-bar"></span>
					<span class="gh-bar"></span>
				</button>

			</div>
		</div>
	</div>

	<!-- Primary Navigation -->
	<nav class="gh-nav" id="gh-nav" aria-label="প্রধান নেভিগেশন">
		<div class="gh-container">
			<?php
			wp_nav_menu( [
				'theme_location' => 'gh-primary',
				'container'      => false,
				'menu_class'     => 'gh-nav-menu',
				'fallback_cb'    => 'gh_fallback_menu',
				'walker'         => new GH_Nav_Walker(),
			] );
			?>
		</div>
	</nav>

</header>

<!-- Mobile Overlay -->
<div class="gh-overlay" id="gh-overlay" aria-hidden="true"></div>

<!-- Mobile Menu -->
<aside class="gh-mobile-menu" id="gh-mobile-menu" aria-hidden="true" aria-label="মোবাইল নেভিগেশন">

	<div class="gh-mobile-header">
		<div class="gh-mobile-logo">
			<?php if ( has_custom_logo() ) :
				the_custom_logo();
			else : ?>
				<span><?php bloginfo( 'name' ); ?></span>
			<?php endif; ?>
		</div>
		<button class="gh-mobile-close" id="gh-mobile-close" aria-label="মেনু বন্ধ করুন">
			<i class="fas fa-times" aria-hidden="true"></i>
		</button>
	</div>

	<div class="gh-mobile-search">
		<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<input type="search" name="s"
			       placeholder="পণ্য খুঁজুন..."
			       value="<?php echo esc_attr( get_search_query() ); ?>"
			       aria-label="পণ্য খুঁজুন">
			<input type="hidden" name="post_type" value="product">
			<button type="submit" aria-label="খুঁজুন">
				<i class="fas fa-search" aria-hidden="true"></i>
			</button>
		</form>
	</div>

	<nav class="gh-mobile-nav" aria-label="মোবাইল নেভিগেশন">
		<?php
		wp_nav_menu( [
			'theme_location' => 'gh-primary',
			'container'      => false,
			'menu_class'     => 'gh-mobile-nav-list',
			'fallback_cb'    => 'gh_fallback_mobile_menu',
		] );
		?>
	</nav>

	<div class="gh-mobile-cta">
		<a href="https://wa.me/<?php echo esc_attr( $whatsapp_clean ); ?>"
		   target="_blank" rel="noopener noreferrer"
		   class="gh-mobile-wa-btn">
			<i class="fab fa-whatsapp" aria-hidden="true"></i>
			WhatsApp-এ যোগাযোগ করুন
		</a>
	</div>

</aside>

<?php endif; // end header check ?>
