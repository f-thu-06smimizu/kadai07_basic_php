// js/dashboard.js
// 画面の読み込みが完全に終わってから実行する
window.addEventListener("load", function () {
  const el = document.getElementById("evaluationChart");

  // 要素がない場合はエラーを出さずに終了
  if (!el) {
    console.warn("Canvas要素 'evaluationChart' が見つかりません。");
    return;
  }

  // Chartライブラリ自体が読み込まれているか確認
  if (typeof Chart === "undefined") {
    console.error(
      "Chart.jsライブラリが読み込まれていません。header.phpを確認してください。"
    );
    return;
  }

  const completed = parseInt(el.dataset.completed) || 0;
  const total = parseInt(el.dataset.total) || 0;

  new Chart(el.getContext("2d"), {
    type: "pie",
    data: {
      labels: ["完了", "未完了"],
      datasets: [
        {
          data: [completed, Math.max(0, total - completed)],
          backgroundColor: ["#00a19a", "#bdc3c7"],
          borderWidth: 1,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { position: "bottom" } },
    },
  });
});
