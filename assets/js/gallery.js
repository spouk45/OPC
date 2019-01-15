// Get the modal
let modal = $('#myModal');

// Get the image and insert it inside the modal - use its "alt" text as a caption
let modalImg = $("#img01");
let captionText = $("#caption");
$('.myImg').click(function(){
    modal.css('display','block');
    modalImg.attr('src',$(this).attr('src'));
    captionText.html($(this).attr('alt'));
});

// Get the <span> element that closes the modal
let span = $('#closeModal');

// When the user clicks on <span> (x), close the modal
span.click(function(){
    modal.css('display','none');
});