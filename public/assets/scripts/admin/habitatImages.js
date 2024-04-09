const ImageInput = document.getElementById("image-input");
const addImageBtn = document.getElementById("add-image");
const csrf_token = document.getElementById("csrf_token").value;
const deleteBtn = document.querySelectorAll(".delete-img-js");

ImageInput.addEventListener("change", () => {
  addImageBtn.disabled = ImageInput.value.length === 0;
});

addImageBtn.addEventListener("click", async (event) => {
  event.preventDefault();
  const id = addImageBtn.dataset.habitatId;
  console.log(ImageInput.files[0]);

  const formData = new FormData();
  formData.append("id", id);
  formData.append("csrf", csrf_token);
  formData.append("file", ImageInput.files[0]);

  await fetch("/public/api/habitats/images", {
    headers: {
      Accept: "application/json",
    },
    method: "POST",
    body: formData,
  }).then(() => {
    location.reload();
  });
});

deleteBtn.forEach((button) => {
  button.addEventListener("click", async (event) => {
    event.preventDefault();
    const id = button.dataset.imageId;

    if (window.confirm("Voulez vous vraiment supprimer l'image ?")) {
      await fetch("/public/api/habitats/images", {
        headers: {
          Accept: "application/json",
        },
        method: "DELETE",
        body: JSON.stringify({
          id: id,
          csrf: csrf_token,
        }),
      }).then(() => {
        location.reload();
      });
    }
  });
});
