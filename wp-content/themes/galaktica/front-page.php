<?php get_header(); ?>

<div class="container mt-4">
	<div class="row">
		<div class="col-md-8">
			<?php if (have_posts()) : ?>
				<?php while (have_posts()) : the_post(); ?>
					<article class="mb-4">
						<h1><?php the_title(); ?></h1>
						<div><?php the_content(); ?></div>
					</article>
				<?php endwhile; ?>
			<?php else : ?>
				<p><?php _e('No content found', 'galaktica'); ?></p>
			<?php endif; ?>
		</div>
	</div>
	<h3 class="mb-4"><?php echo esc_html__('Альбоми', 'galaktica'); ?></h3>
	<?php get_template_part('template-parts/content', 'albums'); ?>
	<div class="col-md-4">
		<?php get_sidebar(); ?>
	</div>
</div>

<?php get_footer(); ?>
