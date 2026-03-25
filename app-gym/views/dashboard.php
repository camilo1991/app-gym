<div class="container mt-4">
    <div class="card-gym p-4 text-center">
        <h3>💪 Panel de Control: <?php echo $_SESSION['user_name']; ?></h3>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="metric-box">
                    <small>Peso Actual</small>
                    <h4><?php echo $metas['peso_actual']; ?> kg</h4>
                </div>
            </div>
            <div class="col-md-6">
                <div class="metric-box">
                    <small>Peso Objetivo</small>
                    <h4 class="text-success"><?php echo $metas['peso_ideal']; ?> kg</h4>
                </div>
            </div>
        </div>

        <div class="progress mt-3" style="height: 10px; background: #30363d;">
            <div class="progress-bar bg-success" style="width: 75%"></div>
        </div>
    </div>
</div>