// Highlight Active Navigation Link
document.addEventListener("DOMContentLoaded", function () {
  const navLinks = document.querySelectorAll("nav a"); // Select all nav links
  const currentPage = window.location.pathname; // Get the current URL path

  navLinks.forEach(link => {
      if (link.getAttribute("href") === currentPage) {
          link.classList.add("text-purple-600", "dark:text-purple-400", "font-semibold");
      } else {
          link.classList.remove("text-purple-600", "dark:text-purple-400", "font-semibold");
      }
  });
});

// Mobile menu toggle functionality
const menuToggle = document.getElementById("menu-toggle");
const mobileMenu = document.getElementById("mobile-menu");

menuToggle.addEventListener("click", () => {
  mobileMenu.classList.toggle("open");
});
