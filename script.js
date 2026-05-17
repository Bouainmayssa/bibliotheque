document.addEventListener("DOMContentLoaded", () => {
    const currentYearNode = document.querySelector("[data-current-year]");
    if (currentYearNode) {
        currentYearNode.textContent = String(new Date().getFullYear());
    }

    const revealElements = document.querySelectorAll(".reveal");
    if (revealElements.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("visible");
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.2 });

        revealElements.forEach((element) => observer.observe(element));
    }

    const slides = document.querySelectorAll(".slide");
    const dots = document.querySelectorAll(".dot");
    if (slides.length > 0) {
        let activeSlide = 0;
        const showSlide = (index) => {
            slides.forEach((slide, idx) => {
                slide.classList.toggle("active", idx === index);
            });
            dots.forEach((dot, idx) => {
                dot.classList.toggle("active", idx === index);
            });
        };

        const nextSlide = () => {
            activeSlide = (activeSlide + 1) % slides.length;
            showSlide(activeSlide);
        };

        document.querySelectorAll("[data-slider]").forEach((button) => {
            button.addEventListener("click", () => {
                if (button.getAttribute("data-slider") === "prev") {
                    activeSlide = (activeSlide - 1 + slides.length) % slides.length;
                } else {
                    activeSlide = (activeSlide + 1) % slides.length;
                }
                showSlide(activeSlide);
            });
        });

        dots.forEach((dot) => {
            dot.addEventListener("click", () => {
                const index = Number(dot.getAttribute("data-dot"));
                if (!Number.isNaN(index)) {
                    activeSlide = index;
                    showSlide(activeSlide);
                }
            });
        });

        setInterval(nextSlide, 4500);
        showSlide(activeSlide);
    }
});
