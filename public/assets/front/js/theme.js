const themeToggleBtn = document.getElementById("theme-toggle");
const themeIcon = document.getElementById("theme-icon");
const html = document.documentElement;

// Check stored theme or system preference
const userTheme = localStorage.getItem("theme");
const systemDark = window.matchMedia("(prefers-color-scheme: dark)").matches;

console.log("User Theme from LocalStorage:", userTheme);
console.log("System prefers dark mode:", systemDark);

// Apply dark mode if user prefers it or system default is dark
if (userTheme === "dark" || (!userTheme && systemDark)) {
  html.classList.add("dark");
} else {
  html.classList.remove("dark");
}

// Toggle dark mode
themeToggleBtn.addEventListener("click", () => {
  console.log("Theme toggle clicked!");
  if (html.classList.contains("dark")) {
    console.log("Switching to light mode...");
    html.classList.remove("dark");
    localStorage.setItem("theme", "light");
  } else {
    console.log("Switching to dark mode...");
    html.classList.add("dark");
    localStorage.setItem("theme", "dark");
  }

  // Confirm class change in the console
  console.log("HTML classList:", html.classList);
});



