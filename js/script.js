document.addEventListener("DOMContentLoaded", function () {
  var adminMenuBtn = document.getElementById("admin-menu-btn");
  var adminDropdownContent = document.getElementById("admin-dropdown-content");

  // Toggle dropdown content visibility
  adminMenuBtn.addEventListener("click", function () {
    adminDropdownContent.classList.toggle("show");
  });

  // Close dropdown content when clicking outside of it
  window.addEventListener("click", function (event) {
    if (!event.target.matches("#admin-menu-btn")) {
      if (adminDropdownContent.classList.contains("show")) {
        adminDropdownContent.classList.remove("show");
      }
    }
  });
});
