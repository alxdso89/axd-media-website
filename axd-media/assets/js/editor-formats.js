/**
 * Editor text formats for AXD headings:
 *  - "Gradient text": the violet-to-purple gradient used on light backgrounds.
 *  - "Accent text": the light lavender used on navy panels.
 * Select text in a heading, then use the toolbar's "More" (chevron) menu.
 */
( function ( wp ) {
	'use strict';

	var el = wp.element.createElement;
	var richText = wp.richText;
	var RichTextToolbarButton = wp.blockEditor.RichTextToolbarButton;

	function register( name, title, className, icon ) {
		richText.registerFormatType( name, {
			title: title,
			tagName: 'span',
			className: className,
			edit: function ( props ) {
				return el( RichTextToolbarButton, {
					icon: icon,
					title: title,
					isActive: props.isActive,
					onClick: function () {
						props.onChange( richText.toggleFormat( props.value, { type: name } ) );
					},
				} );
			},
		} );
	}

	register( 'axd/gradient-text', 'Gradient text', 'axd-grad', 'admin-customizer' );
	register( 'axd/accent-text', 'Accent text (on navy)', 'axd-accent', 'art' );
} )( window.wp );
