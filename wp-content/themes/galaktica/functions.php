<?php
function galaktica_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'galaktica' ),
	) );
	add_theme_support( 'custom-logo', array(
		'height'      => 100,
		'width'       => 400,
		'flex-height' => true,
		'flex-width'  => true,
	) );
}

add_action( 'after_setup_theme', 'galaktica_setup' );

function galaktica_scripts() {
	wp_enqueue_style( 'bootstrap-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css' );
	wp_enqueue_style( 'galaktica-style', get_stylesheet_uri() );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js',
		array( 'jquery' ), null, true );
}

add_action( 'wp_enqueue_scripts', 'galaktica_scripts' );

function admin_scripts() {
	global $typenow;
	if ( $typenow === 'album' ) {
		wp_enqueue_style( 'bootstrap-admin-css',
			'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css' );
		wp_enqueue_style( 'galaktica-admin-style', get_stylesheet_uri() );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'bootstrap-admin-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js',
			array( 'jquery' ), null, true );
	}
}

add_action( 'admin_enqueue_scripts', 'admin_scripts' );

function galaktica_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'galaktica' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}

add_action( 'widgets_init', 'galaktica_widgets_init' );

function create_album_post_type() {
	$labels = array(
		'name'               => 'Albums',
		'singular_name'      => 'Album',
		'menu_name'          => 'Albums',
		'name_admin_bar'     => 'Album',
		'add_new'            => 'Add New',
		'add_new_item'       => 'Add New Album',
		'edit_item'          => 'Edit Album',
		'new_item'           => 'New Album',
		'view_item'          => 'View Album',
		'search_items'       => 'Search Albums',
		'not_found'          => 'No albums found',
		'not_found_in_trash' => 'No albums found in Trash',
	);

	$args = array(
		'labels'       => $labels,
		'public'       => true,
		'has_archive'  => true,
		'supports'     => array( 'title', 'editor', 'thumbnail' ),
		'rewrite'      => array( 'slug' => 'albums' ),
		'show_in_rest' => true,
		'taxonomies'   => array( 'singles' ),
		'show_ui'      => true,
		'show_in_menu' => true,
	);

	register_post_type( 'album', $args );
}

add_action( 'init', 'create_album_post_type' );


function album_custom_fields() {
	add_meta_box(
		'album_details',
		'Album Details',
		'render_album_fields',
		'album',
		'normal',
		'high'
	);
}

add_action( 'add_meta_boxes', 'album_custom_fields' );

function render_album_fields( $post ) {
	$year  = get_post_meta( $post->ID, 'album_year', true );
	$genre = get_post_meta( $post->ID, 'album_genre', true );
	?>
	<div class="form-group">
		<label for="album_year" class="col-form-label">Release Date:</label>
		<input type="date" name="album_year" class="form-control" value="<?php echo esc_attr( $year ); ?>"/>
	</div>
	<div class="form-group">
		<label for="album_genre" class="col-form-label">Genre:</label>
		<input type="text" name="album_genre" class="form-control" value="<?php echo esc_attr( $genre ); ?>"/>
	</div>
	<?php
}


function save_album_custom_fields( $post_id ) {
	if ( isset( $_POST['album_year'] ) ) {
		update_post_meta( $post_id, 'album_year', sanitize_text_field( $_POST['album_year'] ) );
	}
	if ( isset( $_POST['album_genre'] ) ) {
		update_post_meta( $post_id, 'album_genre', sanitize_text_field( $_POST['album_genre'] ) );
	}
}

add_action( 'save_post', 'save_album_custom_fields' );

function create_singles_taxonomy() {
	$labels = array(
		'name'              => _x( 'Singles', 'taxonomy general name' ),
		'singular_name'     => _x( 'Single', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Singles' ),
		'all_items'         => __( 'All Singles' ),
		'parent_item'       => __( 'Parent Single' ),
		'parent_item_colon' => __( 'Parent Single:' ),
		'edit_item'         => __( 'Edit Single' ),
		'update_item'       => __( 'Update Single' ),
		'add_new_item'      => __( 'Add New Single' ),
		'new_item_name'     => __( 'New Single Name' ),
		'menu_name'         => __( 'Singles' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'singles' ),
	);

	register_taxonomy( 'singles', array( 'album' ), $args );
}

add_action( 'init', 'create_singles_taxonomy' );

// added in page [display_albums count="" genre=""]
function shortcode_display_albums( $atts ) {
	$atts = shortcode_atts(
		array(
			'count' => 1,
			'genre' => ''
		),
		$atts,
		'display_albums'
	);
	$args = array(
		'post_type'      => 'album',
		'posts_per_page' => intval( $atts['count'] ),
		'orderby'        => 'date',
		'order'          => 'DESC',
	);
	if ( ! empty( $atts['genre'] ) ) {
		$args['meta_query'] = array(
			array(
				'key'     => 'album_genre',
				'value'   => sanitize_text_field( $atts['genre'] ),
				'compare' => 'LIKE'
			)
		);
	}
	$query = new WP_Query( $args );
	ob_start();
	if ( $query->have_posts() ) {
		echo '<div class="container">';
		echo '<div class="row">';
		while ( $query->have_posts() ) {
			$query->the_post();
			echo '<div class="col-md-4 mb-4">';
			echo '<div class="card h-100">';
			if ( has_post_thumbnail() ) {
				echo '<a href="' . get_permalink() . '">';
				the_post_thumbnail( 'medium', array( 'class' => 'card-img-top' ) );
				echo '</a>';
			}
			echo '<div class="card-body">';
			echo '<h5 class="card-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h5>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
		echo '</div>';
		echo '</div>';
		wp_reset_postdata();
	} else {
		echo '<p>' . _e( 'No albums found.' ) . '</p>';
	}

	return ob_get_clean();
}

add_shortcode( 'display_albums', 'shortcode_display_albums' );

// added in page  [albums_with_songs]
function shortcode_albums_with_songs() {
	global $wpdb;

	$results = $wpdb->get_results( "
        SELECT p.post_title, COUNT(t.term_id) as song_count
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
        LEFT JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        LEFT JOIN {$wpdb->terms} t ON tt.term_id = t.term_id
        WHERE p.post_type = 'album' AND tt.taxonomy = 'singles'
        GROUP BY p.ID
    " );

	if ( $results ) {
		$output = '<div class="container mt-4">';
		$output .= '<div class="row">';
		$output .= '<div class="col-md-12">';
		$output .= '<ul class="list-group">';
		foreach ( $results as $row ) {
			$output .= '<li class="list-group-item">';
			$output .= '<strong>' . esc_html( $row->post_title ) . '</strong> - ';
			$output .= esc_html( $row->song_count ) . ' ' .  __( 'songs' );
			$output .= '</li>';
		}
		$output .= '</ul>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	} else {
		return '<div class="container mt-4"><p>' . __( 'No albums found.' ) . '</p></div>';
	}
}

add_shortcode( 'albums_with_songs', 'shortcode_albums_with_songs' );

function disable_gutenberg_for_custom_post_type($current_status, $post_type)
{
	if ($post_type === 'album') {
		return false;
	}
	return $current_status;
}
add_filter('use_block_editor_for_post_type', 'disable_gutenberg_for_custom_post_type', 10, 2);

?>
