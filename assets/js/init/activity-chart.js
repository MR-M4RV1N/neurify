import Chart from 'chart.js/auto';

let activityChart = null;

export function initActivityChart() {
    const chartCanvas = document.getElementById('eventsChart');
    if (!chartCanvas) return;

    const profileId = chartCanvas.dataset.profileId;
    const year = chartCanvas.dataset.year;

    fetch(`/cpanel/progress/activity/api/statistics/${profileId}?year=${year}`)
        .then(response => response.json())
        .then(data => {

            const eventLabels = data.events.map(item => item.month);
            const eventValues = data.events.map(item => item.count);

            // 🔥 КЛЮЧЕВОЕ МЕСТО
            if (activityChart) {
                activityChart.destroy();
                activityChart = null;
            }

            activityChart = new Chart(chartCanvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: eventLabels,
                    datasets: [{
                        label: `Количество выполненных записей (${year})`,
                        data: eventValues,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
}