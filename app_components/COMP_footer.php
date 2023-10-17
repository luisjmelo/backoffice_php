<div class="bottom_container">
  <div class="footer">
    &copy; Congelagos 2023
  </div>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Find the first input or textarea element in the first form
      var form = document.querySelector("form");
      var input = Array.from(form.querySelectorAll("input, textarea")).find(function(element) {
        return element.offsetParent !== null;
      });

      // Set focus to the input element
      if (input) {
        input.focus();
      }
    });
  </script>
</div>