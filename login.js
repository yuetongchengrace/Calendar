function loginAjax(event) {
    event.preventDefault();
    const username = document.getElementById("login_username").value; // Get the username from the form
    const password = document.getElementById("login_password").value; // Get the password from the form
    const data = { 'username': username, 'password': password};
    // Make a URL-encoded string for passing POST data:
    //const data = { 'username': username, 'password': password };
    //const token= document.getElementsByName("token").value;
    fetch("login.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' 
            //,'X-CSRF-Token': token 
            }
        })
    .then(response => response.json())
    .then(data => {
        console.log(data.success ? "You've been logged in!" : `You were not logged in ${data.message}`)
        if(data.success==true){
            window.location.href="calendar.html";
        }
        else{
            document.getElementById("error_message").innerHTML=data.message;
        }
    })
    .catch(err => console.error(err));
    
}

document.getElementById("login_btn").addEventListener("click", loginAjax, false); // Bind the AJAX call to button click