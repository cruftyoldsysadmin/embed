/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Customizer preview changes asynchronously.
 *
 * https://developer.wordpress.org/themes/customize-api/tools-for-improved-user-experience/#using-postmessage-for-improved-setting-previewing
 */

(function ( $ ) {
	//
	// Typography
	//
	wp.customize( 'global_typo_body', function ( value ) {
		value.bind( function ( to ) {
			SPECIALTY_PREVIEW_SCRIPTS.createTypographyStyles(
				'global_typo_body',
				'body',
				to,
			);
		} );
	} );

	wp.customize( 'global_typo_h1', function ( value ) {
		value.bind( function ( to ) {
			SPECIALTY_PREVIEW_SCRIPTS.createTypographyStyles(
				'global_typo_h1',
				'h1, .entry-title',
				to,
			);
		} );
	} );

	wp.customize( 'global_typo_h2', function ( value ) {
		value.bind( function ( to ) {
			SPECIALTY_PREVIEW_SCRIPTS.createTypographyStyles(
				'global_typo_h2',
				'h2',
				to,
			);
		} );
	} );

	wp.customize( 'global_typo_h3', function ( value ) {
		value.bind( function ( to ) {
			SPECIALTY_PREVIEW_SCRIPTS.createTypographyStyles(
				'global_typo_h3',
				'h3',
				to,
			);
		} );
	} );

	wp.customize( 'global_typo_h4', function ( value ) {
		value.bind( function ( to ) {
			SPECIALTY_PREVIEW_SCRIPTS.createTypographyStyles(
				'global_typo_h4',
				'h4',
				to,
			);
		} );
	} );

	wp.customize( 'global_typo_h5', function ( value ) {
		value.bind( function ( to ) {
			SPECIALTY_PREVIEW_SCRIPTS.createTypographyStyles(
				'global_typo_h5',
				'h5',
				to,
			);
		} );
	} );

	wp.customize( 'global_typo_h6', function ( value ) {
		value.bind( function ( to ) {
			SPECIALTY_PREVIEW_SCRIPTS.createTypographyStyles(
				'global_typo_h6',
				'h6',
				to,
			);
		} );
	} );

	wp.customize( 'global_typo_form_labels', function ( value ) {
		value.bind( function ( to ) {
			SPECIALTY_PREVIEW_SCRIPTS.createTypographyStyles(
				'global_typo_form_labels',
				'form label, form .label',
				to,
			);
		} );
	} );

	wp.customize( 'global_typo_form_text', function ( value ) {
		value.bind( function ( to ) {
			SPECIALTY_PREVIEW_SCRIPTS.createTypographyStyles(
				'global_typo_form_text',
				'input, textarea, select',
				to,
			);
		} );
	} );

	wp.customize( 'global_typo_buttons', function ( value ) {
		value.bind( function ( to ) {
			SPECIALTY_PREVIEW_SCRIPTS.createTypographyStyles(
				'global_typo_buttons',
				'.btn,.button,button[type="submit"],input[type="submit"],input[type="reset"],input[type="button"],button,.comment-reply-link',
				to,
			);
		} );
	} );

	wp.customize( 'global_typo_widget_titles', function ( value ) {
		value.bind( function ( to ) {
			SPECIALTY_PREVIEW_SCRIPTS.createTypographyStyles(
				'global_typo_widget_titles',
				'.widget-title',
				to,
			);
		} );
	} );

	wp.customize( 'global_typo_widget_text', function ( value ) {
		value.bind( function ( to ) {
			SPECIALTY_PREVIEW_SCRIPTS.createTypographyStyles(
				'global_typo_widget_text',
				'.sidebar .widget,.footer .widget,.sidebar .widget_meta li,.sidebar .widget_pages li,.sidebar .widget_categories li,.sidebar .widget_archive li,.sidebar .widget_nav_menu li,.sidebar .widget_recent_entries li,.footer .widget_meta li,.footer .widget_pages li,.footer .widget_categories li,.footer .widget_archive li,.footer .widget_nav_menu li,.footer .widget_recent_entries li',
				to,
			);
		} );
	} );

})( jQuery );
