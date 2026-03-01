const wrapper = document.querySelector('.wrapper');
const registerLink = document.querySelector('.register-link');
const loginLink = document.querySelector('.login-link');
const btnPopup = document.querySelector('.btnlogin-popup');
const iconClose = document.querySelector('.icon-close');

if(registerLink){
    registerLink.onclick = ()=> {
        wrapper.classList.add('active');
    }
}

if(loginLink){
    loginLink.onclick = ()=> {
        wrapper.classList.remove('active');
    }
}

if(btnPopup){
    btnPopup.onclick = ()=> {
        wrapper.classList.add('active-popup');
    }
}

if(iconClose){
    iconClose.onclick = ()=> {
        wrapper.classList.remove('active-popup');
        wrapper.classList.remove('active');
    }
}

/* LOGIN REDIRECT */
function goHome() {
    window.location.href = "home.html";
}
