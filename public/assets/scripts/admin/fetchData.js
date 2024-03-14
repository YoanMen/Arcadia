export { getHabitatsComment, getAnimalsDetail };

const csrf_token = document.head.querySelector(
  'meta[name="csrf-token"]'
).content;

async function getHabitatsComment(search, order, orderBy) {
  const r = await fetch("/public/api/habitats/comment", {
    method: "POST",
    headers: {
      "X-CSRF-TOKEN": csrf_token,
      Accept: "application/json",
      "Content-type": "application/json",
    },
    body: JSON.stringify({ search: search, order: order, orderBy: orderBy }),
  });

  if (r.ok) {
    return r.json();
  }
}

async function getAnimalsDetail(search, date, order, orderBy) {
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
    }),
  });

  if (r.ok) {
    return r.json();
  }
}
