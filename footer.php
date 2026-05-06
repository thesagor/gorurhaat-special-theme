<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$whatsapp_raw   = get_option( 'whatsapp_contact_number', '+8801780884888' );
$whatsapp_clean = preg_replace( '/[^0-9]/', '', $whatsapp_raw );
$current_year   = gmdate( 'Y' );

$skip_footer = (
	is_singular() && 'elementor_canvas' === get_page_template_slug()
);

if ( ! $skip_footer && ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) ) :
?>

<footer id="gh-footer" class="gh-footer" role="contentinfo">

	<!-- ============================
	     FOOTER TOP
	============================= -->
	<div class="gh-footer-top">
		<div class="gh-container">
			<div class="gh-footer-grid">

				<!-- Col 1: About -->
				<div class="gh-footer-col gh-footer-col--about">
					<div class="gh-footer-brand">
						<?php if ( has_custom_logo() ) :
							the_custom_logo();
						else : ?>
							<span class="gh-footer-logo-text"><?php bloginfo( 'name' ); ?></span>
						<?php endif; ?>
					</div>
					<p class="gh-footer-about">
						<?php echo esc_html(
							get_bloginfo( 'description' ) ?:
							'বাংলাদেশের সেরা অনলাইন পশু বাজার। গরু, ছাগল, ভেড়া ও দুগ্ধজাত পণ্য কিনুন সরাসরি খামার থেকে।'
						); ?>
					</p>
					<div class="gh-footer-social">
						<a href="#" class="gh-social-link" aria-label="Facebook" rel="noopener noreferrer" target="_blank">
							<i class="fab fa-facebook-f" aria-hidden="true"></i>
						</a>
						<a href="#" class="gh-social-link" aria-label="YouTube" rel="noopener noreferrer" target="_blank">
							<i class="fab fa-youtube" aria-hidden="true"></i>
						</a>
						<a href="https://wa.me/<?php echo esc_attr( $whatsapp_clean ); ?>"
						   class="gh-social-link gh-social-wa"
						   aria-label="WhatsApp"
						   rel="noopener noreferrer"
						   target="_blank">
							<i class="fab fa-whatsapp" aria-hidden="true"></i>
						</a>
					</div>
				</div>

				<!-- Col 2: Quick Links -->
				<div class="gh-footer-col">
					<h3 class="gh-footer-heading">দ্রুত লিংক</h3>
					<ul class="gh-footer-links">
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">হোম</a></li>
						<?php if ( function_exists( 'wc_get_page_permalink' ) ) : ?>
							<li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">সব পণ্য</a></li>
							<li><a href="<?php echo esc_url( wc_get_page_permalink( 'cart' ) ); ?>">কার্ট</a></li>
							<li><a href="<?php echo esc_url( wc_get_page_permalink( 'checkout' ) ); ?>">চেকআউট</a></li>
							<li><a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">আমার অ্যাকাউন্ট</a></li>
						<?php endif; ?>
						<li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>">ব্লগ</a></li>
						<li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>">যোগাযোগ</a></li>
					</ul>
				</div>

				<!-- Col 3: Categories -->
				<div class="gh-footer-col">
					<h3 class="gh-footer-heading">পণ্য বিভাগ</h3>
					<ul class="gh-footer-links">
						<?php
						$footer_cats = get_terms( [
							'taxonomy'   => 'product_cat',
							'hide_empty' => true,
							'parent'     => 0,
							'number'     => 7,
						] );
						if ( ! is_wp_error( $footer_cats ) && ! empty( $footer_cats ) ) :
							foreach ( $footer_cats as $cat ) :
						?>
							<li>
								<a href="<?php echo esc_url( get_term_link( $cat ) ); ?>">
									<?php echo esc_html( $cat->name ); ?>
								</a>
							</li>
						<?php endforeach; endif; ?>
					</ul>
				</div>

				<!-- Col 4: Contact -->
				<div class="gh-footer-col">
					<h3 class="gh-footer-heading">যোগাযোগ করুন</h3>
					<ul class="gh-footer-contact-list">
						<li>
							<i class="fab fa-whatsapp" aria-hidden="true"></i>
							<a href="https://wa.me/<?php echo esc_attr( $whatsapp_clean ); ?>"
							   target="_blank" rel="noopener noreferrer">
								<?php echo esc_html( $whatsapp_raw ); ?>
							</a>
						</li>
						<li>
							<i class="fas fa-clock" aria-hidden="true"></i>
							<span>শনি–বৃহস্পতি: সকাল ৯টা – রাত ৯টা</span>
						</li>
						<li>
							<i class="fas fa-map-marker-alt" aria-hidden="true"></i>
							<span>ঢাকা, বাংলাদেশ</span>
						</li>
					</ul>

					<div class="gh-footer-trust">
						<span class="gh-trust-badge">
							<i class="fas fa-shield-alt" aria-hidden="true"></i> নিরাপদ পেমেন্ট
						</span>
						<span class="gh-trust-badge">
							<i class="fas fa-truck" aria-hidden="true"></i> দ্রুত ডেলিভারি
						</span>
						<span class="gh-trust-badge">
							<i class="fas fa-undo" aria-hidden="true"></i> রিটার্ন গ্যারান্টি
						</span>
					</div>
				</div>

			</div>
		</div>
	</div>

	<!-- ============================
	     FOOTER BOTTOM
	============================= -->
	<div class="gh-footer-bottom">
		<div class="gh-container">
			<div class="gh-footer-bottom-inner">
				<p class="gh-copyright">
					&copy; <?php echo esc_html( $current_year ); ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>.
					সর্বস্বত্ব সংরক্ষিত।
				</p>
				<div class="gh-payment-badges">
					<span class="gh-pay-badge">bKash</span>
					<span class="gh-pay-badge">Nagad</span>
					<span class="gh-pay-badge gh-pay-badge--card">
						<i class="fab fa-cc-visa" aria-hidden="true"></i>
					</span>
					<span class="gh-pay-badge gh-pay-badge--card">
						<i class="fab fa-cc-mastercard" aria-hidden="true"></i>
					</span>
				</div>
			</div>
		</div>
	</div>

</footer>

<!-- Back to Top -->
<button class="gh-back-to-top" id="gh-back-to-top" aria-label="উপরে যান">
	<i class="fas fa-chevron-up" aria-hidden="true"></i>
</button>

<?php endif; // end footer check ?>

<?php do_action( 'hello_elementor_after_footer' ); ?>

<?php wp_footer(); ?>
</body>
</html>
