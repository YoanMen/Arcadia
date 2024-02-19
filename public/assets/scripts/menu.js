const desktopMenu = document.querySelector(".desktop-menu--fixed");

const menuBtn = document.querySelector(".mobile-menu__btn");
const closeBtn = document.querySelector(".mobile-menu__btn-close");
const tab = document.querySelector(".mobile-menu__tab");

setMenu();

// get scroll position for switching between fixed/ not fixed menu
window.addEventListener("scroll", () => {
  setMenu();
});

closeBtn.addEventListener("click", () => {
  tab.classList.remove("mobile-menu__tab--open");
  document.body.classList.remove("no-scroll");
});

menuBtn.addEventListener("click", () => {
  tab.classList.add("mobile-menu__tab--open");
  document.body.classList.add("no-scroll");
});

function setMenu() {
  const scrollPosition = window.scrollY;
  if (
    (scrollPosition >= 0 && scrollPosition != 0) ||
    window.location != "http://localhost/Arcadia/public/"
  ) {
    desktopMenu.classList.remove("desktop-menu");
    desktopMenu.classList.add("desktop-menu--fixed");
  } else {
    desktopMenu.classList.remove("desktop-menu--fixed");
    desktopMenu.classList.add("desktop-menu");
  }
}
