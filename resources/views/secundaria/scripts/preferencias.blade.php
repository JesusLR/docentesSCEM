
<script type="text/javascript">
    $(document).ready(function() {

        //OBTENER AULAS
        $.get(base_url+`/api/aulas/${ubicacion_id}`,function(res,sta){
            res.forEach(element => {
                $("#aula_id").append(`<option value=${element.id}>${element.aulaClave}</option>`);
            });
        });
        //OBTENER DEPARTAMENTOS
        $.get(base_url+`/api/departamentos/${ubicacion_id}`,function(res,sta){
            var perActual;
            res.forEach(element => {
                var selected = "";
                if(element.id == departamento_id){
                    selected = "selected";
                    perActual = element.perActual
                }
                $("#departamento_id").append(`<option value=${element.id} ${selected}>${element.depClave}-${element.depNombre}</option>`);
            });
            //OBTENER PERIODO CON DEPARTAMENTO
            $("#periodo_id").empty();
            $("#periodo_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
            $("#perFechaInicial").val('');
            $("#perFechaFinal").val('');
            Materialize.updateTextFields();
            $.get(base_url+`/secundaria_periodo/api/periodos/${departamento_id}`,function(res2,sta){
                var perSeleccionado;
                res2.forEach(element => {
                    if(element.id == perActual){
                        perSeleccionado = element.id;
                        $("#periodo_id").append(`<option value=${element.id} selected>${element.perNumero}-${element.perAnio}</option>`);
                    }else{
                        $("#periodo_id").append(`<option value=${element.id}>${element.perNumero}-${element.perAnio}</option>`);
                    }
                });
                //OBTENER FECHA INICIAL Y FINAL DEL PERIODO SELECCIONADO
                $.get(base_url+`/api/periodo/${perSeleccionado}`,function(res3,sta){
                    $("#perFechaInicial").val(res3.perFechaInicial);
                    $("#perFechaFinal").val(res3.perFechaFinal);
                    Materialize.updateTextFields();
                });
            });//TERMINA PERIODO
            //OBTENER ESCUELAS CON DEPARTAMENTO
            $.get(base_url+`/api/escuelas/${departamento_id}`,function(res,sta){
                res.forEach(element => {
                    var selected = "";
                    if(element.id == escuela_id){
                        selected = "selected";
                    }
                    $("#escuela_id").append(`<option value=${element.id} ${selected}>${element.escClave}-${element.escNombre}</option>`);
                });
                //OBTENER PROGRAMAS CON ESCUELA
                $.get(base_url+`/secundaria_programa/api/programas/${escuela_id}`,function(res,sta){
                    res.forEach(element => {
                        if(element.escuela_id == escuela_id){
                            $("#programa_id").append(`<option value=${element.id} selected>${element.progClave}-${element.progNombre}</option>`);
                        }else{
                            $("#programa_id").append(`<option value=${element.id}>${element.progClave}-${element.progNombre}</option>`);
                        }
                    });
                    //OBTENER PLAN CON PROGRAMA
                    programa_id = $("#programa_id").val();
                    if(programa_id != "" && programa_id != null){
                        $("#plan_id").empty();
                        $("#plan_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                        $.get(base_url+`/secundaria_plan/api/planes/${programa_id}`,function(res2,sta){
                            res2.forEach(element => {
                                if(element.programa_id == programa_id){
                                    $("#plan_id").append(`<option value=${element.id} selected>${element.planClave}</option>`);
                                }else{
                                    $("#plan_id").append(`<option value=${element.id}>${element.planClave}</option>`);
                                }
                                //OBTENER CGT CON PLAN Y PERIODO
                                plan_id = $("#plan_id").val();
                                periodo_id = $("#periodo_id").val();
                                if(periodo_id != "" && periodo_id != null){
                                    $("#cgt_id").empty();
                                    $("#cgt_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                                    $.get(base_url+`/secundaria_cgt/api/cgts/${plan_id}/${periodo_id}`,function(res3,sta){
                                        res3.forEach(element => {
                                            $("#cgt_id").append(`<option value=${element.id}>${element.cgtGradoSemestre}-${element.cgtGrupo}-${element.cgtTurno}</option>`);
                                        });
                                    });
                                }
                            });
                            plan_id = $("#plan_id").val();
                            periodo_id = $("#periodo_id").val();
                            if(plan_id != "" && plan_id != null){
                                // OBTENER MATERIA SEMESTRE Y SEMESTRE CGT
                                $.get(base_url+`/secundaria_plan/plan/semestre/${plan_id}`,function(res,sta){
                                    var numeroSemestres = res.planPeriodos;
                                    if (numeroSemestres == 0)
                                    {
                                        numeroSemestres = 9;
                                    }
                                    //for (i = 1; i <= res.planPeriodos; i++) {
                                    for (i = 1; i <= numeroSemestres; i++) {
                                        $("#matSemestre").append(`<option value="${i}">${i}</option>`);
                                        $("#cgtGradoSemestre").append(`<option value="${i}">${i}</option>`);
                                        $("#gpoSemestre").append(`<option value="${i}">${i}</option>`);
                                    }
                                });
                                //OBTENER MATERIAS CON CGT SELECCIONADO
                                cgt_id = $("#cgt_id").val();
                                if(cgt_id != "" && cgt_id != null){
                                    $("#materia_id").empty();
                                    $("#materia_id").append(`<option value="" selected disabled>SELECCIONE UNA OPCIÓN</option>`);
                                    $.get(base_url+`/api/materias/${cgt_id}`,function(res4,sta){
                                        res4.forEach(element => {
                                            $("#materia_id").append(`<option value=${element.id}>${element.matClave}-${element.matNombreCorto}</option>`);
                                        });
                                    });
                                }
                            }
                        });
                    }
                });//TERMINA PROGRAMAS
            });//TERMINA ESCUELAS
        });//TERMINA DEPARTAMENTOS
    });//TERMINA DOCUMENTO SCRIPT
</script>