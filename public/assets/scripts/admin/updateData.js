const csrf_token = document.head.querySelector(
  'meta[name="csrf-token"]'
).content;

export async function updateHabitat(habitat) {
  const r = await fetch("/public/api/habitats", {
    method: "PUT",
    headers: {
      "X-CSRF-TOKEN": csrf_token,
      Accept: "application/json",
      "Content-type": "application/json",
    },
    body: JSON.stringify({
      name: habitat.name,
      description: habitat.description,
    }),
  });

  if (r.ok) {
    return r.json();
  }
}
