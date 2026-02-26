import Chart from 'chart.js/auto';

let smallStepsChart = null;

export function initSmallStepsChart() {
    const chartCanvas = document.getElementById('smallStepsChart');
    if (!chartCanvas) return;

    const range = chartCanvas.dataset.range || 'weeks';
    const year = chartCanvas.dataset.year;

    const weeks = JSON.parse(chartCanvas.dataset.weeks || '[]');
    const days  = JSON.parse(chartCanvas.dataset.days  || '[]');

    const points = (range === 'days') ? days : weeks;

    const labels = points.map(p => (range === 'days') ? p.day : p.week);
    const values = points.map(p => p.count);

    if (smallStepsChart) {
        smallStepsChart.destroy();
        smallStepsChart = null;
    }

    smallStepsChart = new Chart(chartCanvas.getContext('2d'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: (range === 'days')
                    ? `Small steps по дням (последние 12)`
                    : `Small steps по неделям (${year})`,
                data: values,
                borderWidth: 1,
                tension: 0.35,
                fill: true,
                borderColor: '#C18A44',
                backgroundColor: 'rgba(212, 161, 95, 0.45)',
                pointRadius: 3,
                pointHoverRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            scales: { y: { beginAtZero: true } }
        }
    });
}