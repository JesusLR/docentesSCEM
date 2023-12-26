{{-- MODAL EQUIVALENTES --}}
<div id="modalHistorialPagosPrimaria" class="modal">
    <div class="modal-content">
        <div class="row">
            <div class="col s12">
                <h4>Historial de pagos</h4>
                <p>
                    Pagos recibidos hasta: {{$registroUltimoPago}}
                </p>
                <p class="modalNombres"></p>
            </div>
        </div>


        <div class="row">
            <div class="col s12">
                <nav class="nav-extended">
                    <div class="nav-content">
                        <ul class="tabs tabs-transparent">
                            <li class="tab col s3"><a href="#tab1">Pagos año actual</a></li>
                            <li class="tab col s3"><a href="#tab2">Historico de pagos</a></li>
                        </ul>
                    </div>
                </nav>
            </div>
            <div id="tab1" class="col s12">
                <table id="tbl-historial-pagos-primaria" class="responsive-table display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Concepto de pago</th>
                            <th>Importe</th>
                            <th>Referencia de pago</th>
                            <th>Fecha de pago</th>
                            <th>Curso</th>
                            <th>Comentario del pago</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div id="tab2" class="col s12">

                <table id="tbl-historial-pagos-alu-primaria" class="responsive-table display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Concepto de pago</th>
                            <th>Importe</th>
                            <th>Referencia de pago</th>
                            <th>Fecha de pago</th>
                            <th>Curso</th>
                            <th>Comentario del pago</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- <div class="preloader-modal">
            <div id="preloader-modal"></div>
        </div> --}}
    </div>
    <div class="modal-footer">
        <button type="button" class="modal-close waves-effect waves-green btn-flat">Cerrar</button>
    </div>
</div>
