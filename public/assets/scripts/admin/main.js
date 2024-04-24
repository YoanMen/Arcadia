document.addEventListener("DOMContentLoaded", () => {
  const roles = document.querySelectorAll(".orderBy-js");
  const order = document.getElementById("order");

  const confirmBtn = document.querySelectorAll(".delete-js");

  confirmBtn.forEach((btn) => {
    btn.addEventListener("click", (event) => {
      event.preventDefault();
      if (confirm("Voulez-vous vraiment supprimé ?")) {
        const myForm = btn.closest("form");
        myForm.submit();
      }
    });
  });

  roles.forEach((role) => {
    role.addEventListener("click", (event) => {
      order.value = order.value == "asc" ? "desc" : "asc";
    });
  });
});
