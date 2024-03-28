const csrf_token = document.head.querySelector(
  'meta[name="csrf-token"]'
).content;

const regexName = new RegExp(/[!@#$%^&*()_+\-=\[\]{};\'\\:"|,.<>\/?]+/);
const fileMaxSize = 1048576;
const fileTypes = ["image/jpeg", "image/png", "image/webp"];

const loading = `
                    <td>
                      <svg height='32px' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
                      </svg>
                    </td>
                  `;

export class Table {
  /**
   * fetching data
   * @url url to backend
   * @method method type
   * @params params send to backend
   */
  async fetchData(url, method, params = {}) {
    try {
      const r = await fetch("/public" + url, {
        method: method,
        headers: {
          "X-CSRF-TOKEN": csrf_token,
          Accept: "application/json",
          "Content-type": "application/json",
        },
        body: JSON.stringify({
          params,
        }),
      });

      const data = await r.json();

      if (r.ok) {
        return data;
      } else {
        throw new Error(data.error || "Erreur inattendue du serveur");
      }
    } catch (error) {
      throw new Error(error.message);
    }
  }

  /**
   * upload image to backend
   * @url string of url to send backend
   * @id id of table element
   * @file file uploaded
   */
  async uploadImage(url, id, file) {
    try {
      const formData = new FormData();
      formData.append("id", id);
      formData.append("file", file);

      const r = await fetch("/public/" + url, {
        method: "POST",
        headers: {
          "X-CSRF-TOKEN": csrf_token,
        },
        body: formData,
      });

      const data = await r.json();

      if (r.ok) {
        return data;
      } else {
        throw new Error(data.error || "Erreur inattendue du serveur");
      }
    } catch (error) {
      throw new Error(error.message);
    }
  }

  /**
   * show loading in tbody
   * @tbody remplace tbody content by loading
   */
  showLoading(tbody) {
    const trLoading = document.createElement("tr");
    trLoading.innerHTML = loading;
    trLoading.classList.add("loading");
    tbody.appendChild(trLoading);
  }

  /**
   * create li of image with path and id
   * @images need image data with path and id
   */
  insertImage(image) {
    return `  <li class="details__item">
                <label for="details__image">image</label>
                <div class="details__input-wrapper">
                  <img class="details__image" width="320px" src="${window.location.origin}/public/uploads/${image.path}">
                  <p class="details__input">${image.path}</p>
                  <button name="deleteImage" data-image-id='${image.id}' class="button button--cube button--red  max-width--mobile">
                    <svg height='32px' fill='white' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z" /></svg>
                  </button>
                </div>
              </li>
            `;
  }

  /**
   * create list of images of table
   * @images need image data with path and id
   */
  initImages(images) {
    if (images.data) {
      let content = "";
      images.data.forEach((image) => {
        content += this.insertImage(image);
      });

      return content;
    }
    return "";
  }

  /**
   * show delete dialog
   * @dialog dialog
   * @title title dialog
   * @paraph paraph dialog
   * @callback execute after accept
   */
  showDeleteDialog(dialog, title, paraph, callback) {
    dialog.innerHTML = `<h3>${title}</h3>
                               <p class="dialog--confirm__paraph">${paraph}</p>
                               <div class="dialog--confirm__buttons">
                                 <button name="dialog-cancel" class="button button--red">non</button>
                                 <button name="dialog-accept" class="button">oui</button>
                               </div>`;

    const close = dialog.querySelector('button[name="dialog-cancel"]');
    const accept = dialog.querySelector('button[name="dialog-accept"]');

    close.addEventListener("click", () => {
      dialog.close();
    });

    accept.addEventListener("click", () => {
      callback();
      dialog.close();
    });

    dialog.showModal();
  }

  verifyFileSize(file) {
    return file.size <= fileMaxSize;
  }

  verifyFileType(file) {
    return fileTypes.includes(file.type);
  }

  verifyName(name) {
    return regexName.exec(name);
  }
}
