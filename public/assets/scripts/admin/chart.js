document.addEventListener("DOMContentLoaded", () => {
  const hightClick = document.getElementById("hight-click").textContent;
  const bars = document.querySelectorAll(".chart__item");

  if (bars) {
    // for all bars set height to correspond with clicks
    bars.forEach((bar) => {
      bar.style.height = "0%";
      bar.style.transition = "height 0.5s ease-in-out";

      // timeout to play transition
      setTimeout(() => {
        click = bar.dataset.click;
        bar.style.height = (click / hightClick) * 100 + "%";
      }, 100);
    });
  }
});
