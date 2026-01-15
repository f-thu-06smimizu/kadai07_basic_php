/**
 * 個人別スコア分布グラフ（棒グラフ）の描画
 */
document.addEventListener("DOMContentLoaded", () => {
  const ctx = document.getElementById("scoreChart");
  if (!ctx) return;

  // HTMLのdata属性からPHPで出力したJSONデータを取得
  const labels = JSON.parse(ctx.dataset.labels);
  const scores = JSON.parse(ctx.dataset.scores);

  new Chart(ctx.getContext("2d"), {
    type: "bar", // 棒グラフ
    data: {
      labels: labels,
      datasets: [
        {
          label: "総合スコア",
          data: scores,
          backgroundColor: "rgba(0, 161, 154, 0.7)", // メインカラー
          borderColor: "#00a19a",
          borderWidth: 1,
          borderRadius: 4, // 棒の角を少し丸く
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          max: 100, // スコアは100点満点
          title: { display: true, text: "点数" },
        },
      },
      plugins: {
        legend: { display: false }, // 凡例は不要なので非表示
      },
    },
  });
});
