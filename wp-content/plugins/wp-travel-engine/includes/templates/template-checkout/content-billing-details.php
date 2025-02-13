<?php
/**
 * @var WPTravelEngine\Builders\FormFields\BillingFormFields $billing_form_fields
 * @var bool $show_title
 * @since 6.3.0
 */
?>
<!-- Billing Details Form -->
<div class="wpte-checkout__box collapsible <?php echo esc_attr( $show_title ? 'open' : '' ); ?>">
	<?php if ( $show_title ) : ?>
		<h3 class="wpte-checkout__box-title">
			<?php echo esc_html( apply_filters( 'wpte_billings_details_title', esc_html__( 'Billing Details', 'wp-travel-engine' ) ) ); ?>
			<button type="button" class="wpte-checkout__box-toggle-button">
				<svg>
					<use xlink:href="#chevron-down"></use>
				</svg>
			</button>
		</h3>
	<?php endif; ?>
	<div class="wpte-checkout__box-content">
		<?php $billing_form_fields->render(); ?>
	</div>
</div>
