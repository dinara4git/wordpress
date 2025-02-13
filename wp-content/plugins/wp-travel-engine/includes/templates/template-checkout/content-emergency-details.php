<?php
/**
 * @var WPTravelEngine\Builders\FormFields\FormField $emergency_contact_fields
 * @var bool $show_title
 * @since 6.3.0
 */
if ( 'hide' === ( $args['attributes']['travellers'] ?? '' ) ) {
    return;
}

if ( 'hide' === ( $args['attributes']['emergency'] ?? '' ) ) {
    return;
}
?>
<!-- Emergency Details Form -->
<div class="wpte-checkout__box collapsible <?php echo $show_title ? 'open' : ''; ?>">
	<?php if( $show_title ) : ?>
	<h3 class="wpte-checkout__box-title">
		<?php echo __( 'Emergency Details', 'wp-travel-engine' ); ?>
		<button type="button" class="wpte-checkout__box-toggle-button">
			<svg>
				<use xlink:href="#chevron-down"></use>
			</svg>
		</button>
	</h3>
	<?php endif; ?>
	<div class="wpte-checkout__box-content">
		<?php $emergency_contact_fields->render(); ?>
	</div>
</div>
