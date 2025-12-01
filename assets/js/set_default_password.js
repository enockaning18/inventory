function setUserTypeText() {
    const userType = document.getElementById("usertype").value;
    const userInfo = document.getElementById("userkey");
  
    if (userType === "admin") {
      userInfo.value = "Admin2025!";
    } 
    else if (userType === "instructor") {
      userInfo.value = "Instructor2025!";
    } 
    else if (userType === "student") {
      userInfo.value = "Student2025!";
    } 
    else {
      userInfo.value = "";
    }
  }

  