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

/**
 * Profile card: "Share This Card" uses the phone's share sheet when available,
 * otherwise copies the page link.
 */
( function () {
	'use strict';

	document.querySelectorAll( '.axd-share .wp-block-button__link' ).forEach( function ( link ) {
		link.addEventListener( 'click', function ( event ) {
			var url = link.href || window.location.href;
			var title = document.title;
			if ( navigator.share ) {
				event.preventDefault();
				navigator.share( { title: title, url: url } ).catch( function () {} );
			} else if ( navigator.clipboard ) {
				event.preventDefault();
				navigator.clipboard.writeText( url ).then( function () {
					var label = link.textContent;
					link.textContent = 'Link copied';
					setTimeout( function () {
						link.textContent = label;
					}, 2000 );
				} );
			}
		} );
	} );
}() );
