const desktopMenu = document.querySelector(".desktop-menu--fixed");
const menuBtn = document.querySelector(".mobile-menu__btn");
const closeBtn = document.querySelector(".mobile-menu__btn-close");
const tab = document.querySelector(".mobile-menu__tab");
const habitatsMenu = document.getElementsByName("menu-habitats");
const servicesMenu = document.getElementsByName("menu-services");

setMenuType();

// fetch data for services and habitats to show in the menu
initDataMenu().then(async (data) => {
  let serviceList = ``;
  let habitatList = ``;

  data.services.forEach((service) => {
    const urlName = service.name.replaceAll(" ", "-").toLowerCase();
    serviceList += `  <li>
    <a href="${window.location.origin}/Arcadia/public/services/${urlName} ">
      ${service.name} 
    </a>
  </li>`;
  });

  data.habitats.forEach((habitat) => {
    const urlName = habitat.name.replaceAll(" ", "-").toLowerCase();
    habitatList += `  <li>
    <a href="${window.location.origin}/Arcadia/public/services/${urlName} ">
      ${habitat.name} 
    </a>
  </li>`;
  });

  servicesMenu.forEach((service) => {
    service.innerHTML = serviceList;
  });
  habitatsMenu.forEach((habitat) => {
    habitat.innerHTML = habitatList;
  });
});

// switch between mobile menu open or not open
closeBtn.addEventListener("click", () => {
  tab.classList.remove("mobile-menu__tab--open");
  document.body.classList.remove("no-scroll");
});

menuBtn.addEventListener("click", () => {
  tab.classList.add("mobile-menu__tab--open");
  document.body.classList.add("no-scroll");
});

// switch menu between fixed / not fixed
window.addEventListener("scroll", () => {
  setMenuType();
});

function setMenuType() {
  const scrollPosition = window.scrollY;
  if (
    (scrollPosition >= 0 && scrollPosition != 0) ||
    window.location != window.location.origin + "/Arcadia/public/"
  ) {
    desktopMenu.classList.remove("desktop-menu");
    desktopMenu.classList.add("desktop-menu--fixed");
  } else {
    desktopMenu.classList.remove("desktop-menu--fixed");
    desktopMenu.classList.add("desktop-menu");
  }
}

// fetch data
async function initDataMenu() {
  const r = await fetch(
    window.location.origin + "/Arcadia/public/api/initmenu",
    {
      method: "GET",
      headers: {
        Accept: "application/json",
      },
    }
  );
  if (r.ok) {
    return r.json();
  }
  throw new Error("Cant get datas menu");
}
