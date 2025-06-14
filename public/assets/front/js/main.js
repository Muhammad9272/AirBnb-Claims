// Function to load components dynamically
function loadComponent(id, file) {
    fetch(file)
      .then(response => response.text())
      .then(data => document.getElementById(id).innerHTML = data)
      .catch(error => console.error(`Error loading ${file}:`, error));
  }
  
  // Load Navbar & Footer
  document.addEventListener("DOMContentLoaded", function () {
    loadComponent("navbar", "../components/navbar/navbar.html");
    loadComponent("homePageCardSection", "../components/homePageCardSection/homePageCardSection.html");
    loadComponent("footer", "../components/footer/footer.html");
  });