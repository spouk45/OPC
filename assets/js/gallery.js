// Open the Modal
$('.thumb').click(function(){
   openModal();
});

$('.prev').click(function(){
    plusSlides(-1);
});

$('.next').click(function(){
    plusSlides(1);
});

$('.close').click(function(){
    closeModal();
});

$('.demo').click(function(){
    let n = $(this).attr('data-currentSlide');
    $(this).addClass('active');
    currentSlide(n);
});
function openModal() {
    document.getElementById('myModal').style.display = "block";
}

// Close the Modal
function closeModal() {
    document.getElementById('myModal').style.display = "none";
}

let slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
    showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides(n) {
    let i;
    let slides = $('.mySlides');
    let dots = $('.demo');
    let captionText = $('#caption');

    if (n > slides.length) {slideIndex = 1}
    if (n < 1) {slideIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex-1].style.display = "block";
    dots[n].className += " active";
    captionText.innerHTML = dots[slideIndex-1].alt;
}