import Chart from 'chart.js/auto';

let weeklyScoreChart = null;

export function initWeeklyScoreChart() {
    const canvas = document.getElementById('weeklyScoreChart');
    if (!canvas) return;

    const profileId = canvas.dataset.profileId;

    fetch(`/cpanel/progress/journal/api/weekly-score/${profileId}`)
        .then(r => r.json())
        .then(data => {
            const labels = data.weeks.map(i => i.label);
            const values = data.weeks.map(i => i.score);
            const labelScore = canvas.dataset.labelScore || 'Score';

            if (weeklyScoreChart) {
                weeklyScoreChart.destroy();
                weeklyScoreChart = null;
            }

            weeklyScoreChart = new Chart(canvas.getContext('2d'), {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: labelScore,
                        data: values,
                        borderWidth: 1,
                        borderColor: 'rgba(255, 140, 0, 1)',        // линия
                        pointBackgroundColor: 'rgba(255, 140, 0, 1)', // точки
                        pointBorderColor: 'rgba(255, 140, 0, 1)',
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMax: 60
                        }
                    }
                }
            });
        });
}