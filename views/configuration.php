<?
$configuration = new Configuration();
$config = $configuration->consulta();
?>
<section class="content-header">
  <h1>Configuration</h1>
  <ol class="breadcrumb">
    <li><a href="inicio.php"><i class="fa fa-home"></i> Inicio</a></li>
    <li class="active">Configuration</li>
  </ol>
</section>
<div class="content">
  <section>
  </section>
  <section class="perfil">
    <div class="row">
      <div class="col-md-12">
        <h2 class="page-header" style="margin-top:0!important">
          Global variables
        </h2>
      </div>

      <div class="col-md-12">
        <form id="fConfig" class="form-horizontal" action="funciones/class.configuracion.php" method="POST">
          <input type="hidden" name="action" value="configuracion">
          <div class="row">
            <div class="col-md-3">
              <h3>Cabinets</h3>
              <div class="form-group">
                <label for="tax" class="col-md-5 control-label">Taxes:</label>
                <div class="col-md-7">
                  <div class="input-group">
                    <input type="text" class="form-control numeros" id="tax" name="tax" value="<?=$config->config_tax?>">
                    <span class="input-group-addon">%</span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="earnings_cab" class="col-md-5 control-label">Earnings:</label>
                <div class="col-md-7">
                  <div class="input-group">
                    <input type="text" class="form-control numeros" id="earnings_cab" name="earnings_cab" value="<?=$config->config_earnings_cab?>">
                    <span class="input-group-addon">%</span>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="mano" class="col-md-5 control-label">Regular work:</label>
                <div class="col-md-7">
                  <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input type="text" class="form-control numeros" id="regular_work" name="regular_work" value="<?=$config->config_regular_work?>">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="big_work" class="col-md-5 control-label">Big work:</label>
                <div class="col-md-7">
                  <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input type="text" class="form-control numeros" id="big_work" name="big_work" value="<?=$config->config_big_work?>">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="delivery" class="col-md-5 control-label">Delivery:</label>
                <div class="col-md-7">
                  <div class="input-group">
                    <input type="text" class="form-control numeros" id="delivery" name="delivery" value="<?=$config->config_delivery?>">
                    <span class="input-group-addon">%</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-3">
              <h3>Sinks</h3>
              <div class="form-group">
                <label for="earnings_sinks" class="col-md-5 control-label">Earnings:</label>
                <div class="col-md-7">
                  <div class="input-group">
                    <input type="text" class="form-control numeros" id="earnings_sinks" name="earnings_sinks" value="<?=$config->config_earnings_sinks?>">
                    <span class="input-group-addon">%</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-3">
              <h3>Tops</h3>
              <div class="form-group">
                <label for="earnings_tops" class="col-md-5 control-label">Earnings:</label>
                <div class="col-md-7">
                  <div class="input-group">
                    <input id="earnings_tops" class="form-control numeros" type="text" name="earnings_tops" value="<?=$config->config_earnings_tops?>">
                    <span class="input-group-addon">%</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-3">
            <h3>Accessories</h3>

              <div class="form-group">
                <label for="earnings_acce" class="col-md-5 control-label">Earnings:</label>
                <div class="col-md-7">
                  <div class="input-group">
                    <input type="text" class="form-control numeros" id="earnings_acce" name="earnings_acce" value="<?=$config->config_earnings_acce?>">
                    <span class="input-group-addon">%</span>
                  </div>
                </div>
              </div>

              <h3>Global</h3>

              <div class="form-group">
                <label for="shipment" class="col-md-5 control-label">Client shipment:</label>
                <div class="col-md-7">
                  <div class="input-group">
                    <input type="text" class="form-control numeros" id="shipment" name="shipment" value="<?=$config->config_shipment?>">
                    <span class="input-group-addon">%</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="form-group">
            <div class="progress" style="display:none">
              <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
              </div>
            </div>

            <div class="alert alert-dismissible" role="alert" style="display:none">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;
              <span id="msj"></span>
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-12">
              <button id="b-config" type="submit" class="btn btn-danger btn-block btn-flat b-submit">Save</button>
            </div>
          </div>
        </form>
      </div>
    </div><!--row-->
  </section>
</div>
