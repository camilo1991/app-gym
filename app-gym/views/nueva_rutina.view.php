<div class="card-stats">
    <h5>Agregar Ejercicio Manual 📝</h5>
    <form id="formManual">
        <select class="form-select bg-dark text-white border-secondary mb-2" id="m_ejercicio">
            <option>Press de Banca</option>
            <option>Sentadilla</option>
            <option>Peso Muerto</option>
            <option>Press Militar</option>
            <option>Otro...</option>
        </select>
        <div class="row g-2">
            <div class="col-4">
                <input type="number" id="m_peso" placeholder="lb" class="form-control bg-dark text-white border-secondary">
            </div>
            <div class="col-4">
                <input type="number" id="m_reps" placeholder="Reps" class="form-control bg-dark text-white border-secondary">
            </div>
            <div class="col-4">
                <button type="button" onclick="guardarManual()" class="btn btn-primary w-100">Add</button>
            </div>
        </div>
    </form>
</div>

<script>
function guardarManual() {
    const data = {
        ejercicio: document.getElementById('m_ejercicio').value,
        peso: document.getElementById('m_peso').value,
        reps: document.getElementById('m_reps').value
    };
    
    fetch('api.php?action=guardar_serie', {
        method: 'POST',
        body: JSON.stringify(data)
    }).then(r => alert('¡Serie agregada!'));
}
</script>