<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package starter
 */

if ( ! function_exists( 'starter_get_the_post_thumbnail' ) ) :
	/**
	 * Gets the thumbnail with Lazy Load.
	 * Should be called in the WordPress Loop.
	 *
	 * @param int|null $post_id               Post ID.
	 * @param string   $size                  The registered image size.
	 * @param array    $additional_attributes Additional attributes.
	 *
	 * @return string
	 */

	function starter_get_the_post_thumbnail( $post_id, $size = 'featured-thumbnail', $additional_attributes = [] ) {
		$custom_thumbnail = '';

		if ( null === $post_id ) {
			$post_id = get_the_ID();
		}

		if ( has_post_thumbnail( $post_id ) ) {
			$default_attributes = [
				'loading' => 'lazy'
			];

			$attributes = array_merge( $additional_attributes, $default_attributes );

			$custom_thumbnail = wp_get_attachment_image(
				get_post_thumbnail_id( $post_id ),
				$size,
				false,
				$attributes
			);
		}

		return $custom_thumbnail;
	}
endif;

if ( ! function_exists( 'starter_the_post_thumbnail' ) ) :
	/**
	 * Renders Custom Thumbnail with Lazy Load.
	 *
	 * @param int    $post_id               Post ID.
	 * @param string $size                  The registered image size.
	 * @param array  $additional_attributes Additional attributes.
	 */
	function starter_the_post_thumbnail( $post_id, $size = 'featured-thumbnail', $additional_attributes = [] ) {
		echo starter_get_the_post_thumbnail( $post_id, $size, $additional_attributes );
	}
endif;

if ( ! function_exists( 'starter_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function starter_posted_on() {
		
		$year                        = get_the_date( 'Y' );
		$month                       = get_the_date( 'n' );
		$day                         = get_the_date( 'j' );
		$post_date_archive_permalink = get_day_link( $year, $month, $day );

		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		// Post is modified ( when post published time is not equal to post modified time )
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_attr( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_attr( get_the_modified_date() )
		);

		$posted_on = sprintf(
			esc_html_x( 'Posted on %s', 'post date', 'starter' ),
			'<a href="' . esc_url( $post_date_archive_permalink ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>';

	}
endif;

if ( ! function_exists( 'starter_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function starter_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'starter' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

/**
 * Get the trimmed version of post excerpt.
 *
 * This is for modifing manually entered excerpts,
 * NOT automatic ones WordPress will grab from the content.
 *
 * It will display the first given characters ( e.g. 100 ) characters of a manually entered excerpt,
 * but instead of ending on the nth( e.g. 100th ) character,
 * it will truncate after the closest word.
 *
 * @param int $trim_character_count Charter count to be trimmed
 *
 * @return bool|string
 */
function starter_the_excerpt( $trim_character_count = 0 ) {
	$post_ID = get_the_ID();

	if ( empty( $post_ID ) ) {
		return null;
	}

	if ( has_excerpt() || 0 === $trim_character_count ) {
		the_excerpt();

		return;
	}

	$excerpt = wp_html_excerpt( get_the_excerpt( $post_ID ), $trim_character_count, '[...]' );

	echo esc_html( $excerpt );
}

/**
 * Filter the "read more" excerpt string link to the post.
 *
 * @param string $more "Read more" excerpt string.
 *
 * @return string (Maybe) modified "read more" excerpt string.
 */
function starter_excerpt_more( $more = '' ) {

	if ( ! is_single() ) {
		$more = sprintf( '<a class="starter-read-more" href="%1$s">%2$s</a>',
			get_permalink( get_the_ID() ),
			__( 'Read more', 'starter' )
		);
	}

	return $more;
}

/**
 * Starter Pagination.
 *
 * @return void
 */
function starter_pagination() {

	$allowed_tags = [
		'span' => [
			'class' => []
		],
		'a' => [
			'class' => [],
			'href' => [],
		]
	];

	$args = [
		'before_page_number' => '<span class="btn border border-secondary mr-2 mb-2">',
		'after_page_number' => '</span>',
	];

	printf( '<nav class="starter-pagination clearfix">%s</nav>', wp_kses( paginate_links( $args ), $allowed_tags ) );
}

/**
 * Display Post pagination with prev next, first last, to, from
 *
 * @param $current_page_no
 * @param $posts_per_page
 * @param $article_query
 * @param $first_page_url
 * @param $last_page_url
 * @param bool $is_query_param_structure
 */
function starter_the_post_pagination( $current_page_no, $posts_per_page, $article_query, $first_page_url, $last_page_url, bool $is_query_param_structure = true ) {
	
	$prev_posts = ( $current_page_no - 1 ) * $posts_per_page;
	$from = 1 + $prev_posts;
	$to = count( $article_query->posts ) + $prev_posts;
	$of = $article_query->found_posts;
	$total_pages = $article_query->max_num_pages;

	$base = ! empty( $is_query_param_structure ) ? add_query_arg( 'page', '%#%' ) :  get_pagenum_link( 1 ) . '%_%';
	$format = ! empty( $is_query_param_structure ) ? '?page=%#%' : 'page/%#%';

	?>
	<div class="mt-0 md:mt-10 mb-10 lg:my-5 flex items-center justify-end posts-navigation">
		<?php
		if ( 1 < $total_pages && !empty( $first_page_url ) ) {
			printf(
				'<span class="mr-2">Showing %1$s - %2$s Of %3$s</span>',
				$from,
				$to,
				$of
			);
		}

		// First Page
		if ( 1 !== $current_page_no && ! empty( $first_page_url ) ) {
			printf( '<a class="first-pagination-link btn border border-secondary mr-2" href="%1$s" title="first-pagination-link">%2$s</a>', esc_url( $first_page_url ), __( 'First', 'starter' ) );
		}

		echo paginate_links( [
			'base'      => $base,
			'format'    => $format,
			'current'   => $current_page_no,
			'total'     => $total_pages,
			'prev_text' => __( 'Prev', 'starter' ),
			'next_text' => __( 'Next', 'starter' ),
		] );

		// Last Page
		if ( $current_page_no < $total_pages && !empty( $last_page_url ) ) {

			printf( '<a class="last-pagination-link btn border border-secondary ml-2" href="%1$s" title="last-pagination-link">%2$s</a>', esc_url( $last_page_url ), __( 'Last', 'starter' ) );
		}
		?>
	</div>
	<?php
}

/**
 * Simple pagination without arguments
 */
function starter_simple_pagination() { ?>
	<div class="starter-pagination">
		<ul class="pagination">
			<li>
				<?php

				global $wp_query;	
				$big = 999999999; // need an unlikely integer

				echo paginate_links(
					[
						'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
						'format'    => '?paged=%#%',
						'current'   => max(1, get_query_var('paged')),
						'total'     => $wp_query->max_num_pages,
						'type'      => '',
						'prev_text' => '<i class="las la-arrow-left"></i>',
						'next_text' => '<i class="las la-arrow-right"></i>',
					]
				);
				?>
			</li>
		</ul>
	</div>
<?php
}

/**
 * Checks to see if the specified user id has a uploaded the image via wp_admin.
 *
 * @return bool  Whether or not the user has a gravatar
 */
function starter_is_uploaded_via_wp_admin( $gravatar_url ) {

	$parsed_url = wp_parse_url( $gravatar_url );

	$query_args = ! empty( $parsed_url['query'] ) ? $parsed_url['query'] : '';

	// If query args is empty means, user has uploaded gravatar.
	return empty( $query_args );

}

/**
 * If the gravatar is uploaded returns true.
 *
 * There are two things we need to check, If user has uploaded the gravatar:
 * 1. from WP Dashboard, or
 * 2. or gravatar site.
 *
 * If any of the above condition is true, user has valid gravatar,
 * and the function will return true.
 *
 * 1. For Scenario 1: Upload from WP Dashboard:
 * We check if the query args is present or not.
 *
 * 2. For Scenario 2: Upload on Gravatar site:
 * When constructing the URL, use the parameter d=404.
 * This will cause Gravatar to return a 404 error rather than an image if the user hasn't set a picture.
 *
 * @param $user_email
 *
 * @return bool
 */
function starter_has_gravatar( $user_email ) {

	$gravatar_url = get_avatar_url( $user_email );

	if ( starter_is_uploaded_via_wp_admin( $gravatar_url ) ) {
		return true;
	}

	$gravatar_url = sprintf( '%s&d=404', $gravatar_url );

	// Make a request to $gravatar_url and get the header
	$headers = @get_headers( $gravatar_url );

	// If request status is 200, which means user has uploaded the avatar on gravatar site
	return preg_match( "|200|", $headers[0] );
}

if ( ! function_exists( 'starter_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function starter_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'starter' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'starter' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'starter' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'starter' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'starter' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'starter' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
endif;
