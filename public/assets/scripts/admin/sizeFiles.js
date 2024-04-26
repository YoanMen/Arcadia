document.addEventListener("DOMContentLoaded", () => {
  const addImageInput = document.getElementById("image-input");

  if (addImageInput) {
    const size = addImageInput.getAttribute("max-file-size");

    addImageInput.addEventListener("change", () => {
      if (addImageInput.files[0] && addImageInput.files[0].size > size) {
        alert(
          `L'image doit être au maximum de ${Math.round(
            size / (1024 * 1024)
          )} mb`
        );
        addImageInput.value = "";
      }
    });
  }
});
