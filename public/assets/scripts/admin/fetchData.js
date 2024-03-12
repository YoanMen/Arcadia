export { getHabitatsComment, getHabitatsCommentByName, getAnimalsDetail };

const csrf_token = document.head.querySelector(
  'meta[name="csrf-token"]'
).content;

async function getHabitatsComment(order = "ASC") {
  const r = await fetch("/public/api/habitats/comment", {
    method: "POST",
    headers: {
      order: order,
      "X-CSRF-TOKEN": csrf_token,
      Accept: "application/json",
    },
  });

  if (r.ok) {
    return r.json();
  }
}

async function getHabitatsCommentByName(text) {
  const r = await fetch("/public/api/habitats/commentbyname", {
    method: "POST",
    headers: {
      "X-CSRF-TOKEN": csrf_token,
      Accept: "application/json",
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ search: text }),
  });

  if (r.ok) {
    return r.json();
  }
}

async function getAnimalsDetail() {
  const r = await fetch("/public/api/habitats/report", {
    method: "POST",
    headers: {
      "X-CSRF-TOKEN": csrf_token,
      Accept: "application/json",
    },
  });

  if (r.ok) {
    return r.json();
  }
}
