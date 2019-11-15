//Verify match password
var password = document.getElementById("password");
var repassword = document.getElementById("repassword");
var changePwd = document.getElementById("chpwd");

password.onkeyup = function() {
    console.log(checkPassword());
}
repassword.onkeyup = function() {
    if (checkPassword()) {
        changePwd.removeAttribute("disabled");
    } else {
        changePwd.setAttribute("disabled", "true");
    }
}

function checkPassword() {
    return (password.value == repassword.value && password.value != "")
}