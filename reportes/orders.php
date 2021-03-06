<?php
require_once '../config/config.php';
require_once '../vendor/mpdf/mpdf.php';
/**
 * Create a new PDF document
 *
 * @param string $mode
 * @param string $format
 * @param int $font_size
 * @param string $font
 * @param int $margin_left
 * @param int $margin_right
 * @param int $margin_top (Margin between content and header, not to be mixed with margin_header - which is document margin)
 * @param int $margin_bottom (Margin between content and footer, not to be mixed with margin_footer - which is document margin)
 * @param int $margin_header
 * @param int $margin_footer
 * @param string $orientation (P, L)
 */

class Pdf_orders{
  private $pdf;
  private $order;
  private $fecha;

  public function __CONSTRUCT()
  {
    $this->order = new Orders();
    $this->fecha = Base::Fecha("d-m-Y");
  }

  public function order($order)
  {
    $body = "";
    $o = $this->order->obtener($order);
    if($o){
      $name = $o->order_order;
      $paddress =($o->order_address)?$o->order_address:'N/A';
      $shipping = Base::Format(ceil(($o->order_subtotal*$o->order_shipping)/100),2,".",",");
      $subtotal = Base::Format($o->order_subtotal,2,".",",");
      $total    = Base::Format($o->order_total,2,".",",");

      $tbody = "";

      $prod  = $this->order->obtenerProd($order);
      $i=1;
      foreach ($prod as $d){
        switch($d->od_type){
          case '1': $product=$d->od_name; $item = $d->od_item; $qty = $d->od_qty; break;
          case '2': $product=$d->od_name; $item = "-"; $qty = $d->od_qty; break;
          case '3': $product=$d->od_name; $item = "-"; $qty = $d->od_qty."(ft^2)"; break;
          case '4': $product=$d->od_name; $item = "-"; $qty = $d->od_qty; break;
        }
        $tbody .= "
          <tr>
            <td class='text-center'>{$i}</td>
            <td>{$product}</td>
            <td>{$d->od_description}</td>
            <td class='text-center'>{$item}</td>
            <td class='text-center'>{$qty}</td>
          </tr>
        ";
        $i++;
      }

      $body.="
            <div class='col-12'>
              <b>ORDER #{$o->order_order}</b>
            </div>
            <div class='col-6'>
              <h4>Client details</h4>
              <strong>{$o->client_name}</strong><br>
              {$o->client_address}<br>
              Phone: {$o->client_phone}<br>
              Email: {$o->client_email}<br>
              Contact: {$o->client_contact}<br>
              Project Address: {$paddress}<br>
            </div>
            <div class='col-12'> &nbsp; </div>
            <div class='col-12'>
              <table class='table table-bordered'>
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Description</th>
                    <th>Item</th>
                    <th>Qty</th>
                  </tr>
                </thead>
                <tbody>
                  {$tbody}
                </tbody>
                <tfoot>
                  <tr>
                    <th colspan='4' class='text-right'>Subtotal:</th>
                    <td class='text-right'>$ {$subtotal}</td>
                  </tr>
                  <tr>
                    <th colspan='4' class='text-right'>Shipping:</th>
                    <td class='text-right'>$ {$shipping}</td>
                  </tr>
                  <tr>
                    <th colspan='4' class='text-right'>Total:</th>
                    <td class='text-right'>$ {$total}</td>
                  </tr>
                </tfoot>
              </table>
            </div>
          ";

    }else{
      $name="";
      $body = "<center> <h1>An error has ocurred.</h1></cener>";
    }//Si existe un error

    $mpdf = new mPDF('','', 12, '', 15, 15, 0, 5, 5, 5, 'P');
    
    $header = "
              <div class='col-3'>
                <center><img src='../images/logo.JPG' width='75px'></center>
              </div>
              <div class='col-6 text-center'>
                <h4>CONTEMPOSPAZIO</h4>
                9773 S. Orange Blossom Trail. ste 29. Orlando, Fl. 32837<br>
                www.contempospazio.com<br>
                Tlf: +1 (407) 5908881
              </div>";
    $footer = "
            <table width=\"100%\" style=\"vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;\"><tr>
            <td width=\"33%\"><span style=\"font-weight: bold; font-style: italic;\">{DATE m-d-Y}</span></td>
            <td width=\"33%\" align=\"center\" style=\"font-weight: bold; font-style: italic;\">{PAGENO}/{nbpg}</td>
            <td width=\"33%\" style=\"text-align: right; \">ORDER #{$name}</td>
            </tr></table>";
    $css = "
    body{box-sizing: border-box;font-family: 'Source Sans Pro',sans-serif;}
    table{border-spacing: 0;border-collapse: collapse;}
    .table td,.table th {background-color: #fff !important;}
    .table-bordered th,.table-bordered td {border: 1px solid #ddd !important;}
    .table { width: 100%; max-width: 100%; margin-bottom: 20px; }
    .table th,
    .table td { padding: 8px;border-top: 1px solid #ddd; }
    .table tbody td{font-size:13px}
    .table thead tr th { vertical-align: bottom; border-bottom: 2px solid #ddd; }
    .text-center{text-align:center}.text-left{text-align:left;}.text-right{text-align:right}
    .col-1,.col-2,.col-3,.col-4,.col-5,.col-6,.col-7,.col-8,.col-9,.col-10,.col-11,.col-12{position:relative;float:left;min-height:1px;padding:5px 15px 2px 15px;margin:0;overflow:hidden;}
    .col-1{width:3.87%;}.col-2{width:12.21%;}.col-3{width:20.54%;}.col-4{width:28.87%;}.col-5{width:37.21%;}.col-6{width:45.54%;}
    .col-7{width:53.87%;}.col-8{width:62.21%;}.col-9{width:70.54%;}.col-10{width:78.87%;}.col-11{width:87.21%;}.col-12{width:100%;}
    p{font-size:16px} .under{border-bottom:1px solid #000}.b{font-weight:bold}";

    $mpdf->setAutoTopMargin = 'stretch';
    $mpdf->setAutoBottomMargin = 'stretch';
    $mpdf->SetHTMLHeader($header);
    $mpdf->SetHTMLFooter($footer);
    $mpdf->WriteHTML($css,1);
    $mpdf->WriteHTML($body,2);
    $mpdf->Output("order{$name}.pdf","D");
  }//Order
}//Pdf_electores

$pdf = new Pdf_orders();

if(isset($_GET['action'])):
  switch ($_GET['action']):
    case 'order':
      $order = $_GET['order'];
      $pdf->order($order);
    break;
    default:
      return false;
    break;
  endswitch;
endif;
?>