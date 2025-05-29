const checkboxes = document.querySelectorAll(".course-checkbox");
    const totalCostSpan = document.getElementById("totalCost");
    const selectedCountSpan = document.getElementById("selectedCount");

    function updateCourseSummary() {
      let total = 0;
      let count = 0;

      checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
          total += parseFloat(checkbox.dataset.price);
          count++;
        }
      });

      totalCostSpan.textContent = total.toFixed(2);
      selectedCountSpan.textContent = count;
    }

    checkboxes.forEach(cb => {
      cb.addEventListener("change", updateCourseSummary);
    });

    document.getElementById("registrationForm").addEventListener("submit", function(e) {
      e.preventDefault();
      const semester = document.getElementById("semester").value;
      const selectedCourses = Array.from(checkboxes).filter(cb => cb.checked);

      if (selectedCourses.length === 0) {
        alert("Please select at least one course.");
        return;
      }

      if (!semester) {
        alert("Please select a semester.");
        return;
      }

      alert("Courses registered successfully!");
      this.reset();
      updateCourseSummary();
    });