document.addEventListener( 'load', fb_responsive_icons );
document.addEventListener( 'DOMContentLoaded', fb_responsive_icons );

import DpResponsiveControls from './DpResponsiveControls';

function fb_responsive_icons() {
	wp.data.subscribe( function () {
		setTimeout( function () {
			fb_responsive_icon();
		}, 500 );
	} );
}

function fb_responsive_icon() {
	if ( !document.querySelector( '.edit-post-header__toolbar' ) ) {
		return null;
	}
	if ( document.querySelector( '.dp-responsive-control' ) ) {
		return null;
	}

	const buttons = document.createElement( 'div' );
	buttons.classList.add( 'dp-responsive-control' );

	document.querySelector( '.edit-post-header__toolbar' ).append( buttons );
	wp.element.render(
		<DpResponsiveControls />,
		document.querySelector( '.dp-responsive-control' )
	);
}

