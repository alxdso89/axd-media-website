<?php
/**
 * Pattern building helpers.
 *
 * These functions return serialized core-block markup so the full-page
 * patterns stay short, consistent, and valid in the block editor. Everything
 * they output is ordinary core blocks (group, heading, paragraph, image,
 * buttons, list, columns), so all content stays editable in WordPress.
 *
 * Text passed in is trusted, static pattern copy written in this theme; it may
 * contain simple inline markup such as <span class="axd-grad">.
 *
 * @package AXD_Media
 */

defined( 'ABSPATH' ) || exit;

/* ---------------------------------------------------------------------------
 * Low-level block builders
 * ------------------------------------------------------------------------ */

/**
 * Block comment attributes as JSON (empty string when none).
 *
 * @param array $attrs Attributes.
 * @return string
 */
function axd_b_attrs( $attrs ) {
	$attrs = array_filter(
		$attrs,
		static function ( $v ) {
			return null !== $v && '' !== $v && array() !== $v;
		}
	);
	if ( empty( $attrs ) ) {
		return '';
	}
	return ' ' . wp_json_encode( $attrs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP );
}

/**
 * Class attribute value: "base extra".
 *
 * @param string $base  Base class.
 * @param string $extra Extra classes.
 * @return string
 */
function axd_b_cls( $base, $extra = '' ) {
	return trim( $base . ' ' . $extra );
}

/**
 * Group block.
 *
 * @param string $inner  Inner block markup.
 * @param string $class  Extra classes.
 * @param string $tag    div|section|main.
 * @param string $layout default|constrained.
 * @return string
 */
function axd_b_group( $inner, $class = '', $tag = 'div', $layout = 'default', $anchor = '' ) {
	$attrs = array(
		'tagName'   => 'div' === $tag ? null : $tag,
		'anchor'    => $anchor,
		'className' => $class,
		'layout'    => array( 'type' => $layout ),
	);
	$id = $anchor ? ' id="' . esc_attr( $anchor ) . '"' : '';
	return '<!-- wp:group' . axd_b_attrs( $attrs ) . " -->\n"
		. '<' . $tag . ' class="' . esc_attr( axd_b_cls( 'wp-block-group', $class ) ) . '"' . $id . '>'
		. $inner
		. '</' . $tag . ">\n<!-- /wp:group -->\n\n";
}

/**
 * Paragraph block.
 *
 * @param string $html  Inner HTML.
 * @param string $class Classes.
 * @return string
 */
function axd_b_p( $html, $class = '' ) {
	$open = $class ? '<p class="' . esc_attr( $class ) . '">' : '<p>';
	return '<!-- wp:paragraph' . axd_b_attrs( array( 'className' => $class ) ) . " -->\n"
		. $open . $html . "</p>\n<!-- /wp:paragraph -->\n\n";
}

/**
 * Heading block.
 *
 * @param int    $level 1-6.
 * @param string $html  Inner HTML.
 * @param string $class Extra classes.
 * @return string
 */
function axd_b_h( $level, $html, $class = '' ) {
	$attrs = array(
		'level'     => 2 === $level ? null : $level,
		'className' => $class,
	);
	return '<!-- wp:heading' . axd_b_attrs( $attrs ) . " -->\n"
		. '<h' . $level . ' class="' . esc_attr( axd_b_cls( 'wp-block-heading', $class ) ) . '">' . $html . '</h' . $level . ">\n"
		. "<!-- /wp:heading -->\n\n";
}

/**
 * Image block (theme asset URL).
 *
 * @param string $src   Image URL (already escaped).
 * @param string $alt   Alt text ('' for decorative).
 * @param string $class Extra classes.
 * @return string
 */
function axd_b_img( $src, $alt, $class = '' ) {
	$attrs = array(
		'sizeSlug'        => 'full',
		'linkDestination' => 'none',
		'className'       => $class,
	);
	return '<!-- wp:image' . axd_b_attrs( $attrs ) . " -->\n"
		. '<figure class="' . esc_attr( axd_b_cls( 'wp-block-image size-full', $class ) ) . '"><img src="' . $src . '" alt="' . esc_attr( $alt ) . '"/></figure>'
		. "\n<!-- /wp:image -->\n\n";
}

/**
 * Icon tile (image block).
 *
 * @param string $icon  Icon name.
 * @param string $color blue|purple|white.
 * @param string $size  '' | is-sm | is-xs.
 * @param string $tile  is-blue | is-purple | is-glass.
 * @return string
 */
function axd_b_icon( $icon, $color = 'blue', $size = '', $tile = '' ) {
	if ( '' === $tile ) {
		$tile = 'white' === $color ? 'is-glass' : 'is-' . $color;
	}
	return axd_b_img( axd_icon( $icon, $color ), '', trim( 'axd-ic ' . $tile . ' ' . $size ) );
}

/**
 * Buttons block.
 *
 * @param array  $buttons List of [ text, url, classes ].
 * @param string $class   Wrapper classes.
 * @return string
 */
function axd_b_buttons( $buttons, $class = '' ) {
	$inner = '';
	foreach ( $buttons as $i => $b ) {
		$text  = $b[0];
		$url   = $b[1];
		$bcls  = isset( $b[2] ) ? $b[2] : '';
		$inner .= ( $i ? "\n\n" : '' )
			. '<!-- wp:button' . axd_b_attrs( array( 'className' => $bcls ) ) . " -->\n"
			. '<div class="' . esc_attr( axd_b_cls( 'wp-block-button', $bcls ) ) . '"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $url ) . '">' . $text . '</a></div>'
			. "\n<!-- /wp:button -->";
	}
	return '<!-- wp:buttons' . axd_b_attrs( array( 'className' => $class ) ) . " -->\n"
		. '<div class="' . esc_attr( axd_b_cls( 'wp-block-buttons', $class ) ) . '">' . $inner . "</div>\n<!-- /wp:buttons -->\n\n";
}

/**
 * List block.
 *
 * @param array  $items   List item HTML strings.
 * @param string $class   Extra classes.
 * @param bool   $ordered Ordered list.
 * @return string
 */
function axd_b_list( $items, $class = '', $ordered = false ) {
	$tag   = $ordered ? 'ol' : 'ul';
	$inner = array();
	foreach ( $items as $item ) {
		$inner[] = "<!-- wp:list-item -->\n<li>" . $item . "</li>\n<!-- /wp:list-item -->";
	}
	$attrs = array(
		'ordered'   => $ordered ? true : null,
		'className' => $class,
	);
	return '<!-- wp:list' . axd_b_attrs( $attrs ) . " -->\n"
		. '<' . $tag . ' class="' . esc_attr( axd_b_cls( 'wp-block-list', $class ) ) . '">' . implode( "\n\n", $inner ) . '</' . $tag . ">\n<!-- /wp:list -->\n\n";
}

/**
 * Columns block.
 *
 * @param array  $cols  Inner markup per column.
 * @param string $class Extra classes.
 * @return string
 */
function axd_b_cols( $cols, $class = '' ) {
	$inner = '';
	foreach ( $cols as $col ) {
		$inner .= "<!-- wp:column -->\n<div class=\"wp-block-column\">" . $col . "</div>\n<!-- /wp:column -->\n\n";
	}
	return '<!-- wp:columns' . axd_b_attrs( array( 'className' => $class ) ) . " -->\n"
		. '<div class="' . esc_attr( axd_b_cls( 'wp-block-columns', $class ) ) . '">' . rtrim( $inner ) . "</div>\n<!-- /wp:columns -->\n\n";
}

/**
 * Shortcode block.
 *
 * @param string $shortcode Shortcode text.
 * @return string
 */
function axd_b_shortcode( $shortcode ) {
	return "<!-- wp:shortcode -->\n" . $shortcode . "\n<!-- /wp:shortcode -->\n\n";
}

/* ---------------------------------------------------------------------------
 * Section builders
 * ------------------------------------------------------------------------ */

/**
 * Page hero: rounded gradient panel, copy left, image right.
 *
 * @param array $o eyebrow, title, lead, buttons, img, alt, img_class, home.
 * @return string
 */
function axd_s_hero( $o ) {
	$o = wp_parse_args(
		$o,
		array(
			'eyebrow'   => '',
			'title'     => '',
			'lead'      => '',
			'buttons'   => array(),
			'img'       => '',
			'alt'       => '',
			'img_class' => '',
			'home'      => false,
		)
	);

	$left  = axd_b_p( esc_html( $o['eyebrow'] ), 'axd-eyebrow is-dark has-rule' );
	$left .= axd_b_h( 1, $o['title'] );
	$left .= axd_b_p( $o['lead'], 'axd-lead' );
	if ( $o['buttons'] ) {
		$left .= axd_b_buttons( $o['buttons'] );
	}
	$right = axd_b_img( axd_img( $o['img'] ), $o['alt'], trim( 'axd-hero__img ' . $o['img_class'] ) );

	$hero = axd_b_group( axd_b_cols( array( $left, $right ) ), $o['home'] ? 'axd-hero axd-hero--home' : 'axd-hero', 'div', 'constrained' );
	return axd_b_group( $hero, 'axd-hero-shell' );
}

/**
 * Centered section heading (eyebrow, title, optional subtitle).
 *
 * @param string $eyebrow Eyebrow text.
 * @param string $title   Title HTML.
 * @param string $sub     Subtitle text.
 * @return string
 */
function axd_s_head( $eyebrow, $title, $sub = '' ) {
	$inner  = axd_b_p( esc_html( $eyebrow ), 'axd-eyebrow' );
	$inner .= axd_b_h( 2, $title );
	if ( $sub ) {
		$inner .= axd_b_p( esc_html( $sub ), 'axd-sub' );
	}
	return axd_b_group( $inner, 'axd-section-head' );
}

/**
 * A section wrapper.
 *
 * @param string $inner  Inner markup.
 * @param string $extra  Extra classes (is-alt, is-tight-top).
 * @param string $anchor Optional HTML anchor (id).
 * @return string
 */
function axd_s_section( $inner, $extra = '', $anchor = '' ) {
	return axd_b_group( $inner, trim( 'axd-section ' . $extra ), 'section', 'constrained', $anchor );
}

/**
 * Grid of icon tiles.
 *
 * @param array $tiles List of [ icon, color, title, text, url? ].
 * @param int   $cols  2|3|4.
 * @return string
 */
function axd_s_tiles( $tiles, $cols = 3 ) {
	$inner = '';
	foreach ( $tiles as $t ) {
		$url    = isset( $t[4] ) ? $t[4] : '';
		$title  = $url ? '<a href="' . esc_url( $url ) . '">' . esc_html( $t[2] ) . '</a>' : esc_html( $t[2] );
		$tile   = axd_b_icon( $t[0], $t[1] );
		$tile  .= axd_b_h( 3, $title );
		$tile  .= axd_b_p( esc_html( $t[3] ) );
		$inner .= axd_b_group( $tile, $url ? 'axd-tile is-link' : 'axd-tile' );
	}
	return axd_b_group( $inner, 'axd-grid-' . (int) $cols );
}

/**
 * Two stat/info cards.
 *
 * @param array $cards List of [ 'num' => '', 'icon' => '', 'color' => '', 'title' => '', 'text' => '' ].
 * @return string
 */
function axd_s_statcards( $cards ) {
	$inner = '';
	foreach ( $cards as $c ) {
		$card = '';
		if ( ! empty( $c['num'] ) ) {
			$card .= axd_b_p( '<span class="axd-grad">' . esc_html( $c['num'] ) . '</span>', 'axd-statcard__num' );
		}
		if ( ! empty( $c['icon'] ) ) {
			$card .= axd_b_icon( $c['icon'], $c['color'], 'is-sm' );
		}
		if ( ! empty( $c['title'] ) ) {
			$card .= axd_b_p( esc_html( $c['title'] ), 'axd-statcard__title' );
		}
		$card  .= axd_b_p( esc_html( $c['text'] ), 'axd-statcard__text' );
		$inner .= axd_b_group( $card, 'axd-statcard' );
	}
	return axd_b_group( $inner, 'axd-statcards' );
}

/**
 * Collaboration section: navy quote card left, copy + checklist right.
 *
 * @param array $o quote, small, eyebrow, title, body, checks.
 * @return string
 */
function axd_s_quote_split( $o ) {
	$quote  = axd_b_img( esc_url( get_theme_file_uri( 'assets/icons/quote.svg' ) ), '', 'axd-quote__mark' );
	$quote .= axd_b_p( esc_html( $o['quote'] ), 'axd-quote__text' );
	if ( ! empty( $o['small'] ) ) {
		$quote .= axd_b_p( esc_html( $o['small'] ), 'axd-quote__small' );
	}
	$right  = axd_b_p( esc_html( $o['eyebrow'] ), 'axd-eyebrow has-rule' );
	$right .= axd_b_h( 2, $o['title'] );
	$right .= axd_b_p( esc_html( $o['body'] ), 'axd-body' );
	$right .= axd_b_list( array_map( 'esc_html', $o['checks'] ), 'axd-checks' );

	return axd_s_section( axd_b_cols( array( axd_b_group( $quote, 'axd-quote' ), $right ), 'axd-split' ) );
}

/**
 * Approach section (Solutions pages): heading + image left, copy + steps right.
 *
 * @param array $o eyebrow, title, img, alt, body, steps.
 * @return string
 */
function axd_s_approach( $o ) {
	$left   = axd_b_p( esc_html( $o['eyebrow'] ), 'axd-eyebrow has-rule' );
	$left  .= axd_b_h( 2, $o['title'] );
	$left  .= axd_b_img( axd_img( $o['img'] ), $o['alt'], 'axd-approach-img' );
	$right  = axd_b_p( esc_html( $o['body'] ), 'axd-body' );
	$right .= axd_b_list( array_map( 'esc_html', $o['steps'] ), 'axd-steps', true );
	return axd_s_section( axd_b_cols( array( $left, $right ), 'axd-split is-top' ) );
}

/**
 * Navy feature panel.
 *
 * @param array $o eyebrow, title, paras, pills, img, alt, img_class, buttons, home, img_first.
 * @return string
 */
function axd_s_feature( $o ) {
	$o = wp_parse_args(
		$o,
		array(
			'anchor'    => '',
			'eyebrow'   => '',
			'title'     => '',
			'paras'     => array(),
			'pills'     => array(),
			'img'       => '',
			'alt'       => '',
			'img_class' => 'is-shadow',
			'buttons'   => array(),
			'home'      => false,
			'img_first' => false,
			'extra'     => '',
		)
	);
	$text = axd_b_p( esc_html( $o['eyebrow'] ), 'axd-eyebrow is-light has-rule' );
	$text .= axd_b_h( 2, $o['title'] );
	foreach ( $o['paras'] as $p ) {
		$text .= axd_b_p( esc_html( $p ) );
	}
	if ( $o['pills'] ) {
		$text .= axd_b_list( array_map( 'esc_html', $o['pills'] ), 'axd-pills' );
	}
	$text .= $o['extra'];
	if ( $o['buttons'] ) {
		$text .= axd_b_buttons( $o['buttons'] );
	}
	$img  = axd_b_img( axd_img( $o['img'] ), $o['alt'], trim( 'axd-feature__img ' . $o['img_class'] ) );
	$cols = $o['img_first'] ? array( $img, $text ) : array( $text, $img );

	$panel = axd_b_group( axd_b_cols( $cols ), $o['home'] ? 'axd-feature axd-feature--home' : 'axd-feature' );
	return axd_s_section( $panel, 'is-tight-top', $o['anchor'] );
}

/**
 * A full Solutions page (hero, approach, challenges, collaboration,
 * feature panel, CTA).
 *
 * @param array $o Page definition.
 * @return string
 */
function axd_s_solution_page( $o ) {
	$out  = axd_s_hero( $o['hero'] );
	$out .= axd_s_approach( $o['approach'] );
	$out .= axd_s_section(
		axd_s_head( 'How We Help', $o['challenges_title'] ) . axd_s_tiles( $o['challenges'] ),
		'is-alt'
	);
	$out .= axd_s_quote_split( $o['collab'] );
	$out .= axd_s_feature( $o['feature'] );
	$out .= axd_s_cta();
	return $out;
}

/**
 * "Let's Build What's Next" CTA band.
 *
 * @param string $text Supporting line.
 * @return string
 */
function axd_s_cta( $text = 'Have a project, challenge, or idea? Let’s talk about how AXD can help.' ) {
	$inner  = axd_b_h( 2, 'Let’s Build What’s Next.' );
	$inner .= axd_b_p( esc_html( $text ) );
	$inner .= axd_b_buttons( array( array( 'Schedule a Consultation', '/contact/', 'is-style-white has-calendar' ) ) );
	return axd_b_group( $inner, 'axd-cta', 'section', 'constrained' );
}
