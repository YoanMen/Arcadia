export class FlashMessage {
  type;
  text;

  constructor(type, text) {
    this.type = type;
    this.text = text;
    this.send();
  }

  send() {
    let container = document.querySelector(".alert__container");

    if (container == null) {
      document.body.insertAdjacentHTML(
        "afterbegin",
        "<div class='alert__container'></div>"
      );
      container = document.querySelector(".alert__container");
    }

    switch (this.type) {
      case "success":
        this.type = "success";
        break;
      case "error":
        this.type = "danger";
        break;
      default:
        this.type = "success";
        break;
    }

    const id = Math.floor(Math.random() * 100);

    container.innerHTML += `<div data-flash=${id} class="alert alert--${this.type}">
                                  <p>${this.text}</p>
                                  <button name="alert-button"><svg height='36px' fill='black' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                      <path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                                    </svg></button>
                                </div>`;

    const delay = setTimeout(() => {
      let element = document.querySelector(`[data-flash='${id}']`);

      element.classList.add("alert--dismiss");
      setTimeout(() => {
        element.remove();
        if (container.children.length === 0) {
          container.remove();
        }
      }, 500);
    }, 5000);

    const buttons = document.querySelectorAll('button[name="alert-button"]');

    buttons.forEach((button) => {
      button.addEventListener("click", () => {
        let element = document.querySelector(`[data-flash='${id}']`);

        button.parentElement.classList.add("alert--dismiss");

        clearTimeout(delay);
        setTimeout(() => {
          button.parentElement.remove();
          if (container.children.length === 0) {
            element.remove();
          }
        }, 500);
      });
    });
  }
}
