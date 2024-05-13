document.addEventListener("DOMContentLoaded", () => {
  const dateInput = document.getElementById("date");
  const timeInput = document.getElementById("time");

  const now = new Date();

  if (dateInput) {
    const year = now.getFullYear();
    let month = now.getMonth() + 1;
    let day = now.getDate();

    if (day < 10) {
      day = `0${day}`;
    }
    if (month < 10) {
      month = `0${month}`;
    }

    dateInput.value = `${year}-${month}-${day}`;
  }

  if (timeInput) {
    let hours = now.getHours();
    let minutes = now.getMinutes();

    if (hours < 10) {
      hours = `0${hours}`;
    }
    if (minutes < 10) {
      minutes = `0${minutes}`;
    }

    timeInput.value = `${hours}:${minutes}`;
  }
});
