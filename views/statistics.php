<?
$graphics = new Graphics();
$orders   = $graphics->lastDaysOrders();
$products = $graphics->productsType();
$selling  = $graphics->bestSelling();

?>
<section class="content-header">
  <h1> Statistics </h1>
  <ol class="breadcrumb">
    <li><a href="inicio.php"><i class="fa fa-dashboard" aria-hidden="true"></i> Home</a></li>
    <li class="active">Statistics</li>
  </ol>
</section>

<!-- Main content -->
<div class="content">
  <!-- Info boxes -->
  <div class="row">
  	
    <div class="col-md-12 hidden-xs hidden-xs">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Orders from the last 7 days.</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body chart-responsive">
        	<div id="chart-orders" style="height:300px"></div>
       	</div>
      </div>
    </div>

    <div class="col-md-6">
    	<div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Sellings by type</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body chart-responsive">
        	<div id="chart-products" style="height:300px"></div>
       	</div>
      </div>
    </div>

    <div class="col-md-6">
    	<div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title"><i  class="glyphicon glyphicon-fire" style="color:#DD4B39"></i> Best selling</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body chart-responsive">
        	<ul class="products-list product-list-in-box">
          <?
          	foreach($selling as $d) {
          ?>
            <li class="item">
              <div class="product-img">
                <img class="img-responsive" src="<?=Base::Img("images/uploads/".$d->foto)?>" alt="<?=$d->foto?>">
              </div>
              <div class="product-info">
                <a href="?ver=products&opc=<?=$d->opc?>&id=<?=$d->id?>" class="product-title"><?=$d->product?><span class="label label-danger pull-right"><?=$d->sells?> Sells</span></a>
                <span class="product-description">
                  <?=$d->name?>
                </span>
              </div>
            </li>
            <?}?>
          </ul>
       	</div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
    //ORDERS
    $('#chart-orders').highcharts({
    	title: null,
      xAxis: {categories:[<?=join($orders->days,",")?>]},
      yAxis: {min:0,tickInterval:1,title:{text:'Total Orders'}},
      legend: {layout:'vertical',align:'right',verticalAlign:'middle',borderWidth:0},
      series: [{name: 'Orders',data: [<?=join($orders->orders,",")?>]}]
    });

    //PRODUCTS
    // Build the chart
    $('#chart-products').highcharts({
      chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
      },
      title: null,
      tooltip:{pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br>Qty: {point.y}'},
      plotOptions: {
        pie: {
          allowPointSelect: true,
          cursor: 'pointer',
          dataLabels: {
              enabled: false
          },
          showInLegend: true
        }
      },
      series: [{
      	name:'Type',
      	colorByPoint:true,
      	data: [<?=join($products,",")?>]
      }]
    });
  });
 </script>