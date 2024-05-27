let btn_theme = document.querySelector('#btn_theme');
let body = document.querySelector('body');
let icon = document.querySelector('#btn_theme > i');
let theme = localStorage.getItem('data-bs-theme');


btn_theme.addEventListener('click', Change_Theme);
Toggle_Theme();


function Change_Theme() {
    if (localStorage.getItem('data-bs-theme') === 'light') {
        body.setAttribute('data-bs-theme', 'dark');
        icon.setAttribute('class', 'fa-xl fa-solid fa-sun');
        localStorage.setItem('data-bs-theme', 'dark');

    } else {
        body.setAttribute('data-bs-theme', 'light');
        icon.setAttribute('class', 'fa-xl fa-solid fa-moon');
        localStorage.setItem('data-bs-theme', 'light');
    }
}

function Toggle_Theme() {
    body.setAttribute('data-bs-theme', theme);

    if (theme === 'light') {
        icon.setAttribute('class', 'fa-xl fa-solid fa-moon');

    } else {
        icon.setAttribute('class', 'fa-xl fa-solid fa-sun');
    }
}

