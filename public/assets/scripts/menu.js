document.addEventListener("DOMContentLoaded", () => {
  const csrf_token = document.head.querySelector(
    'meta[name="csrf-token"]'
  ).content;

  const desktopMenu = document.querySelector(".desktop-menu--fixed");
  const menuBtn = document.querySelector(".mobile-menu__btn");
  const closeBtn = document.querySelector(".mobile-menu__btn-close");
  const tab = document.querySelector(".mobile-menu__tab");
  const habitatsMenu = document.getElementsByName("menu-habitats");
  const servicesMenu = document.getElementsByName("menu-services");

  setMenuType();

  async function getDataMenu() {
    const r = await fetch("/api/initmenu", {
      method: "GET",
      headers: {
        Accept: "application/json",
        "X-CSRF-TOKEN": csrf_token,
      },
    });
    if (r.ok) {
      return r.json();
    }
    throw new Error("Cant get datas menu");
  }

  // fetch data for services and habitats to show in the menu
  getDataMenu().then(async (data) => {
    let serviceList = ``;
    let habitatList = ``;

    data.services.forEach((service) => {
      const urlName = service.name.replaceAll(" ", "-").toLowerCase();
      serviceList += `  <li>
    <a href="/services/${urlName} ">
      ${service.name} 
    </a>
  </li>`;
    });

    data.habitats.forEach((habitat) => {
      const urlName = habitat.name.replaceAll(" ", "-").toLowerCase();
      habitatList += `  <li>
    <a href="/habitats/${urlName} ">
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
      window.location.pathname != "/"
    ) {
      desktopMenu.classList.remove("desktop-menu");
      desktopMenu.classList.add("desktop-menu--fixed");
    } else {
      desktopMenu.classList.remove("desktop-menu--fixed");
      desktopMenu.classList.add("desktop-menu");
    }
  }
});
