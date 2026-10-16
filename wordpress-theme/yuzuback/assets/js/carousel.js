(() => {
	const carousel = document.querySelector('[data-carousel]');
	if (!carousel) return;

	const slides = Array.from(carousel.querySelectorAll('.carousel-slide'));
	const dots = Array.from(carousel.querySelectorAll('[data-carousel-slide]'));
	const caption = document.querySelector('[data-carousel-caption]');	
	let activeIndex = 0;

	const showSlide = (index) => {
		activeIndex = (index + slides.length) % slides.length;

		slides.forEach((slide, slideIndex) => {
			const position = (slideIndex - activeIndex + slides.length) % slides.length;
			slide.classList.toggle('is-active', position === 0);
			slide.classList.toggle('is-next', position === 1);
			slide.classList.toggle('is-previous', position === slides.length - 1);
			slide.setAttribute('aria-hidden', position === 0 ? 'false' : 'true');
		});

		dots.forEach((dot, dotIndex) => {
			const isActive = dotIndex === activeIndex;
			dot.classList.toggle('is-active', isActive);
			dot.setAttribute('aria-pressed', isActive ? 'true' : 'false');
		});

		caption.textContent = slides[activeIndex].dataset.caption;
	};

	carousel.querySelector('[data-carousel-prev]').addEventListener('click', () => showSlide(activeIndex - 1));
	carousel.querySelector('[data-carousel-next]').addEventListener('click', () => showSlide(activeIndex + 1));
	dots.forEach((dot) => {
		dot.addEventListener('click', () => showSlide(Number(dot.dataset.carouselSlide)));
	});

	carousel.addEventListener('keydown', (event) => {
		if (event.key === 'ArrowLeft') showSlide(activeIndex - 1);
		if (event.key === 'ArrowRight') showSlide(activeIndex + 1);
	});

	showSlide(activeIndex);
})();