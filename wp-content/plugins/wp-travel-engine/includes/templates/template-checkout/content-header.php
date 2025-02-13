<?php
/**
 * @since 6.3.0
 */
?>
<!-- Checkout Header -->
<header class="wpte-checkout__header">
	<div class="wpte-checkout__container">
		<?php
		if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
			$logo = get_custom_logo();
			echo $logo;
		} else {
			?>
			<div class="site-title">
				<a class="wpte-checkout__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"
				   rel="home"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></a>
			</div>
			<?php if ( '' !== get_bloginfo( 'description' ) ) : ?>
				<p class="site-description"><?php echo esc_html( get_bloginfo( 'description', 'display' ) ); ?></p>
			<?php
			endif;
		}
		?>
		<span class="wpte-checkout__secure-payment-logo">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path
					d="M17 10V8C17 5.23858 14.7614 3 12 3C9.23858 3 7 5.23858 7 8V10M12 14.5V16.5M8.8 21H15.2C16.8802 21 17.7202 21 18.362 20.673C18.9265 20.3854 19.3854 19.9265 19.673 19.362C20 18.7202 20 17.8802 20 16.2V14.8C20 13.1198 20 12.2798 19.673 11.638C19.3854 11.0735 18.9265 10.6146 18.362 10.327C17.7202 10 16.8802 10 15.2 10H8.8C7.11984 10 6.27976 10 5.63803 10.327C5.07354 10.6146 4.6146 11.0735 4.32698 11.638C4 12.2798 4 13.1198 4 14.8V16.2C4 17.8802 4 18.7202 4.32698 19.362C4.6146 19.9265 5.07354 20.3854 5.63803 20.673C6.27976 21 7.11984 21 8.8 21Z"
					stroke="#12B76A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
			</svg>
			<span><?php echo esc_html__( 'Secure Payment', 'wp-travel-engine' ); ?></span>
		</span>
	</div>
</header>
