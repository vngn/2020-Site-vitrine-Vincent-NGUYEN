// js pour texte titre page d'accueil

$(document).ready(function () {
  var mouseX, mouseY;
  var ww = $(window).width();
  var wh = $(window).height();
  var traX, traY;
  $(document).mousemove(function (e) {
    mouseX = e.pageX;
    mouseY = e.pageY;
    traX = ((4 * mouseX) / 570) + 40;
    traY = ((4 * mouseY) / 570) + 50;
    $(".title").css({
      "background-position": traX + "%" + traY + "%"
    });
  });
});


// js pour sidenav Axentix

let adminSidenav = new Axentix.Sidenav('#admin-sidenav', {
  bodyScrolling: true,
  animationDuration: 500
});

let registeredSidenav = new Axentix.Sidenav('#registered-sidenav', {
  bodyScrolling: true,
  animationDuration: 500
});

let visitorSidenav = new Axentix.Sidenav('#visitor-sidenav', {
  bodyScrolling: true,
  animationDuration: 500
});

let menuSidenav = new Axentix.Sidenav('#menu-sidenav', {
  bodyScrolling: true,
  animationDuration: 500
});

let portfolioSidenav = new Axentix.Sidenav('#portfolio-sidenav', {
  bodyScrolling: true,
  animationDuration: 500
});

let articlesSidenav = new Axentix.Sidenav('#articles-sidenav', {
  bodyScrolling: true,
  animationDuration: 500
});

let blogSidenav = new Axentix.Sidenav('#blog-sidenav', {
  bodyScrolling: true,
  animationDuration: 500
});

let aboutSidenav = new Axentix.Sidenav('#about-sidenav', {
  bodyScrolling: true,
  animationDuration: 500
});

let contactSidenav = new Axentix.Sidenav('#contact-sidenav', {
  bodyScrolling: true,
  animationDuration: 500
});

// js pour sidenav Axentix

$(".button").click(function () {
  $(".social.email").toggleClass("clicked");
  $(".social.github").toggleClass("clicked");
  $(".social.linkedin").toggleClass("clicked");
  $(".social.cvsite").toggleClass("clicked");
})