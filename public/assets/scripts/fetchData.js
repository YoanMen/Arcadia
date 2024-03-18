const csrf_token = document.head.querySelector(
  'meta[name="csrf-token"]'
).content;

export async function getDataMenu() {
  const r = await fetch("/public/api/initmenu", {
    method: "GET",
    headers: {
      Accept: "application/json",
    },
  });
  if (r.ok) {
    return r.json();
  }
  throw new Error("Cant get datas menu");
}
