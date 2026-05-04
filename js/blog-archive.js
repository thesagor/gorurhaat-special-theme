/**
 * Blog Archive JavaScript
 *
 * Handles animations and interactivity for blog archive page
 */

(function () {
  "use strict";

  // Initialize animations when page loads
  function initBlogArchiveAnimations() {
    // Animate cards on page load
    const cards = document.querySelectorAll(".blog-card");
    cards.forEach((card, index) => {
      // Set animation delay based on card index
      card.style.animationDelay = index * 0.1 + "s";
    });

    // Handle search form submission
    const searchForm = document.querySelector(".blog-search");
    if (searchForm) {
      searchForm.addEventListener("submit", function (e) {
        // Form will submit naturally to process search
      });
    }

    // Add smooth scroll behavior
    document.querySelectorAll(".blog-card a").forEach((link) => {
      link.addEventListener("click", function (e) {
        // Standard link behavior - no scroll hijacking
      });
    });
  }

  // Run on DOM ready
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initBlogArchiveAnimations);
  } else {
    initBlogArchiveAnimations();
  }

  // Run on page load for final adjustments
  window.addEventListener("load", initBlogArchiveAnimations);
})();
