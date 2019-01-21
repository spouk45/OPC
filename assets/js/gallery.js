// Get the modal
let modal = $('#myModal');
let selectorActivated = false;
// Get the image and insert it inside the modal - use its "alt" text as a caption
let modalImg = $("#img01");
let captionText = $("#caption");
let selectedImgId = [];

$('.myImg').click(function () {
    if (!selectorActivated) {
        modal.css('display', 'block');
        modalImg.attr('src', $(this).attr('src'));
        captionText.html($(this).attr('alt'));
    } else {
        $(this).toggleClass('active');
    }
});

// Get the <span> element that closes the modal
let span = $('#closeModal');

// When the user clicks on <span> (x), close the modal
span.click(function () {
    modal.css('display', 'none');
});

// selection mode
$('#selector').click(function () {
    $(this).toggleClass('active');
    if ($(this).hasClass('active')) {
        selectorActivated = true;
        $('#deleteImg').show();
    } else {
        selectorActivated = false;
        $('.myImg').removeClass('active');
        $('#deleteImg').hide();
        console.log(selectedImgId);
    }

});

$('#deleteImg').click(function () {
    let tab = $('.myImg.active');
    if(tab.length > 0 ){
        $('#deleteModal').show();
    }
    // récupération de la liste des image id selectionnées
    tab.each(function(){
         let id = $(this).attr('data-id');
        selectedImgId.push(id);
    });

    // open confirmation modal
    $('#deleteForm #imagesToDelete').val(JSON.stringify(selectedImgId));
    // process post delete

    // tab.each(function () {
    //     $(this).parents('.blockImg').remove();
    // });
    selectedImgId = [];

});


$('#deleteModal span.close').click(function(){
    $('#deleteModal').hide();
});

$('#back').click(function(){
    $('#deleteModal').modal('hide');
});

$('#confirm').click(function(){
    // delete action

});
