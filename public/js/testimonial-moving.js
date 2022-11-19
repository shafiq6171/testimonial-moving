
// vanila javascript 
document.querySelectorAll('a.text_more').forEach(bttn => {
	bttn.dataset.state = 0;
	bttn.addEventListener('click', function (e) {
		let span = this.previousElementSibling;
		
		/*document.querySelectorAll('.floating-parent').forEach(flot => {
			flot.classList.remove("content-floating");
		});*/
		
		
		let div = span.parentElement;
		div.classList.add("content-floating");
		span.dataset.tmp = span.textContent;
		span.textContent = span.dataset.content;
		span.dataset.content = span.dataset.tmp;

		this.innerHTML = this.dataset.state == 1 ? 'Mehr Lesen' : ' <span class="read-less">-Weniger lesen</span>';
		this.dataset.state = 1 - this.dataset.state;
		if(this.dataset.state ==0){
				div.classList.remove("content-floating");
		}

	})
});

document.querySelectorAll('span.testimonial_moving_quote').forEach(span => {
	span.dataset.content = span.textContent;
	const regex = /.*?(\.)(?=\s[A-Z])/;
	let m;
	if ((m = regex.exec(span.textContent)) !== null) {
	 span.textContent = m[0]+ '[...]';
	}
	//span.textContent = span.textContent.substr(0, 200) + '[...]';

});