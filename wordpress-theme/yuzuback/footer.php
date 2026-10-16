</main>
<footer class="footer">
	<div class="wrap">
		<div class="footer-top">
			<div>
				<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<span class="brand-mark" aria-hidden="true">Y</span>
					YuzuBack
				</a>
				<p class="footer-note">A free desktop backup manager for Windows and Linux.</p>
			</div>
			<div class="footer-links">
				<div>
					<b>Explore</b>
					<a href="<?php echo esc_url( yuzuback_page_url( 'downloads' ) ); ?>">Downloads</a>
					<a href="<?php echo esc_url( yuzuback_page_url( 'changelog' ) ); ?>">Changelog</a>
				</div>
				<div>
					<b>Project</b>
					<a href="<?php echo esc_url( yuzuback_page_url( 'contact' ) ); ?>">Contact</a>
					<a href="<?php echo esc_url( yuzuback_page_url( 'donate' ) ); ?>">Donate</a>
				</div>
			</div>
		</div>
		<div class="footer-bottom">
			<span>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> YuzuBack</span>
			<span>Freeware &middot; Proprietary software</span>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>