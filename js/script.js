function selectRole(role) {
    const action = confirm(`Do you already have a ${role} account?\n\nClick OK to Login or Cancel to Sign Up.`);
  
    if (action) {
      window.location.href = `${role}-login.html`;
    } else {
      window.location.href = `${role}-signup.html`;
    }
  }

