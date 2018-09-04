//ALT LAB STUFF

// When the user scrolls the page, execute myFunction 
window.onscroll = function() {myFunction()};

// Get the navbar
var navbar = document.getElementById("wrapper-navbar");

// Get the offset position of the navbar
var sticky = navbar.offsetTop+60;

// Add the sticky class to the navbar when you reach its scroll position. Remove "sticky" when you leave the scroll position
function myFunction() {
  if (window.pageYOffset >= sticky) {
    navbar.classList.add("sticky")
  } else {
    navbar.classList.remove("sticky");
  }
}


var ratings = document.getElementsByTagName('label');
console.log(ratings[1].tabIndex = 0);
ratings = Array.from(ratings);

ratings.forEach(function(element) {
  element.tabIndex = 0;
  element.setAttribute('role', 'radio');
});