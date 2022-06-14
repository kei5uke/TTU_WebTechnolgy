window.onload = function validate() {
    let add = document.getElementById("submit");
    add.addEventListener("click", () => {
        let username = document.getElementById("username");
        let password = document.getElementById("password");
        if (!username.checkValidity()) {
            alert("Username can contain letters, numbers and underscores. Minimum length is 4. Maximum length is 20.");
        }
        else if (!password.checkValidity()) {
            alert("Password can contain letters, numbers and underscores. Minimum length is 6. Maximum length is 20.");
        }
    });
}