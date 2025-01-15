 // Close all dropdowns on page shows
 window.addEventListener('pageshow', function() {
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
      menu.classList.remove('show');
    });
  });

  // Toggle dropdown on click
  document.querySelectorAll('.dropdown-toggle').forEach(item => {
    item.addEventListener('click', function(e) {
      e.preventDefault();

      // Close other open dropdowns
      document.querySelectorAll('.dropdown-menu').forEach(menu => {
        if (menu !== this.nextElementSibling) {
          menu.classList.remove('show');
        }
      });

      // Toggle current dropdown
      const menu = this.nextElementSibling;
      menu.classList.toggle('show');
    });
  });

  // Close dropdown when clicking outside
  document.addEventListener('click', function(event) {
    document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
      if (!menu.contains(event.target) && !menu.previousElementSibling.contains(event.target)) {
        menu.classList.remove('show');
      }
    });
  });