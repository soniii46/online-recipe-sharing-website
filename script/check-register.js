function validateRegisterDetails() {
    var nameRGEX = /^[A-Za-z]+\d+$/;
    var emailREGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    // var pwdREGEX=  /^(?=.*[0-9])(?=.*[a-zA-Z])[a-zA-Z0-9]{8,}$/;
    var pwdREGEX =/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;

    var un = document.getElementById('Username').value;
    var nameError = document.getElementById('unameError');
    if(un==null || un==""){
        nameError.textContent = "*Username cannot be blank";
    }
    else if (!nameRGEX.test(un)) {
        nameError.textContent =  "*Username must contain alphabets and at least one number at the end.";
    } else {
        nameError.textContent = "";
    }

    var email = document.getElementById('email').value;
    var emailError = document.getElementById('emailError');
    if(email==null || email==""){
        emailError.textContent = "*Email cannot be blank";
    }
    else if (!emailREGEX.test(email)) {
        emailError.textContent = "*Invalid email";
    } else {
        emailError.textContent = "";
    }

    var pwd = document.getElementById('password').value;
    var pwdError = document.getElementById('pwdError');
    if(pwd==null || pwd==""){
        pwdError.textContent = "*Password cannot be blank";
    }
    else if (!pwdREGEX.test(pwd)) {
        pwdError.textContent = "*Password must have a capital letter, simple letter, numeral and special character. Password length should be atleast 8 characters";
    } else {
        pwdError.textContent = "";
    }

    // pass and username cannot be same
    // var pwd = document.getElementById('password').value.trim();
    // var pwdError = document.getElementById('pwdError');

    // if (pwd === "") {
    //     pwdError.textContent = "*Password cannot be blank";
    // } else if (!pwdREGEX.test(pwd)) {
    //     pwdError.textContent = "*Password must have at least one uppercase, lowercase, number, special character, and be 10-30 characters long.";
    // } else if (un === pwd) {  
    //     pwdError.textContent = "*Username and password cannot be the same."; // ✅ Checks if username and password match
    // } else {
    //     pwdError.textContent = "";
    // }
   
    // Prevent form submission if there are errors
    if (nameError.textContent || emailError.textContent || pwdError.textContent) {
        return false;
    }
  }