function validation() {

    const signupForm = document.getElementById("signupForm");
    
    if (signupForm){
        signupForm.addEventListener("submit", function(e) {
            e.preventDefault();
    const fname = document.getElementById("fname").value.trim();
    const lname = document.getElementById("lname").value.trim();
    const email = document.getElementById("email").value.trim();
    const age = document.getElementById("age").value.trim();
    const bloodGroup = document.getElementById("bloodGroup").value;
    const department = document.getElementById("department").value;
    const address = document.getElementById("address").value.trim();
    const password = document.getElementById("password").value.trim();
    const confirm = document.getElementById("confirm").value.trim();

    const errorMsg = document.getElementById("errorMsg");
    errorMsg.textContent = ""; 

    
    if (
      !fname || !lname || !email || !age || !bloodGroup || !department ||
      !address || !password || !confirm
    ) {
      errorMsg.textContent = "Please fill out all fields.";
      return;
    }

    
    const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
    if (!emailPattern.test(email)) {
      errorMsg.textContent = "Please enter a valid email address.";
      return;
    }

    
    if (password !== confirm) {
      errorMsg.textContent = "Passwords do not match."
      return;
    }

    
    alert("Signup successful!");
    location.reload();
    

        });
    }

    window.location.href="login.html"

   
}  
    


  
