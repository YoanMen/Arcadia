const csrf_token = document.head.querySelector(
  'meta[name="csrf-token"]'
).content;

export async function getHabitatsComment(search, order, orderBy, count) {
  const r = await fetch("/public/api/habitats/comment", {
    method: "POST",
    headers: {
      "X-CSRF-TOKEN": csrf_token,
      Accept: "application/json",
      "Content-type": "application/json",
    },
    body: JSON.stringify({
      search: search,
      order: order,
      orderBy: orderBy,
      count: count,
    }),
  });

  if (r.ok) {
    return r.json();
  }
}

export async function getAnimalsDetail(search, date, order, orderBy, count) {
  const r = await fetch("/public/api/habitats/report", {
    method: "POST",
    headers: {
      "X-CSRF-TOKEN": csrf_token,
      Accept: "application/json",
      "Content-type": "application/json",
    },
    body: JSON.stringify({
      search: search,
      date: date,
      order: order,
      orderBy: orderBy,
      count: count,
    }),
  });

  if (r.ok) {
    return r.json();
  }
}

export async function getHabitats(search, order, orderBy, count) {
  const r = await fetch("/public/api/habitats", {
    method: "POST",
    headers: {
      "X-CSRF-TOKEN": csrf_token,
      Accept: "application/json",
      "Content-type": "application/json",
    },
    body: JSON.stringify({
      search: search,
      order: order,
      orderBy: orderBy,
      count: count,
    }),
  });

  if (r.ok) {
    return r.json();
  }
}

export async function getHabitatImages(id) {
  const r = await fetch("/public/api/habitats/images", {
    method: "POST",
    headers: {
      "X-CSRF-TOKEN": csrf_token,
      Accept: "application/json",
      "Content-type": "application/json",
    },
    body: JSON.stringify({ id: id }),
  });

  if (r.ok) {
    return r.json();
  }
}

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
