<script type="text/javascript">
  $(document).ready(function() {
      $("#tipoReporte").change(function(e) {
        e.preventDefault()

        if (e.target.value === "gradoMateria") {
          $(".tipo-grado-materia").show()
        }
        if (e.target.value === "paquete" || e.target.value === "") {
          $(".tipo-grado-materia").hide()
        }
      })
  });
</script>