function toggleDropdown() {
  const menu = document.getElementById("optionsMenu");
  const icon = document.getElementById("dropdownIcon");

  if (menu.style.maxHeight) {
    menu.style.maxHeight = null;
    icon.classList.remove("rotate-180");
  } else {
    menu.style.maxHeight = menu.scrollHeight + "px";
    icon.classList.add("rotate-180");
  }
}
