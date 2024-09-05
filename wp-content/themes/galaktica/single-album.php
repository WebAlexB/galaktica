<?php get_header(); ?>

<div class="container mt-4">
	<div class="row">
		<div class="col-md-8">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<article class="mb-4">
						<h1><?php the_title(); ?></h1>
						<p>
							<strong>
								<?php echo esc_html__( 'Рік випуску:', 'galaktica' ) ?>
							</strong>
							<?php echo get_post_meta( get_the_ID(), 'album_year', true ); ?>
						</p>
						<p>
							<strong>
								<?php echo esc_html__( 'Жанр:', 'galaktica' ) ?>
							</strong>
							 <?php echo get_post_meta( get_the_ID(), 'album_genre', true ); ?>
						</p>
						<div><?php the_content(); ?></div>
					</article>
				<?php endwhile; ?>
			<?php else : ?>
				<p><?php _e( 'not found', 'galaktica' ); ?></p>
			<?php endif; ?>
		</div>
		<div class="col-md-4">
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
