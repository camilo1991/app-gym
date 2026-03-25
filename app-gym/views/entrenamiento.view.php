<canvas id="gymChart"></canvas>

<script>
    const ctx = document.getElementById('gymChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: [<?php foreach($progreso as $p) echo "'".date('d/m', strtotime($p['fecha']))."',"; ?>],
            datasets: [{
                label: 'Progreso Real (lb)',
                data: [<?php foreach($progreso as $p) echo $p['peso'].","; ?>],
                borderColor: '#2ea043',
                backgroundColor: 'rgba(46, 160, 67, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            scales: { y: { beginAtZero: false, grid: { color: '#30363d' } } }
        }
    });
</script>