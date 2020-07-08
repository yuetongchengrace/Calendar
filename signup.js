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
    .then(data => console.log(data.success ? "You've been Signed up!" : `You were not signed up${data.message}`))
    .catch(err => console.error(err));
}