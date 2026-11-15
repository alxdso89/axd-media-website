/**
 * AXD Media front-end enhancements (no dependencies).
 *
 * Our Work: filter the project grid by project type. Buttons come from the
 * [axd_project_filters] shortcode; cards carry "axd_project_type-{slug}".
 */
( function () {
	'use strict';

	document.querySelectorAll( '.axd-filters' ).forEach( function ( bar ) {
		var scope = bar.closest( '.axd-section' ) || document;
		var cards = scope.querySelectorAll( '.axd-projects .wp-block-post' );

		bar.addEventListener( 'click', function ( event ) {
			var button = event.target.closest( '.axd-filter' );
			if ( ! button ) {
				return;
			}
			var filter = button.getAttribute( 'data-filter' );

			bar.querySelectorAll( '.axd-filter' ).forEach( function ( b ) {
				var on = b === button;
				b.classList.toggle( 'is-active', on );
				b.setAttribute( 'aria-pressed', on ? 'true' : 'false' );
			} );

			cards.forEach( function ( card ) {
				var show = filter === 'all' || card.classList.contains( filter );
				card.hidden = ! show;
			} );
		} );
	} );
} )();
