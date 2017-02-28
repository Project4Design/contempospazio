$(document).ready(function() {

  loadTable();
  loadBasic();

  $('.numeros').keypress(function(evt){
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if(charCode > 31 && (charCode < 48 || charCode > 57)){
      return false;
    }else{
      return true;
    }
  });

	$('#b-logout').click(function(){
		$.ajax({
      type: 'POST',
      cache: false,
      url: 'funciones/class.sesiones.php',
      data: {action:'logout'},
      dataType: 'json',
      success: function(r){
        if(r.response){
          window.location.replace(r.redirect)
        }
      }
		});
	});//#b-logout

  $('#fr-b-newpass').click(function(e){
    e.preventDefault();
    $('#fr-b-newpass').button('loading');
    $('#fr-newpass .progress').show();

    var form = $('#fr-newpass');
    var url  = form.attr("action");
    var p2   = $('#fr-newpass #p2');
    var p3   = $('#fr-newpass #p3');

    if(p2.val()!=p3.val()){
      p2.closest('.form-group').addClass('has-error');
      p3.closest('.form-group').addClass('has-error');
      $('#fr-newpass .progress').hide();
      $('#fr-b-newpass').button('reset'); 
      $('#fr-newpass .alert').removeClass('alert-success').addClass('alert-danger');
      $('#fr-newpass .alert #msj').text('Las contraseñas no coinciden');
      $('#fr-newpass .alert').show().delay(5000).hide('slow');
    }else{
      p2.closest('.form-group').removeClass('has-error');
      p3.closest('.form-group').removeClass('has-error');

      $.post(url,form.serialize(),function(resp){
        var json = JSON.parse(resp);

        if(json.result==true){
          $('#fr-newpass .alert').removeClass('alert-danger').addClass('alert-success');
          $('#fr-breset').click();
        }else{
          $('#fr-newpass .alert').removeClass('alert-success').addClass('alert-danger');
        }

        $('#fr-newpass .alert #msj').html(json.msj);
        $('#fr-newpass .progress').hide();
        $('#fr-b-newpass').button('reset');
        $('#fr-newpass .alert').show().delay(5000).hide('slow');
      });
    }

  });

  $('.b-submit').click(function(e){
    e.preventDefault();
    var btn    = $(this);
    var form   = btn.closest('form');
    var action = form.attr('action');
    var bar    = form.find('.progress');
    var alert  = form.find('.alert');

    alert.hide();
    btn.button('loading');
    bar.show();

    //Validacion
    var fields = form.find('input,select').filter('[required]').length;
    form.find('input,select').filter('[required]').each(function(){
      var regex = $(this).attr('pattern');
      var val   = $(this).val();
      if(val == ""){
        $(this).closest('.form-group').addClass('has-error');
        $(this).closest('.form-group').find('.help-block').show();
      }
      else{
        if(val.match(regex)){
          $(this).closest('.form-group').removeClass('has-error');
          $(this).closest('.form-group').find('.help-block').hide('fast');
          fields = fields-1;
        }else{
          $(this).closest('.form-group').addClass('has-error');
          $(this).closest('.form-group').find('.help-block').show();
        }
      }
    });

    if(fields!=0){
      alert.removeClass('alert-success').addClass('alert-danger');
      alert.find('#msj').text('You must complete all required fields.');
      bar.hide();
      btn.button('reset');
      alert.show().delay(7000).hide('slow');
    }else{
      $.ajax({
        type: 'POST',
        cache: false,
        url: action,
        data: form.serialize(),
        dataType: 'json',
        success: function(r){
          if(r.response==true){
            alert.removeClass('alert-danger').addClass('alert-success');
            form[0].reset();
          }else if(r.response=="mod"){
            alert.removeClass('alert-danger').addClass('alert-success');
          }else{
            alert.removeClass('alert-info alert-success').addClass('alert-danger');
          }
          alert.find('#msj').html(r.msj);
          if(r.reload){
            location.replace(r.redirect);
          }
        },
        error: function(){
          alert.removeClass('alert-info alert-success').addClass('alert-danger');
          alert.find('#msj').text('Ah ocurrido un error inesperado');
        },
        complete: function(r){
          bar.hide();
          btn.button('reset');
          alert.show().delay(7000).hide('slow');
        }
      })
    }
  })

  //Cuenta regresiva de caracteres
  $('.cuenta').keyup(function(){
    var id = this.id;
    var cant = $(this).attr('maxlength');
    var letras = $(this).val().length;
    var resto  = Number(cant)-Number(letras);

    if(resto<(cant*0.1)){
      $('#'+id).closest('.form-group').find('.resto').css('color','red');
    }else{
      $('#'+id).closest('.form-group').find('.resto').css('color','#737373');
    }
    $('#'+id).closest('.form-group').find('.resto').html(resto);
  });

});//document.ready

function loadTable(){
  $('.data-table').DataTable({
    responsive: true,
    "aaSorting": []
  });
}

function loadBasic(){
  $('.table-basic').DataTable({
    "paging": true,
    "lengthChange": false,
    responsive:true,
    "searching": false,
    "ordering": true,
    "aaSorting": []
  });
}

function addCommas(nStr){
  console.log(nStr);
  nStr += '';
  x = nStr.split('.');
  x1 = x[0];
  if(x.length > 1){
    str = x[1].toString();
    x2 = '.'; 
    x2 += (str.length>1) ? x[1] : x[1]+'0';
  }else{
    x2 = '.00';
  }
  var rgx = /(\d+)(\d{3})/;
  while (rgx.test(x1)) {
    x1 = x1.replace(rgx, '$1' + ',' + '$2');
  }
  return x1 + x2;
}