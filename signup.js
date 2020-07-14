document.getElementById("signup_btn").addEventListener("click", signupAjax, false); // Bind the AJAX call to button click
function signupAjax(event){
    event.preventDefault();
    const username = document.getElementById("signup_username").value;
    const password = document.getElementById("signup_password").value;
    const confirm_password=document.getElementById("signup_confirm_password").value;
    const data = { 'username': username, 'password': password,'confirm_password':confirm_password };
    fetch("signup.php", {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { 'content-type': 'application/json' }
        })
    .then(response => response.json())
    .then(data => {
        if(data.success==true){
            alert("Welcome user "+username+", you've been successfully signed up!");
            document.getElementById("signup_username").value="";
            document.getElementById("signup_password").value="";
            document.getElementById("signup_confirm_password").value="";
        }
        else{
            if(data.message=="Already signed up"){
                alert("Hi user "+username+", you already signed up with us!");
            }
            else{
                alert(data.message);
            }
        }
    })
    .catch(err => console.error(err));
}