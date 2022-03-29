jQuery(document).ready(function($){

  $('#rv_manual_asana').on('click',function(e){
    e.preventDefault();
    alert('Non Chiudere la finestra fino al messaggio finale');
    $.ajax({
       type : "POST",
       url : rvlocalize.ajaxurl,
       data : {
          action:"rv_asana_import",
      },
      success: function(response) {
        console.log(response);
        alert('Finito');
      }
    });
  });

  $('#rv_empty_asana').on('click',function(e){
    e.preventDefault();
    $.ajax({
       type : "POST",
       url : rvlocalize.ajaxurl,
       data : {
          action:"rv_empty_asana",
      },
      success: function(response) {
        console.log(response);
      }
    });
  });



});
