<?php get_header(); ?>
<section class="wrap content">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<article <?php post_class(); ?>><h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1><?php the_excerpt(); ?></article>
		<?php endwhile; ?>
	<?php else : ?>
		<h1>Nothing found</h1>
	<?php endif; ?>
</section>
<?php get_footer(); ?>