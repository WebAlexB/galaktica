<?php
/**
 * The template for displaying albums
 *
 * @package WordPress
 * @subpackage Galaktica
 * @since Galaktica  1.0
 */
?>
<div class="row">
	<?php
	$query = new WP_Query(array('post_type' => 'album'));
	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			?>
			<div class="col-md-4 mb-4">
				<div class="card h-100">
					<div class="card-body">
						<h5 class="card-title">
							<a href="<?php the_permalink(); ?>">
								<?php the_title(); ?>
							</a>
						</h5>
					</div>
				</div>
			</div>
			<?php
		}
	} else {
		echo '<p>' . __('No albums found.', 'galaktica') . '</p>';
	}
	wp_reset_postdata();
	?>
</div>
