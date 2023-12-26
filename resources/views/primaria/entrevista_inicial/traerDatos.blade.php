<script type="text/javascript">

    $(document).ready(function() {

        function obtenerDatosAlumno(id) {
          

            $.get(base_url+`/primaria_entrevista_inicial/getDatosAlumno/${id}`, function(res,sta) {

                console.log(res)

                var fecha = new Date();
                var anoActual = fecha. getFullYear();

            
                res.forEach(element => {
                    $("#fechaNacimiento").val(element.perFechaNac);    
                    
                    var anioNacAlum = element.perFechaNac;
                    var elem = anioNacAlum.split('-');
                    var year = elem[0];

                    $("#edadAlumo").val(anoActual - year);
                    $("#municipioAlumno").val(element.munNombre);
                    $("#estadoAlumno").val(element.edoNombre);
                    $("#paisAlumno").val(element.paisNombre);
                });
            });
        }
        
        obtenerDatosAlumno($("#alumno_id").val())
        $("#alumno_id").change( event => {
            obtenerDatosAlumno(event.target.value)
        });
     });
</script>


{{-- validar maximo de caracteres para celulares  --}}


<script>
    var celularPadre=  document.getElementById('celularPadre');
    celularPadre.addEventListener('input',function(){
    if (this.value.length > 10) 
        this.value = this.value.slice(0,10); 
    })

    var celularMadre=  document.getElementById('celularMadre');
    celularMadre.addEventListener('input',function(){
    if (this.value.length > 10) 
        this.value = this.value.slice(0,10); 
    })
    
    var celularTutor=  document.getElementById('celularTutor');
    celularTutor.addEventListener('input',function(){
    if (this.value.length > 10) 
        this.value = this.value.slice(0,10); 
    })

    var celularAccidente=  document.getElementById('celularAccidente');
    celularAccidente.addEventListener('input',function(){
    if (this.value.length > 10) 
        this.value = this.value.slice(0,10); 
    })

    var celularReferencia1=  document.getElementById('celularReferencia1');
    celularReferencia1.addEventListener('input',function(){
    if (this.value.length > 10) 
        this.value = this.value.slice(0,10); 
    })

    var celularReferencia2=  document.getElementById('celularReferencia2');
    celularReferencia2.addEventListener('input',function(){
    if (this.value.length > 10) 
        this.value = this.value.slice(0,10); 
    })

    var celularReferencia3=  document.getElementById('celularReferencia3');
    celularReferencia3.addEventListener('input',function(){
    if (this.value.length > 10) 
        this.value = this.value.slice(0,10); 
    })
</script>