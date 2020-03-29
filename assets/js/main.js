$(document).ready(function() {
  $(".navbar-burger").click(function() {
    $(".navbar-burger").toggleClass("is-active");
    $(".navbar-menu").toggleClass("is-active");
  });

  $(".drop-zone input").change(function() {
    $(".drop-zone p").text(this.files.length + " file(s) selected");
  });
});
