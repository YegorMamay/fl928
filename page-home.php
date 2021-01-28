<?php
/**
 * Template Name: Home Page
 **/
?>

<?php get_header(); ?>
<div class="container">

<?php get_template_part('loops/content', 'home'); ?>

<!-- <?php echo do_shortcode('[layerslider id="1"]'); ?> -->

<?php
$catList = get_field('cat-list');
if (!empty($catList)) {
?>


<!-- Первая секция -->
<?php if (get_field('shortcode-first-section')) { ?>
<section class="first-sec">
	<?php $shortcode = get_field('shortcode-first-section') ?>
	<?php echo do_shortcode($shortcode) ?>
</section>
<?php } ?>

<!-- Секция "Категории товара" -->
<section class="categories" id="categories">
	<div class="categories__wrapp d-flex align-items-center flex-column">
		<h2 class="categories__main-title">
			<?php the_field('cat-main-title') ?>
		</h2>
		<ul class="categories__list">
			<?php foreach ($catList as $catItem) { ?>
			<li class="categories__item">
				<a class="h1 categories__item-title" href="<?php echo $catItem['cat-link'] ?>">
					<?php echo $catItem['cat-title'] ?>
				</a>
				<div class="categories__item-photo-wrapp">
					<div class="categories__item-photo">
						<?php
							$catItemImgs = $catItem['cat-all-img'];
							foreach ($catItemImgs as $catItemImg) {
								?>
								<div class="categories__photo d-flex justify-content-center align-items-center">
									<img src="<?php echo $catItemImg['url']; ?>" alt="<?php echo $catItemImg['alt']; ?>">
								</div>
								<?php
							}
						?>
					</div>
				</div>
			</li>
			<?php } ?>
		</ul>
		<a href="/shop/" class="btn btn-primary btn-lg categories__btn">
			Весь ассортимент
		</a>
	</div>
</section>
<?php } ?>

</div><!-- /.container -->

<!-- Секция "Мы рекомендуем" -->
<?php if (get_field('rec-short')) { ?>
<section class="recommend" id="recommend">
	<?php if (get_field('rec-img')) {
		?>
		<div class="recommend__title-back d-flex align-items-center">
			<img src="<?php the_field('rec-img') ?>" alt="Рекомендуем">
		</div>
		<?php
	} ?>
	<div class="container">
		<div class="recommend__wrapp">
			<p class="recommend__title-second">
				<?php the_field('rec-sub-title'); ?>
			</p>
			<h2 class="recommend__title">
				<?php the_field('rec-title'); ?>
			</h2>
			<?php $shortcode = get_field('rec-short') ?>
			<?php echo do_shortcode($shortcode) ?>
		</div>
	</div>
</section>
<?php } ?>


<?php get_footer(); ?>
