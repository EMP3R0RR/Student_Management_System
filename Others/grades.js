document.addEventListener("DOMContentLoaded", function () {
  const table = document.getElementById("gradesTable");
  const rows = table.querySelectorAll("tbody tr");

  let totalMarks = 0;
  let totalGPA = 0;

  rows.forEach(row => {
    const marks = parseFloat(row.cells[1].textContent);
    const gpa = parseFloat(row.cells[2].textContent);
    totalMarks += marks;
    totalGPA += gpa;
  });

  const avgMarks = totalMarks / rows.length;
  const avgGPA = totalGPA / rows.length;

  document.getElementById("avgMarks").textContent = avgMarks.toFixed(2);
  document.getElementById("avgGPA").textContent = avgGPA.toFixed(2);
});

function logout() {
  window.location.href = "login.html";
}