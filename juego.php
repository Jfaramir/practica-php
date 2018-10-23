<?php
session_start();
//capturo los valores de los parámetros que me han sido pasados
//desde app.php
include ('misFunciones.php');
$vidas = $_POST['vidas'];
$correctas = $_POST['correctas'];
$tema = $_POST['tema'];

$mysqli = conectaBBDD();
$resultadoQuery = $mysqli -> query("SELECT * FROM preguntas WHERE tema ='$tema' ");
$numPreguntas = $resultadoQuery -> num_rows;
 
//declaro un array en php para guardar el resultado de la query
$listaPreguntas = array();

//cargo todas las filas del resultado de la query en el array
    for ($i = 0; $i < $numPreguntas; $i++){
        $r = $resultadoQuery -> fetch_array(); //leo una fila del resultado de la query
        $listaPreguntas[$i][0] = $r['numero'];
        $listaPreguntas[$i][1] = $r['enunciado']; 
        $listaPreguntas[$i][2] = $r['r1']; 
        $listaPreguntas[$i][3] = $r['r2']; 
        $listaPreguntas[$i][4] = $r['r3']; 
        $listaPreguntas[$i][5] = $r['r4']; 
        $listaPreguntas[$i][6] = $r['correcta'];
    }
    
 $preguntaActual = rand(0, $numPreguntas-1);

?>
<div>
    <p></p>
    <p><a  class="btn btn-block btn-dark disabled" >Demuestra que estás listo para la EVAU</a></p>
    <p><a  class="btn btn-block btn-dark disabled" ><?php echo $_SESSION['nombreUsuario']?></a></p>
    <p><a  class="btn btn-block btn-warning" onclick="volver();">Volver al Menú</a></p>
    
    <p><a id="sigue1" class="btn btn-block btn-primary" ><?php echo $tema;?></a></p>
    
        <div id="cajatiempo" style="height: 30px;" >
            <div id="tiempo" class="progress-bar progress-bar-striped bg-success" style="width: 0%;"></div>
        </div>
    <p></p>
    <p><a id="enunciado" class="btn btn-block btn-primary " ></a></p>
    
    <p><a id="r1"  class="btn btn-block btn-success " onclick="comprobadora(1);" ></a></p>
    <p><a id="r2"  class="btn btn-block btn-success " onclick="comprobadora(2);" ></a></p>
    <p><a id="r3"  class="btn btn-block btn-success " onclick="comprobadora(3);" ></a></p>
    <p><a id="r4"  class="btn btn-block btn-success " onclick="comprobadora(4);" ></a></p>
    <p><a id="correcta"  class="btn btn-block btn-success "></a></p>
</div>

<script>
    function volver(){
        $('#principal').load('app.php');
    }
    var progreso;
    var segundo = 0;
    //temporizador de la barra
    clearInterval(progreso);
    progreso = setInterval(function(){
        var caja = $("#cajatiempo");
        var tiempo = $("#tiempo");
        
        if (tiempo.width() >= caja.width()){
            tiempo.width(tiempo.width() - caja.width());
            sigue();
            segundo = "";
            
            
        }
        else{
            tiempo.width(tiempo.width() + caja.width()/10);
            segundo++;
        }
        //cambia el color de la barra dependiendo del segundo en que está
        if (segundo < 5){
            tiempo.removeClass("bg-warning").removeClass("bg-danger").addClass("bg-success");
        }
        else if (segundo < 8){
            tiempo.removeClass("bg-success").addClass("bg-warning");
        }
        else {
            tiempo.removeClass("bg-warning").addClass("bg-danger");
        }
        tiempo.text(segundo);
        
        
    }, 1000);
    
   
    //cargo el array php de preguntas en una variable javascript
    var listaPreguntas = <?php echo json_encode($listaPreguntas); ?>;
    //calculo un numero aleatorio
    var numeroPregunta =  Math.floor(Math.random() * listaPreguntas.length) ;
    //dibujo los textos en los botones correspondientes
    sigue(); 
    
     function sigue(){
    
    numeroPregunta =  Math.floor(Math.random() * listaPreguntas.length) ;
    $('#enunciado').text(listaPreguntas[numeroPregunta][1]);
    $('#r1').text(listaPreguntas[numeroPregunta][2]);
    $('#r2').text(listaPreguntas[numeroPregunta][3]);
    $('#r3').text(listaPreguntas[numeroPregunta][4]);
    $('#r4').text(listaPreguntas[numeroPregunta][5]);
    $('#correcta').text(listaPreguntas[numeroPregunta][6]);
 }
 
 function comprobadora(seguridad){
     
     var segur = listaPreguntas[numeroPregunta][6];

        if(segur == seguridad){
         sigue();
         tiempo.width(caja.width);
         segundo = "";
         
     }
    
 } 
 
 
</script>


<div class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Modal body text goes here.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>