const csrf_token = document.head.querySelector(
  'meta[name="csrf-token"]'
).content;

const loading = `
                    <td>
                      <svg height='32px' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12,4V2A10,10 0 0,0 2,12H4A8,8 0 0,1 12,4Z" />
                      </svg>
                    </td>
                  `;

// get data for table
export async function getDataTable(url, method, params = {}) {
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

  if (r.ok) {
    return r.json();
  }
}

// set image for details view
export function setImages(images) {
  if (images.data) {
    let content = "";
    images.data.forEach((image) => {
      content += `<li class="dialog__item">
                    <label for="habitatImage">image</label>
                    <div class="dialog__input-wrapper">
                      <img class="dialog__image" width="320px" src="${window.location.origin}/public/uploads/${image.path}">
                      <p class="dialog__input">${image.path}</p>
                      <button name="deleteImage" imageId='${image.id}' class="button button--cube button--red  max-width--mobile">
                        <svg height='32px' fill='white' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z" /></svg>
                      </button>
                    </div>
                  </li>`;
    });

    return content;
  }
  return "";
}

export function showLoading(body) {
  const trLoading = document.createElement("tr");
  trLoading.innerHTML = loading;
  trLoading.classList.add("loading");
  body.appendChild(trLoading);
}
