export async function dashboardPage() {
  const r = await fetch("/public/dashboard", {
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
