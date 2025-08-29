// Admin Dashboard Charts
document.addEventListener("DOMContentLoaded", function () {
    // Check if Chart.js is available
    if (typeof Chart === "undefined") {
        console.error("Chart.js is not loaded");
        return;
    }

    // Get chart data from the page
    const chartDataElement = document.getElementById("chart-data");
    if (!chartDataElement) {
        console.error("Chart data not found");
        return;
    }

    const chartData = JSON.parse(chartDataElement.textContent);
    console.log("Chart data loaded:", chartData);

    // Initialize all charts
    initializeCharts(chartData);
});

function initializeCharts(chartData) {
    Chart.defaults.font.family = "Inter, system-ui, sans-serif";
    Chart.defaults.color = "#6B7280";

    // Transaction Trends Chart
    const transactionCanvas = document.getElementById("transactionChart");
    if (transactionCanvas) {
        new Chart(transactionCanvas.getContext("2d"), {
            type: "line",
            data: {
                labels: chartData.months,
                datasets: [
                    {
                        label: "Transaksi",
                        data: chartData.transactionTrends,
                        borderColor: "rgb(59, 130, 246)",
                        backgroundColor: "rgba(59, 130, 246, 0.1)",
                        fill: true,
                        tension: 0.4,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            },
        });
    }

    // Report Status Chart
    const reportCanvas = document.getElementById("reportStatusChart");
    if (reportCanvas) {
        new Chart(reportCanvas.getContext("2d"), {
            type: "doughnut",
            data: {
                labels: ["Pending", "Approved", "Rejected"],
                datasets: [
                    {
                        data: [
                            chartData.reportStats.pending,
                            chartData.reportStats.approved,
                            chartData.reportStats.rejected,
                        ],
                        backgroundColor: [
                            "rgb(249, 115, 22)",
                            "rgb(34, 197, 94)",
                            "rgb(239, 68, 68)",
                        ],
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            },
        });
    }
}
