<div class="container d-flex justify-content-center my-5">
    <form action="index.php?action=guardar" method="POST" class="card shadow-sm border-0 w-100" style="max-width: 600px;">

        <div class="card-header bg-white border-bottom-0 pt-4 px-4 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-secondary">Nueva Cita</h5>
            <button name="CANCELAR" class="btn btn-outline-secondary btn-sm">Volver</button>
        </div>

        <div id="camposFormulario" class="card-body p-4">

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="fechaInicio" class="form-label fw-semibold">Fecha inicio</label>
                    <input class="form-control <?php echo isset($aErrores['fechaInicio']) ? 'is-invalid' : ''; ?>"
                        type="date" id="fechaInicio" name="fechaInicio" value="<?php echo $aRespuestas['fechaInicio'] ?>">
                    <div class="invalid-feedback"><?php echo $aErrores['fechaInicio'] ?></div>
                </div>
                <div class="col-md-6">
                    <label for="horaInicio" class="form-label fw-semibold">Hora Inicio</label>
                    <input class="form-control <?php echo isset($aErrores['horaInicio']) ? 'is-invalid' : ''; ?>"
                        type="time" id="horaInicio" name="horaInicio" value="<?php echo $aRespuestas['horaInicio'] ?>">
                    <div class="invalid-feedback"><?php echo $aErrores['horaInicio'] ?></div>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="fechaFin" class="form-label fw-semibold">Fecha fin</label>
                    <input class="form-control <?php echo isset($aErrores['fechaFin']) ? 'is-invalid' : ''; ?>"
                        type="date" id="fechaFin" name="fechaFin" value="<?php echo $aRespuestas['fechaFin'] ?>">
                    <div class="invalid-feedback"><?php echo $aErrores['fechaFin'] ?></div>
                </div>
                <div class="col-md-6">
                    <label for="horaFin" class="form-label fw-semibold">Hora fin</label>
                    <input class="form-control <?php echo isset($aErrores['horaFin']) ? 'is-invalid' : ''; ?>"
                        type="time" id="horaFin" name="horaFin" value="<?php echo $aRespuestas['horaFin'] ?>">
                    <div class="invalid-feedback"><?php echo $aErrores['horaFin'] ?></div>
                </div>
            </div>

            <div class="mb-3">
                <label for="asunto" class="form-label fw-semibold">Asunto</label>
                <input class="form-control <?php echo isset($aErrores['asunto']) ? 'is-invalid' : ''; ?>"
                    type="text" id="asunto" name="asunto" placeholder="Motivo de la reunión" value="<?php echo $aRespuestas['asunto'] ?>">
                <div class="invalid-feedback"><?php echo $aErrores['asunto'] ?></div>
            </div>

            <div class="mb-3">
                <label for="observaciones" class="form-label fw-semibold">Observaciones</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"><?php echo $aRespuestas['observaciones'] ?></textarea>
                <?php if (!empty($aErrores['observaciones'])): ?>
                    <small class="text-danger d-block mt-1"><?php echo $aErrores['observaciones'] ?></small>
                <?php endif; ?>
            </div>

            <div class="mb-4 p-3 bg-light rounded border">
                <label class="form-label fw-bold d-block mb-2 text-primary">Participantes</label>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="alejandrohuerga" name="correos[]" value="alejandrodelahuerga@gmail.com">
                    <label class="form-check-label" for="alejandrohuerga">Alejandro De La Huerga</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="webqinamical" name="correos[]" value="webqinamical@gmail.com">
                    <label class="form-check-label" for="webqinamical">Webqinamical</label>
                </div>
                
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="alvarogargon" name="correos[]" value="alvaro.gargon.4@educa.jcyl.es">
                    <label class="form-check-label" for="alvarogargon">Alvaro Garcia</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="practicas" name="correos[]" value="practicasenlb@hotmail.com">
                    <label class="form-check-label" for="practicas">Practicas</label>
                </div>
                <div class="form-check border-top pt-2 mt-2">
                    <input class="form-check-input" type="checkbox" id="mailtrap" name="correos[]" value="alvar.sancristoball.9@gmail.com">
                    <label class="form-check-label" for="mailtrap">
                        Genuine Apple
                    </label>
                </div>
                <div class="form-check border-top pt-2 mt-2">
                    <input class="form-check-input" type="checkbox" id="mailtrap" name="correos[]" value="practicasweb@qinamical.com">
                    <label class="form-check-label" for="mailtrap">
                        SoGo
                    </label>
                </div>
            </div>
        </div>

        <div class="card-footer bg-white border-top-0 pb-4 px-4">
            <button type="submit" name="GUARDAR" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                Confirmar y Guardar Cita
            </button>
        </div>
    </form>
</div>