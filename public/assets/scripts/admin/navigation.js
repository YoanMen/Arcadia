import { FlashMessage } from "../flashMessage.js";

const loading = `<div class="dashboard__loading loading">
                    <svg height='32px' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                      <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
                    </svg>
                 </div>`;

const content = document.getElementById("dashboard-content");
const dashboardBtn = document.getElementById("menu-dashboard");
const habitatBtn = document.getElementById("menu-habitat");
const animalBtn = document.getElementById("menu-animal");
const serviceBtn = document.getElementById("menu-service");
const userBtn = document.getElementById("menu-user");
const scheduleBtn = document.getElementById("menu-schedule");
const adviceBtn = document.getElementById("menu-advice");
const foodAnimalBtn = document.getElementById("menu-foodAnimal");
const habitatCommentBtn = document.getElementById("menu-habitatComment");
const reportAnimalBtn = document.getElementById("menu-report");
const showFoodAnimalBtn = document.getElementById("menu-showFoodAnimal");
init();

// get role of user and display corresponding page
async function init() {
  const r = await fetch("/public/api/role", {
    method: "GET",
    headers: {
      Accept: "application/json",
    },
  });

  const data = await r.json();

  if (r.ok) {
    switch (data.role) {
      case "admin":
        loadDashboard();
        break;
      case "employee":
        loadFoodAnimal();
        break;
      default:
        break;
    }
  }
}

// dashboard
if (dashboardBtn) {
  dashboardBtn.addEventListener("click", async (event) => {
    event.preventDefault();

    content.innerHTML = loading;

    loadDashboard();
  });
}

if (showFoodAnimalBtn) {
  showFoodAnimalBtn.addEventListener("click", async (event) => {
    event.preventDefault();
    content.innerHTML = loading;

    await fetch("/public/dashboard/food/show", {
      method: "GET",
      headers: {
        Accept: "text/html",
      },
    })
      .then((response) => {
        if (response.ok) {
          return response.text();
        }
      })
      .then((htmlContent) => {
        deleteScripts();
        const timestamp = new Date().getTime();
        content.innerHTML = htmlContent;

        const habitat = document.createElement("script");
        habitat.id = "js";
        habitat.src = `./assets/scripts/admin/foodAnimal.js?v=${timestamp}`;
        habitat.type = "module";
        document.body.appendChild(habitat);
      })
      .catch((error) => {
        new FlashMessage("error", error.message);
      });
  });
}

// habitat
if (habitatBtn) {
  habitatBtn.addEventListener("click", async (event) => {
    event.preventDefault();
    content.innerHTML = loading;

    await fetch("/public/dashboard/habitat", {
      method: "GET",
      headers: {
        Accept: "text/html",
      },
    })
      .then((response) => {
        if (response.ok) {
          return response.text();
        }
      })
      .then((htmlContent) => {
        deleteScripts();
        const timestamp = new Date().getTime();
        content.innerHTML = htmlContent;

        const habitat = document.createElement("script");
        habitat.id = "js";
        habitat.src = `./assets/scripts/admin/habitat.js?v=${timestamp}`;
        habitat.type = "module";
        document.body.appendChild(habitat);
      })
      .catch((error) => {
        new FlashMessage("error", error.message);
      });
  });
}

if (animalBtn) {
  animalBtn.addEventListener("click", async (event) => {
    event.preventDefault();

    await fetch("/public/dashboard/animal", {
      method: "GET",
      headers: {
        Accept: "text/html",
      },
    })
      .then((response) => {
        if (response.ok) {
          return response.text();
        }
      })
      .then((htmlContent) => {
        deleteScripts();
        const timestamp = new Date().getTime();

        content.innerHTML = htmlContent;

        const animal = document.createElement("script");
        animal.id = "js";
        animal.src = `./assets/scripts/admin/animal.js?v=${timestamp}`;
        animal.type = "module";

        document.body.appendChild(animal);
      });
  });
}

if (serviceBtn) {
  serviceBtn.addEventListener("click", async (event) => {
    event.preventDefault();

    loadService();
  });
}

if (userBtn) {
  userBtn.addEventListener("click", async (event) => {
    event.preventDefault();

    await fetch("/public/dashboard/user", {
      method: "GET",
      headers: {
        Accept: "text/html",
      },
    })
      .then((response) => {
        return response.text();
      })
      .then((htmlContent) => {
        deleteScripts();
        const timestamp = new Date().getTime();

        content.innerHTML = htmlContent;

        const service = document.createElement("script");
        service.id = "js";
        service.type = "module";
        service.src = `./assets/scripts/admin/user.js?v=${timestamp}`;

        document.body.appendChild(service);
      });
  });
}

if (scheduleBtn) {
  scheduleBtn.addEventListener("click", async (event) => {
    event.preventDefault();

    await fetch("/public/dashboard/schedule", {
      method: "GET",
      headers: {
        Accept: "text/html",
      },
    })
      .then((response) => {
        return response.text();
      })
      .then((htmlContent) => {
        deleteScripts();
        const timestamp = new Date().getTime();

        content.innerHTML = htmlContent;

        const schedule = document.createElement("script");
        schedule.id = "js";
        schedule.type = "module";

        schedule.src = `./assets/scripts/admin/schedule.js?v=${timestamp}`;

        document.body.appendChild(schedule);
      });
  });
}

if (adviceBtn) {
  adviceBtn.addEventListener("click", async (event) => {
    event.preventDefault();

    await fetch("/public/dashboard/advice", {
      method: "GET",
      headers: {
        Accept: "text/html",
      },
    })
      .then((response) => {
        return response.text();
      })
      .then((htmlContent) => {
        deleteScripts();
        const timestamp = new Date().getTime();

        content.innerHTML = htmlContent;

        const advice = document.createElement("script");
        advice.id = "js";
        advice.type = "module";
        advice.src = `./assets/scripts/admin/advice.js?v=${timestamp}`;

        document.body.appendChild(advice);
      });
  });
}

if (foodAnimalBtn) {
  foodAnimalBtn.addEventListener("click", async (event) => {
    event.preventDefault();

    loadFoodAnimal();
  });
}

if (habitatCommentBtn) {
  habitatCommentBtn.addEventListener("click", async (event) => {
    event.preventDefault();

    await fetch("/public/dashboard/habitat-comment", {
      method: "GET",
      headers: {
        Accept: "text/html",
      },
    })
      .then((response) => {
        return response.text();
      })
      .then((htmlContent) => {
        deleteScripts();
        const timestamp = new Date().getTime();

        content.innerHTML = htmlContent;

        const service = document.createElement("script");
        service.id = "js";
        service.type = "module";
        service.src = `./assets/scripts/admin/habitatComment.js?v=${timestamp}`;

        document.body.appendChild(service);
      });
  });
}

if (reportAnimalBtn) {
  reportAnimalBtn.addEventListener("click", async (event) => {
    event.preventDefault();

    await fetch("/public/dashboard/animal-report", {
      method: "GET",
      headers: {
        Accept: "text/html",
      },
    })
      .then((response) => {
        return response.text();
      })
      .then((htmlContent) => {
        deleteScripts();
        const timestamp = new Date().getTime();

        content.innerHTML = htmlContent;

        const service = document.createElement("script");
        service.id = "js";
        service.type = "module";
        service.src = `./assets/scripts/admin/animalReport.js?v=${timestamp}`;

        document.body.appendChild(service);
      });
  });
}

/*
 * delete old script to load script for new page
 */
function deleteScripts() {
  const scripts = document.querySelectorAll("#js");
  scripts.forEach((script) => {
    script.remove();
  });
}

async function loadService() {
  await fetch("/public/dashboard/service", {
    method: "GET",
    headers: {
      Accept: "text/html",
    },
  })
    .then((response) => {
      return response.text();
    })
    .then((htmlContent) => {
      deleteScripts();
      const timestamp = new Date().getTime();

      content.innerHTML = htmlContent;

      const service = document.createElement("script");
      service.id = "js";
      service.type = "module";
      service.src = `./assets/scripts/admin/service.js?v=${timestamp}`;

      document.body.appendChild(service);
    });
}

async function loadFoodAnimal() {
  await fetch("/public/dashboard/food", {
    method: "GET",
    headers: {
      Accept: "text/html",
    },
  })
    .then((response) => {
      return response.text();
    })
    .then((htmlContent) => {
      deleteScripts();
      const timestamp = new Date().getTime();

      content.innerHTML = htmlContent;

      const service = document.createElement("script");
      service.id = "js";
      service.type = "module";
      service.src = `./assets/scripts/admin/foodAnimal.js?v=${timestamp}`;

      document.body.appendChild(service);
    });
}

async function loadDashboard() {
  await fetch("/public/dashboard/dashboard", {
    method: "GET",
    headers: {
      Accept: "text/html",
    },
  })
    .then((response) => {
      if (response.ok) {
        return response.text();
      }
    })
    .then((htmlContent) => {
      content.innerHTML = htmlContent;
      deleteScripts();
      const timestamp = new Date().getTime();

      // create script with js id and add a version number to prevent cache.
      const habitatCommentScript = document.createElement("script");
      habitatCommentScript.id = "js";
      habitatCommentScript.src = `./assets/scripts/admin/habitatComment.js?v=${timestamp}`;
      habitatCommentScript.type = "module";
      document.body.appendChild(habitatCommentScript);

      const animalReportScript = document.createElement("script");
      animalReportScript.id = "js";
      animalReportScript.src = `./assets/scripts/admin/animalReport.js?v=${timestamp}`;
      animalReportScript.type = "module";
      document.body.appendChild(animalReportScript);
    })
    .catch((error) => {
      new FlashMessage("error", error.message);
    });
}
