export function setHabitatOption(habitats, data = "") {
  let content = "";
  habitats.forEach((habitat) => {
    content += `<option ${(data = habitat.name) ? "selected" : ""}   value="${
      habitat.id
    }">${habitat.name}</option>`;
  });

  return content;
}
