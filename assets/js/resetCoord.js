$('#resetCoord').click(function(){
  let inputLat = $('#customer_latitude');
  let inputLng = $('#customer_longitude');
  let customerId = $(this).data('customer');

  $.post('/customer/'+customerId+'/reset').done(function(data){
      if(data.error == null){
          inputLat.val(data.location.lat);
          inputLng.val(data.location.lng);
      }else{
          console.log(data.error);
          $('#flashBox').html('<div class="mt-4 text-center alert alert-danger">\n'+data.error+'\n</div>');
      }
  });
});