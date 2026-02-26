import Chart from 'chart.js/auto';

export function initGoalPercentCharts() {
    const canvases = document.querySelectorAll('.js-goal-percent-chart');
    if (!canvases.length) return;

    function getColorByPercent(percent) {
        if (percent < 30) return 'rgba(220, 53, 69, 1)';   // красный
        if (percent < 60) return 'rgba(255, 159, 64, 1)';  // оранжевый
        if (percent < 80) return 'rgba(255, 193, 7, 1)';   // жёлтый
        return 'rgba(40, 167, 69, 1)';                     // зелёный
    }

    canvases.forEach((canvas) => {
        const percent = parseInt(canvas.dataset.percent || '0', 10);
        const safe = Math.max(0, Math.min(100, percent));

        const mainColor = getColorByPercent(safe);

        // чтобы Turbo/повторный вызов не создавал график поверх
        if (canvas.__chart) {
            canvas.__chart.destroy();
            canvas.__chart = null;
        }

        canvas.__chart = new Chart(canvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [safe, 100 - safe],
                    backgroundColor: [
                        mainColor,
                        'rgba(220, 220, 220, 0.4)'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: false,
                maintainAspectRatio: false,
                animation: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false }
                }
            }
        });
    });
}