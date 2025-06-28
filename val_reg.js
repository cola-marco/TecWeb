//input validation
function showError(error_id, error_message){
    document.querySelector("."+error_id).classList.add("display-error");
    document.querySelector("."+error_id).innerHTML = error_message;
}

function clearError(){
    let server_errors = document.querySelectorAll("div.error-msg");
    for (let server_error of server_errors){
        server_error.innerHTML = "";
    }
    let errors = document.querySelectorAll(".error-msg");
    for(let error of errors){
        error.classList.remove("display-error");
    }
}

let reg_form = document.getElementById('login-form');
user_error = false;
email_error = false;
pass_error = false;
confpass_error = false;

if(reg_form){
    reg_form.addEventListener('change', function() {
        
        clearError();
        user_error = false;
        email_error = false;
        pass_error = false;
        confpass_error = false;

        //validazione user
        let user = document.getElementById('username').value;
        if(user.length > 0 && user.trim().length == 0){
            showError("user1-error", "<span lang='en'>Username</span> non può contenere soli spazi");
            user_error = true;
        };
        user = user.trim();
        if(user.length > 0 && user.length < 3){
            showError("user2-error", "<span lang='en'>Username</span> deve contenere almeno 3 caratteri diversi dallo spazio");
            user_error = true;
        }
        if(user.length > 0){
            fetch("checkUserSaved.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded" 
                },
                body: new URLSearchParams({ checkUser: user }) 
            })
            .then(response => response.json()) 
            .then(data => {
                if(data.result){
                    showError("user3-error", "<span lang='en'>Username</span> già registrato");
                    user_error = true;
                }
            })
            .catch(error => {
                console.error(error);
            });
        }
        
        //validazione email
        let email = document.getElementById('email').value;
        if(email.length > 0 && email.trim().length == 0){
            showError("email1-error", "<span lang='en'>Email</span> non può contenere soli spazi");
            user_error = true;
        };
        email = email.trim();
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if(email.length > 0){
            if(!emailPattern.test(email)){
                showError("email2-error", "Inserisci un'<span lang='en'>email</span> valida");
                email_error = true;
            }
            fetch("checkEmailSaved.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: new URLSearchParams({ checkEmail: email }) 
            })
            .then(response => response.json()) 
            .then(data => {
                if(data.result){
                    showError("email3-error", "<span lang='en'>Email</span> già registrata");
                    email_error = true;
                }
            })
            .catch(error => {
                console.error(error);
            });
        }

        //validazione password
        let password = document.getElementById('password').value;
        password = password.trim();
        if(password.length > 0){
            if(password.length < 8){
                showError("pass1-error", "<span lan='en'>Password</span> deve avere almeno 8 caratteri diversi dallo spazio");
                pass_error = true;
            }
        }

        //validazione conferma password
        let confirm_pwd = document.getElementById('confirm-password').value;
        confirm_pwd = confirm_pwd.trim();
        if(confirm_pwd.length > 0){
            if(password !== confirm_pwd){
                showError("confpass1-error", "Le <span lan='en'>password</span> non corrispondono");
                confpass_error = true;
            }
        }
    });
}

if(reg_form){
    reg_form.addEventListener('submit', function (e) {

        e.preventDefault();
        if(!(user_error || email_error || pass_error || confpass_error)){
            this.submit();
        }
    });
}

const passwordInput = document.getElementById('password');

if(passwordInput){
passwordInput.addEventListener('input', function () {
    const trimmed = this.value.trim();  // rimuove spazi all'inizio e alla fine
    if (this.value !== trimmed) {
        this.value = trimmed;
    }
});
}

const confpasswordInput = document.getElementById('confirm-password');

if(confpasswordInput){
confpasswordInput.addEventListener('input', function () {
    const trimmed = this.value.trim();  // rimuove spazi all'inizio e alla fine
    if (this.value !== trimmed) {
        this.value = trimmed;
    }
});
}