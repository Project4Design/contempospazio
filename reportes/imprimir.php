<?
require_once '../config/config.php';
require_once '../vendor/mpdf/mpdf.php';

class Pdf_casos{

  public function solicitante($caso)
  {
    $query = Query::prun("SELECT c.*,s.*,n.* FROM casos AS c
                              INNER JOIN solicitantes AS s ON s.id_caso = c.id_caso
                              INNER JOIN notificados AS n ON n.id_caso = c.id_caso
                              WHERE c.id_caso = ?",array("i",$caso));

    $numero = 1;
    if($query->result->num_rows>0){
      $datos = (object) $query->result->fetch_array(MYSQLI_ASSOC);

      $numero = $datos->caso_numero;

      $trabaja  = ($datos->soli_trabaja)?'Sí':'No';
      $trabaja2 = ($datos->noti_trabaja)?'Sí':'No';
      if($datos->caso_motivo==5){
        $motivo = "<div class=\"col-2\">Otros: </div><div class=\"col-6 under\">&nbsp;&nbsp;{$datos->caso_motivo_otro}&nbsp;&nbsp;</div>";
      }else{
        switch ($datos->caso_motivo) {
          case 1: $mt = "Obligacion de Manutención"; break; case 2: $mt = "Régimen de Convivencia Familiar"; break; case 3: $mt = "Ambos Procedimientos"; break; case 4: $mt = "Responsabilidad de Crianza-custodia"; break; case 5: $mt = "Otro"; break;
        }

        $motivo = "<div class=\"col-8 under\">{$mt}</div>";
      }

      $cuerpo ="
          <div class=\"col-12 text-center\">
            REPUBLICA BOLIVARIANA DE VENEZUELA<br>ALCALDIA DEL MUNICIPIO SUCRE EDO. ARAGUA<br>DEFENSORIA MUNICIPAL DE NIÑOS,NIÑAS Y ADOLESCENTES<br>DEFMNNA
          </div><br><br><br>
          <div class=\"col-6 b\">
            <p><span class=\"under\">HOJA DE REGISTRO DE CASO</span></p>
          </div>
          <div class=\"col-6\">
            <p class=\"text-right b\">N° de Caso: <span class=\"under\">&nbsp;&nbsp;&nbsp; {$datos->caso_numero} &nbsp;&nbsp;&nbsp;</span></p>
          </div>
          <div class=\"col-12\">
            <p class=\"b\">1.- DATOS DEL SOLICITANTE DEL SERVICIO</p>
          </div>
          <div class=\"col-12\" style=\"padding:0\">
            <div class=\"col-3\">Nombres y Apellidos: </div><div class=\"col-9 under\">{$datos->soli_nombre}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-2\">C.I.V- </div><div class=\"col-3 under\">{$datos->soli_cedula}&nbsp;</div>
            <div class=\"col-2\">Edad: </div><div class=\"col-1 under\">{$datos->soli_edad}&nbsp;</div>
            <div class=\"col-2\">Estado Civil: </div><div class=\"col-2 under\">{$datos->soli_estado_civil}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-2\">Domicilio: </div><div class=\"col-10 under\">{$datos->soli_domicilio}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-2\">Municipio: </div><div class=\"col-5 under\">{$datos->soli_municipio}&nbsp;</div>
            <div class=\"col-2\">Estado: </div><div class=\"col-3 under\">{$datos->soli_estado}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-3\">Profesión u Oficio: </div><div class=\"col-8 under\">{$datos->soli_profesion}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-2\">Trabaja: </div><div class=\"col-1 under\">{$trabaja}</div>
            <div class=\"col-2\">Donde: </div><div class=\"col-7 under\">{$datos->soli_tdonde}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-2\">Dirección: </div><div class=\"col-10 under\">{$datos->soli_direccion}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-4\">Teléfono donde localizarlo(a): </div><div class=\"col-8 under\">{$datos->soli_telefono}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-3\">Correo electrónico: </div><div class=\"col-9 under\">{$datos->soli_email}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-4 b\">2.- Motivo de la Consulta: </div>{$motivo}
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-5 b\">3.- Relación Sucinta de los Hechos:</div> <div class=\"col-7 under\">{$datos->caso_hechos}&nbsp;</span>
          </div>
          <div class=\"col-12\">
            <p class=\"b\">4.- DATOS APORTADOS DE LA PERSONA A SER NOTIFICADA</p>
          </div>
          <div class=\"col-12\" style=\"padding:0\">
            <div class=\"col-3\">Nombres y Apellidos: </div><div class=\"col-9 under\">{$datos->noti_nombre}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-2\">C.I.V- </div><div class=\"col-3 under\">{$datos->noti_cedula}&nbsp;</div>
            <div class=\"col-2\">Edad: </div><div class=\"col-1 under\">{$datos->noti_edad}&nbsp;</div>
            <div class=\"col-2\">Estado Civil: </div><div class=\"col-2 under\">{$datos->noti_estado_civil}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-2\">Domicilio: </div><div class=\"col-10 under\">{$datos->noti_domicilio}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-2\">Municipio: </div><div class=\"col-5 under\">{$datos->noti_municipio}&nbsp;</div>
            <div class=\"col-2\">Estado: </div><div class=\"col-3 under\">$datos->noti_estado&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-3\">Profesión u Ocupación: </div><div class=\"col-8 under\">{$datos->noti_profesion}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-2\">Trabaja: </div><div class=\"col-1 under\">{$trabaja2}</div>
            <div class=\"col-2\">Donde: </div><div class=\"col-7 under\">{$datos->noti_tdonde}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-2\">Dirección: </div><div class=\"col-10 under\">{$datos->noti_direccion}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-2\">Telf.: </div><div class=\"col-3 under\">{$datos->noti_telefono}&nbsp;</div>
            <div class=\"col-3\">Correo electrónico: </div><div class=\"col-4 under\">{$datos->noti_email}&nbsp;</div>
          </div>
      ";
    }else{
      $cuerpo = "Ha ocurrido un error";
    }

    $mpdf = new Mpdf();
    
    $mpdf->AddPage('', // L - landscape, P - portrait
        '', '', '', '',
        10, // margin_left
        10, // margin right
       20, // margin top
       20, // margin bottom
        0, // margin header
        0); // margin footer

    $stylesheet = file_get_contents('../includes/css/bootstrap.min.css');
    $css = "
    body{box-sizing: border-box}
    .col-1,.col-2,.col-3,.col-4,.col-5,.col-6,.col-7,.col-8,.col-9,.col-10,.col-11,.col-12{position:relative;float:left;min-height:1px;padding:5px 15px 2px 15px;margin:0;overflow:hidden;}
    .col-1{width:3.87%;}.col-2{width:12.21%;}.col-3{width:20.54%;}.col-4{width:28.87%;}.col-5{width:37.21%;}.col-6{width:45.54%;}
    .col-7{width:53.87%;}.col-8{width:62.21%;}.col-9{width:70.54%;}.col-10{width:78.87%;}.col-11{width:87.21%;}.col-12{width:100%;}
    p{font-size:16px} .under{border-bottom:1px solid #000}.b{font-weight:bold}";
    $mpdf->WriteHTML($stylesheet,1);
    $mpdf->WriteHTML($css,1);
    $mpdf->WriteHTML($cuerpo,2);
    $mpdf->Output("Solicitante_{$caso}.pdf","D");
  }//Solicitante

  public function notificar($caso)
  {
    $query = Query::prun("SELECT c.*,n.*,u.user_nombres,u.user_apellidos FROM casos AS c
                              INNER JOIN usuarios AS u ON u.id_user = c.id_user
                              INNER JOIN notificados AS n ON n.id_caso = c.id_caso
                              WHERE c.id_caso = ?",array("i",$caso));

    $numero = 1;
    if($query->result->num_rows>0){
      $datos = (object) $query->result->fetch_array(MYSQLI_ASSOC);

      $numero = $datos->caso_numero;

      $trabaja = ($datos->noti_trabaja)?'Sí':'No';
      if($datos->caso_motivo==5){
        $motivo = "<div class=\"col-2\">Otros: </div><div class=\"col-5 under\">&nbsp;&nbsp;{$datos->caso_motivo_otro}&nbsp;&nbsp;</div>";
      }else{
        switch ($datos->caso_motivo) {
          case 1: $mt = "Obligacion de Manutención"; break; case 2: $mt = "Régimen de Convivencia Familiar"; break; case 3: $mt = "Ambos Procedimientos"; break; case 4: $mt = "Responsabilidad de Crianza-custodia"; break; case 5: $mt = "Otro"; break;
        }

        $motivo = "<div class=\"col-5 under\">{$mt}</div>";
      }
      $x = explode(" ",$datos->caso_fecha_reg);
      $x = explode("-",$x[0]);
      $fecha = $x[2]."-".$x[1]."-".$x[0];

      $cuerpo ="
          <div class=\"col-12\">
            <p class=\"b\">5.- VERIFICACION DE DATOS DE LA PERSONA NOTIFICADA.</p>
          </div>
          <div class=\"col-12\" style=\"padding:0\">
            <div class=\"col-3\">Nombres y Apellidos: </div><div class=\"col-9 under\">{$datos->noti_nombre}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-2\">C.I.V- </div><div class=\"col-3 under\">{$datos->noti_cedula}&nbsp;</div>
            <div class=\"col-2\">Edad: </div><div class=\"col-1 under\">{$datos->noti_edad}&nbsp;</div>
            <div class=\"col-2\">Estado Civil: </div><div class=\"col-2 under\">{$datos->noti_estado_civil}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-2\">Domicilio: </div><div class=\"col-10 under\">{$datos->noti_domicilio}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-2\">Municipio: </div><div class=\"col-5 under\">{$datos->noti_municipio}&nbsp;</div>
            <div class=\"col-2\">Estado: </div><div class=\"col-3 under\">$datos->noti_estado&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-3\">Profesión u Ocupación: </div><div class=\"col-8 under\">{$datos->noti_profesion}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-2\">Trabaja: </div><div class=\"col-1 under\">{$trabaja}</div>
            <div class=\"col-2\">Donde: </div><div class=\"col-7 under\">{$datos->noti_tdonde}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-2\">Dirección: </div><div class=\"col-10 under\">{$datos->noti_direccion}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-2\">Telf.: </div><div class=\"col-3 under\">{$datos->noti_telefono}</div>
            <div class=\"col-3\">Correo electrónico: </div><div class=\"col-4 under\">{$datos->noti_email}</div>
          </div>

          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-6\">Relación con e Niño(a) o Adolescente involucrado: </div><div class=\"col-6 under\">{$datos->noti_relacion}</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-4 b\">7.- Acciones Dispuestas: </div><div class=\"col-8 under\">{$datos->caso_acciones}</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-6 b\">8.- Funcionario que recibió la Solicitud: </div> <div class=\"col-6 under\">{$datos->user_nombres} {$datos->user_apellidos}</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-2 b\">9.- Fecha: </div><div class=\"col-4 under\">{$fecha}&nbsp;</div>
          </div>
          <br><br>
          <div class=\"col-12\" style=\"padding:0;\">
            FIRMA DEL (LA) SOLICITANTE
          </div>
          <div class=\"col-12 text-right\" style=\"padding:0;\">
            Por la Defensoría
          </div>
          <br><br>
          <div class=\"col-12 text-right\" style=\"padding:0;\">
            <p>{$datos->user_nombres} {$datos->user_apellidos}</p>
            <p>DEFENSOR(A) RESPONSABLE</p>
          </div>

      ";
    }else{
      $cuerpo = "Ha ocurrido un error";
    }

    $mpdf = new Mpdf();
    
    $mpdf->AddPage('', // L - landscape, P - portrait
        '', '', '', '',
        10, // margin_left
        10, // margin right
       10, // margin top
       20, // margin bottom
        0, // margin header
        0); // margin footer

    $stylesheet = file_get_contents('../includes/css/bootstrap.min.css');
    $css = "
    body{box-sizing: border-box}
    .col-1,.col-2,.col-3,.col-4,.col-5,.col-6,.col-7,.col-8,.col-9,.col-10,.col-11,.col-12{position:relative;float:left;min-height:1px;padding:5px 15px 2px 15px;margin:0;overflow:hidden;}
    .col-1{width:3.87%;}.col-2{width:12.21%;}.col-3{width:20.54%;}.col-4{width:28.87%;}.col-5{width:37.21%;}.col-6{width:45.54%;}
    .col-7{width:53.87%;}.col-8{width:62.21%;}.col-9{width:70.54%;}.col-10{width:78.87%;}.col-11{width:87.21%;}.col-12{width:100%;}
    p{font-size:16px} .under{border-bottom:1px solid #000}.b{font-weight:bold}";
    $mpdf->WriteHTML($stylesheet,1);
    $mpdf->WriteHTML($css,1);
    $mpdf->WriteHTML($cuerpo,2);
    $mpdf->Output("Persona_notificada_{$numero}.pdf","D");
  }//Notificar

  public function nino($caso)
  {
    $query = Query::prun("SELECT c.*,n.*,u.user_nombres,u.user_apellidos FROM casos AS c
                              INNER JOIN usuarios AS u ON u.id_user = c.id_user
                              INNER JOIN ninos AS n ON n.id_caso = c.id_caso
                              INNER JOIN ninos_detalles AS nd ON nd.id_nino = n.id_nino
                              WHERE c.id_caso = ?",array("i",$caso));
    $numero = 1;

    if($query->result->num_rows>0){
      $datos = (object) $query->result->fetch_array(MYSQLI_ASSOC);

      $numero = $datos->caso_numero;
      
      $tipo = ($datos->nd_tipo=="V")?"Venezolano(a)":"Extranjero (a)";

      $estudia = ($datos->nd_estudia)?"Sí":"No";
      if($datos->nd_estado_civil==2){
        $civil = "<div class=\"col-2\">Otro: </div><div class=\"col-4 under\">{$datos->nd_civil_otro}</div>";
      }else{
        switch ($datos->nd_estado_civil) {
          case 0: $ec = "Soltero"; break; case 1: $ec = "Casado"; break;
        }
        $civil = "<div class=\"col-4 under\">{$ec}</div>";
      }

      if($datos->nd_vive==2){
        $vive = "<div class=\"col-2\">Otro: </div><div class=\"col-4 under\">{$datos->nd_vive_otro}</div>";
      }else{
        switch ($datos->nd_vive) {
          case 0: $vi = "Mamá"; break; case 1: $vi = "Papá"; break;
        }
        $vive = "<div class=\"col-4 under\">{$vi}</div>";
      }
      $x = explode(" ",$datos->caso_fecha_reg);
      $x = explode("-",$x[0]);
      $dia  = $x[2];
      $mes  = Base::Mes($x[1]);
      $anio = $x[0];
      

      $cuerpo ="
          <div class=\"col-12 text-center\">
            REPUBLICA BOLIVARIANA DE VENEZUELA<br>ALCALDIA DEL MUNICIPIO SUCRE EDO. ARAGUA<br>DEFENSORIA MUNICIPAL DE NIÑOS,NIÑAS Y ADOLESCENTES<br>DEFMNNA
          </div><br><br><br>
          <div class=\"col-6 b\">
            <p><span class=\"under\">HOJA DE REGISTRO DE CASOS</span></p>
          </div>
          <div class=\"col-6\">
            <p class=\"text-right b\">N° de Caso: <span class=\"under\">&nbsp;&nbsp;&nbsp; {$datos->caso_numero} &nbsp;&nbsp;&nbsp;</span></p>
          </div>
          <div class=\"col-12\">
            <p class=\"b\">1.- DATOS DE IDENTIFICACIÓN DEL NIÑ, NIÑA O ADOLESCENTE.</p>
          </div>
          <div class=\"col-12\" style=\"padding:0\">
            <div class=\"col-4 b\">Nombres y Apellidos: </div><div class=\"col-8 under\">{$datos->nino_nombre}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-4 b\">Fecha de Nacimiento: </div><div class=\"col-3 under\">{$datos->nino_nacimiento}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-4 b\">Acta de Nacimiento N°: </div> <div class=\"col-1 under\">{$datos->nino_acta_numero}</div>

            <div class=\"col-2 b\">Tomo: </div> <div class=\"col-1 under\">{$datos->nino_tomo}&nbsp;</div>

            <div class=\"col-2 b\">Año: </div> <div class=\"col-2 under\">{$datos->nino_anio}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-4 b\">Registro Civil del Municipio: </div><div class=\"col-5 under\">{$datos->nino_rcivil}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-2 b\">Estado: </div><div class=\"col-3 under\">{$datos->nino_estado}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-2 b\">Sexo: </div><div class=\"col-1 under\">{$datos->nino_sexo}&nbsp;</div>
            <div class=\"col-2 b\">Edad: </div><div class=\"col-1 under\">{$datos->nino_edad}&nbsp;</div>
            <div class=\"col-2 b\">Cedula: </div><div class=\"col-2 under\">{$datos->nd_cedula}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-3 b\">Nacionalidad: </div><div class=\"col-4 b\">{$datos->nd_tipo}</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-2 b\">Estudia: </div><div class=\"col-1 under\">{$estudia}&nbsp;</div>
            <div class=\"col-4 b\">Grado de Instrucción: </div><div class=\"col-3 under\">{$datos->nd_grado_inst}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-3 b\">Grado que estudia: </div><div class=\"col-2 under\">{$datos->nd_grado}&nbsp;</div>
            <div class=\"col-2 b\">Sección: </div><div class=\"col-2 under\">{$datos->nd_seccion}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-3 b\">Nombre del colegio: </div><div class=\"col-4 under\">{$datos->nd_nombre_colegio}&nbsp;</div>
            <div class=\"col-2 b\">Teléfono: </div><div class=\"col-3 under\">{$datos->nd_telefono_colegio}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-4 b\">Dirección del colegio: </div><div class=\"col-5 under\">{$datos->nd_direccion_colegio}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-2 b\">Ocupación: </div><div class=\"col-5 under\">{$datos->nd_direccion_colegio}&nbsp;</div>
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-3 b\">Estado Civil: </div>{$civil}
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-3 b\">Vive con: </div>{$vive}&nbsp;
          </div><br>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-4 b\"><p>2.- OBSERVACIONES: </p></div><div class=\"col-8 under\">{$datos->caso_observaciones}&nbsp;</div>
          </div><br>
          <div class=\"col-12\">
            <span class=\"b\">3.- Funcionario que recibio la Solicitud:</span> Defensor(a) Responsable {$datos->user_nombres} {$datos->user_apellidos}
          </div>
          <div class=\"col-12\" style=\"padding:0;\">
            <div class=\"col-2 b\">4.- Fecha: </div><div class=\"col-1\">Día</div><div class=\"col-1 under\">{$dia}</div>
            <div class=\"col-1\">Mes</div><div class=\"col-2 under\">{$mes}</div>
            <div class=\"col-3\">Año {$anio}.</div>
          </div>
          <br><br>
          <div class=\"col-12\" style=\"padding:0;\">
            FIRMA DEL (LA) SOLICITANTE
          </div>
          <div class=\"col-12 text-right\" style=\"padding:0;\">
            Por la Defensoría
          </div>
          <br><br>
          <div class=\"col-12 text-right\" style=\"padding:0;\">
            <p>{$datos->user_nombres} {$datos->user_apellidos}</p>
            <p>DEFENSOR(A) RESPONSABLE</p>
          </div>
          <div class=\"col-12 text-right\" style=\"padding:0;\">
            <span class=\"b\">DEFENSORIA MUNICIPAL DE NIÑOS, NIÑAS Y ADOLESCENTES<br>DEL MUNICIPIO SUCRE DEL ESTADO ARAGUA.</span>
          </div>
      ";
    }else{
      $cuerpo = "Ha ocurrido un error";
    }

    $mpdf = new Mpdf();
    
    $mpdf->AddPage('', // L - landscape, P - portrait
        '', '', '', '',
        10, // margin_left
        10, // margin right
       10, // margin top
       10, // margin bottom
        0, // margin header
        0); // margin footer

    $stylesheet = file_get_contents('../includes/css/bootstrap.min.css');
    $css = "
    body{box-sizing: border-box}
    .col-1,.col-2,.col-3,.col-4,.col-5,.col-6,.col-7,.col-8,.col-9,.col-10,.col-11,.col-12{position:relative;float:left;min-height:1px;padding:5px 15px 2px 15px;margin:0;overflow:hidden;}
    .col-1{width:3.87%;}.col-2{width:12.21%;}.col-3{width:20.54%;}.col-4{width:28.87%;}.col-5{width:37.21%;}.col-6{width:45.54%;}
    .col-7{width:53.87%;}.col-8{width:62.21%;}.col-9{width:70.54%;}.col-10{width:78.87%;}.col-11{width:87.21%;}.col-12{width:100%;}
    p{font-size:16px} .under{border-bottom:1px solid #000}.b{font-weight:bold}";
    $mpdf->WriteHTML($stylesheet,1);
    $mpdf->WriteHTML($css,1);
    $mpdf->WriteHTML($cuerpo,2);
    $mpdf->Output("Nino-a_{$numero}.pdf","D");
  }//Nino

}//Pdf_pagos

$pdf = new Pdf_casos();

if(isset($_GET['action'])):
  switch ($_GET['action']):
    case 'caso':
      $caso = $_GET['id'];
      $pdf->caso($caso);
    break;
    case 'solicitante':
      $caso = $_GET['id'];
      $pdf->solicitante($caso);
    break;
    case 'notificar':
      $caso = $_GET['id'];
      $pdf->notificar($caso);
    break;
    case 'nino':
      $caso = $_GET['id'];
      $pdf->nino($caso);
    break;
  endswitch;
endif;
?>
