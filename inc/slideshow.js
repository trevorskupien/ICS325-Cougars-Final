let i = 0;
showSlide(i);

function incSlides(n){
	showSlide(i += n);
}

function currentSlide(n){
	showSlide(i = n);
}

function showSlide(n){
	let slides = $(".blog-slide");

	if(n >= slides.length) {i = 0};
	if(n < 0) {i = slides.length - 1};
	
	for(j = 0; j < slides.length; j++){
		slides[j].style.display = "none";
	}
	
	slides[i].style.display = "flex";
}