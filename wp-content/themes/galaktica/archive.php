<?php get_header(); ?>
<div class="container mt-4">
	<div class="row">
		<div class="col-md-8">
			<h1 class="page-title"><?php the_archive_title(); ?></h1>
			<?php if (have_posts()) : ?>
				<?php while (have_posts()) : the_post(); ?>
					<article class="mb-4">
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<div><?php the_excerpt(); ?></div>
					</article>
				<?php endwhile; ?>
				<nav aria-label="Page navigation">
					<ul class="pagination">
						<?php
						echo paginate_links(array(
							'prev_text' => '<span aria-hidden="true">&laquo;</span>',
							'next_text' => '<span aria-hidden="true">&raquo;</span>',
						));
						?>
					</ul>
				</nav>
			<?php else : ?>
				<p><?php _e('not found', 'galaktica'); ?></p>
			<?php endif; ?>
		</div>
		<div class="col-md-4">
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>
<?php get_footer(); ?>
