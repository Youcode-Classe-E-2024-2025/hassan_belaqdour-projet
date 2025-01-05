// Graph Data using Chart.js
const ctx = document.getElementById("myChart").getContext("2d");

new Chart(ctx, {
  type: "line",
  data: {
    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
    datasets: [
      {
        label: "Revenue",
        data: [20000, 18000, 22000, 21000, 24000, 26000, 28000],
        borderColor: "#8b5cf6",
        borderWidth: 3,
        fill: true,
        backgroundColor: "rgba(139, 92, 246, 0.2)",
        tension: 0.4,
        pointBackgroundColor: "#8b5cf6",
        pointRadius: 5,
      },
    ],
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        display: false,
      },
    },
    scales: {
      y: {
        beginAtZero: false,
        ticks: {
          color: "white",
        },
      },
      x: {
        ticks: {
          color: "white",
        },
      },
    },
  },
});
