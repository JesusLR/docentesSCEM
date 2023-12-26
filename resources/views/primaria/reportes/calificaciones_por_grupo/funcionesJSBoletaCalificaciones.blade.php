<script>
    /*
 * Las siguientes funciones reciben como parámetro
 * un array de campos.
 */
function applyRequired(elements) {
    $.each(elements, function(key, value) {
        $('#' + value).attr('required', true);
    });
}

function unsetRequired(elements) {
    $.each(elements, function(key, value) {
        $('#' + value).attr('required', false);
    });
}

function setDisabled(elements) {
    $.each(elements, function(key, value) {
        $('#' + value).prop('disabled', true);
    });
} //setDisabled.

function unsetDisabled(elements) {
    $.each(elements, function(key, value) {
        $('#' + value).prop('disabled', false);
    });
} //unsetDisabled.

/*
 * Recibe un Objeto como parámetro.
 * Utilizado con Modelos de Laravel.
 *
 * Busca el campo correspondiente a cada atributo,
 * le asigna el valor correspondiente.
 */
function fillElements(object) {
    $.each(object, function(key, value) {
        var element = $('#' + key);
        element && element.val(value);
    });
    Materialize.updateTextFields();
}

/*
 * Recibe array de campos.
 */
function emptyElements(elements) {
    $.each(elements, function(key, value) {
        var element = $('#' + value);
        element.val('');
        Materialize.updateTextFields();
    });
}

/*
 * Resetear un select
 *
 * recibe el Id del elemento, sin el #.
 */
function resetSelect(selectId) {
    $('#' + selectId).empty().append(new Option('SELECCIONE UNA OPCIÓN', ''));
    $('#' + selectId).trigger('change');
}

/**
 * Verifica si el select tiene data-value especificado,
 * si es así, selecciona la <option> que tenga ese value.
 *
 * recibe attr id, sin el #.
 */
function apply_data_to_select(select_id, data_value, default_value = null) {
    var select = $('#' + select_id);
    var data = select.data(data_value) || default_value;
    data && select.val(data).select2();

    return select;
}

/*
 * Recibe:
 * array elements = Todos los campos involucrados.
 * array elemRequired = Solo campos Requeridos.
 *
 * Si algún campo de elements fue llenado,
 * se aplica atributo "required" a elemRequired.
 *
 * Si ningún elements tiene valor,
 * No aplica "required".
 */
function if_haveValue_setRequired(elements, elemRequired) {
    var filled = 0;
    $.each(elements, function(key, value) {
        $('#' + value).val() && filled++;
    });

    (filled > 0) ? applyRequired(elemRequired): unsetRequired(elemRequired);
}

/*
 * Recibe un array de campos.
 * crea un objeto que contiene los valores de cada campo.
 *
 * ejemplo de uso: Mandar datos de varios campos a un Ajax Request.
 */
function objectBy(elements) {
    var obj = {};
    $.each(elements, function(key, value) {
        obj[value] = $('#' + value).val();
    });
    return obj;
}

/*
 * Recibe un objeto.
 * las claves son los #ids de los elementos.
 * los valores son el nombre visual del campo.
 *
 * Devuelve un objeto con los campos vacíos.
 */
function validate_formFields(elements) {
    var emptyFields = {};
    $.each(elements, function(key, value) {
        var element = $('#' + key);
        if (!element.val()) {
            emptyFields[key] = value;
        }
    });
    return emptyFields;
}

/*
 * Recibe un objeto.
 * las claves son los #ids de los elementos.
 * los valores son el nombre visual del campo.
 *
 * muestra una alerta con los campos "faltantes".
 */
function showRequiredFields(elements) {
    var fields = '\n ';
    $.each(elements, function(key, value) {
        fields = fields + value + ' \n ';
    });

    swal({
        type: 'warning',
        title: 'Campos requeridos',
        text: 'Para proceder, necesita llenar los siguientes campos: \n' + fields
    });
}

/**
 * Esta función impedirá que se ingresen caracteres especiales en el input_id.
 */
function avoidSpecialCharacters(input_id) {
    $('#' + input_id).on('keypress', function(e) {
        let regex = new RegExp("([a-zA-ZÄËÏÖÜ\u00f1\u00d1 ])");
        let char = String.fromCharCode(e.keyCode);
        if (!regex.test(char)) {
            e.preventDefault();
        }
    })
}

/* #########################################################################
 * 				AJAX PARA SELECTS COMUNES EN LAS VISTAS.
 *
 * - targetSelect = el id del select que se desea modificar, sin el #.
 * - val = el select.val() deseado. (puede ser nulo).
 * - dataName = por defecto es "modelo-id" pero puede especificarse.
 * ----------------------------------------------------------------------- */

function getDepartamentos(ubicacion_id, targetSelect = 'departamento_id', val = null, dataName = null) {

    var select = $('#' + targetSelect);
    var current_data = dataName || 'departamento-id';
    var current_value = val || select.data(current_data);
    //select.empty().append(new Option('SELECCIONE UNA OPCIÓN', ''));
    select.empty();
    $.ajax({
        type: 'GET',
        url: base_url + '/primaria_grupo/api/departamentos/' + ubicacion_id,
        dataType: 'json',
        data: { ubicacion_id: ubicacion_id },
        success: function(departamentos) {
            if (departamentos) {
                $.each(departamentos, function(key, value) {
                    select.append(new Option(value.depClave + '-' + value.depNombre, value.id));
                    (value.id == current_value) && select.val(value.id);
                });
                select.trigger('change');
                select.trigger('click');
            }
        },
        error: function(Xhr, textMessage, errorMessage) {
            console.log(errorMessage);
        }
    });
} //getDepartamentos.

function getDepartamentosListaCompleta(ubicacion_id, targetSelect = 'departamento_id', val = null, dataName = null) {

    var select = $('#' + targetSelect);
    var current_data = dataName || 'departamento-id';
    var current_value = val || select.data(current_data);
    select.empty().append(new Option('SELECCIONE UNA OPCIÓN', ''));
    $.ajax({
        type: 'GET',
        url: base_url + '/api/get_departamentos_lista_completa/' + ubicacion_id,
        dataType: 'json',
        data: { ubicacion_id: ubicacion_id },
        success: function(departamentos) {
            if (departamentos) {
                $.each(departamentos, function(key, value) {
                    select.append(new Option(value.depClave + '-' + value.depNombre, value.id));
                    (value.id == current_value) && select.val(value.id);
                });
                select.trigger('change');
                select.trigger('click');
            }
        },
        error: function(Xhr, textMessage, errorMessage) {
            console.log(errorMessage);
        }
    });
} //getDepartamentos.

function getDepartamentos_claves(ubicacion_id, targetSelect, val = null, dataName = null) {
    var select = $('#' + targetSelect);
    var current_data = dataName || 'departamento-id';
    var current_value = val || select.data(current_data);
    select.empty().append(new Option('SELECCIONE UNA OPCIÓN', ''));
    $.ajax({
        type: 'GET',
        url: base_url + '/api/departamentos/' + ubicacion_id,
        dataType: 'json',
        data: { ubicacion_id: ubicacion_id },
        success: function(departamentos) {
            if (departamentos) {
                $.each(departamentos, function(key, value) {
                    select.append(new Option(value.depClave, value.id));
                    (value.id == current_value) && select.val(value.id);
                });
                select.trigger('change');
                select.trigger('click');
            }
        },
        error: function(Xhr, textStatus, errorMessage) {
            console.log(errorMessage);
        }
    });
} //getDepartamentos_claves.

function getPeriodos(departamento_id, targetSelect = 'periodo_id', val = null, dataName = null) {
    var select = $('#' + targetSelect);
    var current_data = dataName || 'periodo-id';
    var current_value = val || select.data(current_data);
    select.empty().append(new Option('SELECCIONE UNA OPCIÓN', ''));
    $.ajax({
        type: 'GET',
        url: base_url + '/primaria_periodo/api/periodos/' + departamento_id,
        dataType: 'json',
        data: { departamento_id: departamento_id },
        success: function(periodos) {
            if (periodos) {
                $.each(periodos, function(key, value) {
                    select.append(new Option(value.perNumero + '-' + value.perAnioPago, value.id));
                    (value.perAnioPago == current_value) && select.val(value.perAnioPago);
                });
                select.trigger('change');
                select.trigger('click');
            }
        },
        error: function(Xhr, textMessage, errorMessage) {
            console.log(errorMessage);
        }
    });
} //getPeriodos.

function getPeriodos2(departamento_id, targetSelect = 'periodo_id', val = null, dataName = null) {
    var select = $('#' + targetSelect);
    var current_data = dataName || 'periodo-id';
    var current_value = val || select.data(current_data);
    select.empty().append(new Option('SELECCIONE UNA OPCIÓN', ''));
    $.ajax({
        type: 'GET',
        url: base_url + '/primaria_periodo/api/periodos/' + departamento_id,
        dataType: 'json',
        data: { departamento_id: departamento_id },
        success: function(periodos) {
            if (periodos) {
                $.each(periodos, function(key, value) {
                    select.append(new Option(value.perNumero + '-' + value.perAnioPago, value.perAnioPago));
                    (value.perAnioPago == current_value) && select.val(value.perAnioPago);
                });
                select.trigger('change');
                select.trigger('click');
            }
        },
        error: function(Xhr, textMessage, errorMessage) {
            console.log(errorMessage);
        }
    });
} //getPeriodos.

/*
 * Creado para traer perAnios o perNumeros distintos. (param: field).
 * Por defecto trae perNumero.
 */
function getPeriodos_field(departamento_id, targetSelect, val = null, dataName = null, field = 'perNumero') {
    var select = $('#' + targetSelect);
    var current_data = dataName || 'periodo-field-value';
    var current_value = val || select.data(current_data);
    select.empty().append(new Option('SELECCIONE UNA OPCIÓN', ''));
    $.ajax({
        type: 'GET',
        url: base_url + '/primaria_periodo/api/periodos/' + departamento_id,
        dataType: 'json',
        data: { departamento_id: departamento_id, 'field': field },
        success: function(data) {
            if (data) {
                $.each(data, function(key, value) {
                    select.append(new Option(value, value));
                    (value == current_value) && select.val(value);
                });
                select.trigger('change');
                select.trigger('click');
            }
        },
        error: function(Xhr, textStatus, errorMessage) {
            console.log(errorMessage);
        }
    });
} //getPeriodos_field.

/*
 * Busca los periodos que comienzan después de la fecha actual.
 * Si desea buscar en una fecha específica, puede ponerla como 5to. parámetro.
 * El formato de fecha debe ser 'Y-m-d'
 */
function getPeriodos_afterDate(departamento_id, targetSelect, val = null, dataName = null, fecha = null) {
    var select = $('#' + targetSelect);
    var current_data = dataName || 'periodo-id';
    var current_value = val || select.data(current_data);
    select.empty().append(new Option('SELECCIONE UNA OPCIÓN', ''));
    $.ajax({
        type: 'GET',
        url: base_url + '/primaria_periodo/api/periodo/' + departamento_id + '/posteriores',
        dataType: 'json',
        data: { departamento_id: departamento_id, fecha: fecha },
        success: function(periodos) {
            if (periodos) {
                $.each(periodos, function(key, value) {
                    select.append(new Option(value.perNumero + '-' + value.perAnio, value.id));
                    (value.id == current_value) && select.val(value.id);
                });
                select.trigger('change');
                select.trigger('click');
            }
        },
        error: function(Xhr, textMessage, errorMessage) {
            console.log(errorMessage);
        }
    });
} //getPeriodos_afterDate.

function getEscuelas(departamento_id, targetSelect = 'escuela_id', val = null, dataName = null) {
    var select = $('#' + targetSelect);
    var current_data = dataName || 'escuela-id';
    var current_value = val || select.data(current_data);
    //select.empty().append(new Option('SELECCIONE UNA OPCIÓN', ''));
    select.empty();
    $.ajax({
        type: 'GET',
        url: base_url + '/primaria_grupo/api/escuelas/' + departamento_id,
        dataType: 'json',
        data: { departamento_id: departamento_id },
        success: function(escuelas) {
            if (escuelas) {
                $.each(escuelas, function(key, value) {
                    select.append(new Option(value.escClave + '-' + value.escNombre, value.id));
                    (value.id == current_value) && select.val(value.id);
                });
                select.trigger('change');
                select.trigger('click');
            }
        },
        error: function(Xhr, textMessage, errorMessage) {
            console.log(errorMessage);
        }
    });
} //getEscuelas.

function getEscuelasListaCompleta(departamento_id, targetSelect = 'escuela_id', val = null, dataName = null) {
    var select = $('#' + targetSelect);
    var current_data = dataName || 'escuela-id';
    var current_value = val || select.data(current_data);
    select.empty().append(new Option('SELECCIONE UNA OPCIÓN', ''));
    $.ajax({
        type: 'GET',
        url: base_url + '/api/get_escuelas_lista_completa/' + departamento_id,
        dataType: 'json',
        data: { departamento_id: departamento_id },
        success: function(escuelas) {
            if (escuelas) {
                $.each(escuelas, function(key, value) {
                    select.append(new Option(value.escClave + '-' + value.escNombre, value.id));
                    (value.id == current_value) && select.val(value.id);
                });
                select.trigger('change');
                select.trigger('click');
            }
        },
        error: function(Xhr, textMessage, errorMessage) {
            console.log(errorMessage);
        }
    });
} //getEscuelasListaCompleta.

function getProgramas(escuela_id, targetSelect = 'programa_id', val = null, dataName = null) {
    var select = $('#' + targetSelect);
    var current_data = dataName || 'programa-id';
    var current_value = val || select.data(current_data);
    select.empty().append(new Option('SELECCIONE UNA OPCIÓN', ''));
    $.ajax({
        type: 'GET',
        url: base_url + '/primaria_programa/api/programas/' + escuela_id,
        dataType: 'json',
        data: { escuela_id: escuela_id },
        success: function(programas) {
            if (programas) {
                $.each(programas, function(key, value) {
                    select.append(new Option(value.progClave + '-' + value.progNombre, value.id));
                    (value.id == current_value) && select.val(value.id);
                });
                select.trigger('change');
                select.trigger('click');
            }
        },
        error: function(Xhr, textMessage, errorMessage) {
            console.log(errorMessage);
        }
    });
} //getProgramas.

function getPlanes(programa_id, targetSelect = 'plan_id', val = null, dataName = null) {
    var select = $('#' + targetSelect);
    var current_data = dataName || 'plan-id';
    var current_value = val || select.data(current_data);
    select.empty().append(new Option('SELECCIONE UNA OPCIÓN', ''));
    $.ajax({
        type: 'GET',
        url: base_url + '/api/planes/' + programa_id,
        dataType: 'json',
        data: { programa_id: programa_id },
        success: function(planes) {
            if (planes) {
                $.each(planes, function(key, value) {
                    select.append(new Option(value.planClave, value.id));
                    (value.id == current_value) && select.val(value.id);
                });
                select.trigger('change');
                select.trigger('click');
            }
        },
        error: function(Xhr, textMessage, errorMessage) {
            console.log(errorMessage);
        }
    });
} //getPlanes.

function getSemestres(plan_id, targetSelect = 'cgtGradoSemestre', val = null, dataName = null) {
    var select = $('#' + targetSelect);
    var current_data = dataName || 'cgt-grado';
    var current_value = val || select.data(current_data);
    select.empty().append(new Option('SELECCIONE UNA OPCIÓN', ''));
    $.ajax({
        type: 'GET',
        url: base_url + '/primaria_plan/plan/semestre/' + plan_id,
        dataType: 'json',
        data: { plan_id: plan_id },
        success: function(plan) {
            if (plan) {
                //PARA PRIMARIA SON 6 GRADOS
                //for (let i = 1; i <= plan.planPeriodos; i++) {
                for (let i = 1; i <= 6; i++) {
                    select.append(new Option(i, i));
                    (i == current_value) && select.val(i);
                }
                select.trigger('change');
                select.trigger('click');
            }
        },
        error: function(Xhr, textStatus, errorMessage) {
            console.log(Xhr, errorMessage);
        }
    });
} //getSemestres

function getMaterias(plan_id, targetSelect = 'materia_id', val = null, dataName = null) {
    var select = $('#' + targetSelect);
    var current_data = dataName || 'materia-id';
    var current_value = val || select.data(current_data);
    select.empty().append(new Option('SELECCIONE UNA OPCIÓN', ''));
    $.ajax({
        type: 'GET',
        url: base_url + '/primaria_materia/getMateriasByPlan/' + plan_id,
        dataType: 'json',
        data: { plan_id: plan_id },
        success: function(materias) {
            if (materias) {
                $.each(materias, function(key, value) {
                    select.append(new Option(value.matClave + ' - ' + value.matNombre, value.id));
                    (value.id == current_value) && select.val(value.id);
                });
                select.trigger('change');
                select.trigger('click');
            }
        },
        error: function(Xhr, textMessage, errorMessage) {
            console.log(errorMessage);
        }
    });
}

function getCgts_plan_periodo(plan_id, periodo_id, targetSelect, val = null, dataName = null) {
    var select = $('#' + targetSelect);
    current_data = dataName || 'cgt-id';
    current_value = val || select.data(current_data);
    select.empty().append(new Option('SELECCIONE UNA OPCIÓN', ''));
    $.ajax({
        type: 'GET',
        url: base_url + '/api/cgts/' + plan_id + '/' + periodo_id,
        dataType: 'json',
        data: { plan_id: plan_id, periodo_id, periodo_id },
        success: function(cgts) {
            if (cgts) {
                $.each(cgts, function(key, value) {
                    select.append(new Option(value.cgtGradoSemestre + '-' + value.cgtGrupo + '-' + value.cgtTurno, value.id));
                    (value.id == current_value) && select.val(value.id);
                });
                select.trigger('change');
                select.trigger('click');
            }
        },
        error: function(Xhr, textMessage, errorMessage) {
            console.log(errorMessage);
        }
    });
} //getCgts.

function getEstados(pais_id, targetSelect = 'estado_id', val = null, dataName = null) {
    var select = $('#' + targetSelect);
    var current_data = dataName || 'estado-id';
    var current_value = val || select.data(current_data);
    select.empty().append(new Option('SELECCIONE UNA OPCIÓN', ''));
    $.ajax({
        type: 'GET',
        url: base_url + '/api/estados/' + pais_id,
        dataType: 'json',
        data: { pais_id: pais_id },
        success: function(estados) {
            if (estados) {
                $.each(estados, function(key, value) {
                    select.append(new Option(value.edoNombre, value.id));
                    (value.id == current_value) && select.val(value.id);
                });
                select.trigger('change');
                select.trigger('click');
            }
        },
        error: function(Xhr, textMessage, errorMessage) {
            console.log(errorMessage);
        }
    });
} //getEstados.

function getMunicipios(estado_id, targetSelect = 'municipio_id', val = null, dataName = null) {
    var select = $('#' + targetSelect);
    var current_data = dataName || 'municipio-id';
    var current_value = val || select.data(current_data);
    select.empty().append(new Option('SELECCIONE UNA OPCIÓN', ''));
    $.ajax({
        type: 'GET',
        url: base_url + '/api/municipios/' + estado_id,
        dataType: 'json',
        data: { estado_id: estado_id },
        success: function(municipios) {
            if (municipios) {
                $.each(municipios, function(key, value) {
                    select.append(new Option(value.munNombre, value.id));
                    (value.id == current_value) && select.val(value.id);
                });
                select.trigger('change');
                select.trigger('click');
            }
        },
        error: function(Xhr, textMessage, errorMessage) {
            console.log(errorMessage);
        }
    });
} //getMunicipios.

function getPreparatorias(municipio_id, targetSelect, val = null, dataName = null) {
    var select = $('#' + targetSelect);
    var current_data = dataName || 'preparatoria-id';
    var current_value = val || select.data(current_data);
    select.empty().append(new Option('SELECCIONE UNA OPCIÓN', ''));
    $.ajax({
        type: 'GET',
        url: base_url + '/api/preparatoriaProcedencia/' + municipio_id,
        dataType: 'json',
        data: { municipio_id: municipio_id },
        success: function(preparatorias) {
            if (preparatorias) {
                $.each(preparatorias, function(key, value) {
                    select.append(new Option(value.prepNombre, value.id));
                    (value.id == current_value) && select.val(value.id);
                });
                select.trigger('change');
                select.trigger('click');
            }
        },
        error: function(Xhr, textMessage, errorMessage) {
            console.log(errorMessage);
        }
    });
} //getPreparatorias.


/* #########################################################################
 * 				Otras funciones AJAX Request
 * ----------------------------------------------------------------------- */

//Busca la fecha inicial y final de un periodo y las ubica en los campos específicos.
function periodo_fechasInicioFin(periodo_id, selectInicial = null, selectFinal = null) {
    var select1 = selectInicial || 'perFechaInicial';
    var select2 = selectFinal || 'perFechaFinal';
    $.ajax({
        type: 'GET',
        url: base_url + '/primaria_periodo/api/periodo/' + periodo_id,
        dataType: 'json',
        data: { periodo_id: periodo_id },
        success: function(periodo) {
            if (periodo) {
                $('#' + select1).val(periodo.perFechaInicial);
                $('#' + select2).val(periodo.perFechaFinal);
                Materialize.updateTextFields();
            }
        },
        error: function(Xhr, textMessage, errorMessage) {
            console.log(errorMessage);
        }
    });
}



/* ##########################################################################
 * 				MANEJO DE JSON RESPONSES.
 * -------------------------------------------------------------------------- */

/*
 * Recibe un objecto (responseJSON óptimamente).
 * Muestra alerta de errores del Laravel Request Validator.
 */
function showValidatorErrorsJSON(responseJsonObject) {
    var fields = '\n ';
    $.each(responseJsonObject, function(key, value) {
        fields = fields + value + ' \n';
    });

    swal({
        type: 'warning',
        title: 'Verificar datos.',
        text: fields +
            '\n Revisar si ingresó los datos correctamente.'
    });
}
</script>
