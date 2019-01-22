$('#addNameHeating').click(function(){
    let heatingType = $('#heating_heatingType option:selected').text();
    let sourceType = $('#heating_sourceType option:selected').text();
    let extractionType = $('#heating_extractionType option:selected').text();
    let heating_onTheGround = '';
    if( $('#heating_onTheGround option:selected').val() == true){
        heating_onTheGround = ' au sol';
    }

    let designation = heatingType + heating_onTheGround + ' - ' + sourceType + ' - ' + extractionType;

   $('#heating_designation').val(designation);
});
