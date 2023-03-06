window.addEventListener('load', () => {
    //Valores Globales
    let template = document.querySelector('#fil_template');
    let fil_tbody = document.querySelector('#fil_tbody');
    let btn_insert = document.querySelector('#btn_insert');
    let btn_theme = document.querySelector('#btn_theme');

    //Eventos de Escucha
    btn_insert.addEventListener('click', Store);
    btn_theme.addEventListener('click', change_theme);

    Fill_Table();

    //Funciones para Generar Consultas
    //--------------------------------------------------------------------------------------------------
    async function Store() {
        let name = document.querySelector('#name');
        let deadline = document.querySelector('#deadline');

        //Convertir a fecha valida en el servidor #/#/####
        deadline = deadline.value.split('-').reverse().join('-');

        let data = new FormData();
        data.append('name', name.value);
        data.append('deadline', deadline);

        let data_result = await Make_Consult('store', 'post', data);

        //Agregar el nuevo dato a la tabla
        if (data_result['success'] === true) {
            data_array = {
                'name': name.value,
                'deadline': deadline.replaceAll('-', '/')
            };
            Insert_Table(template, fil_tbody, data_array, false);

            Show_Message('Agregado');
        }
    }

    async function Fill_Table() {
        //Traer datos de la Base
        let data = await Make_Consult('index', 'get');

        //Llenar Datos
        data.quehaceres.forEach(element => {
            Insert_Table(template, fil_tbody, element);
        });
    }

    //Funciones de Ayuda
    //--------------------------------------------------------------------------------------------------
    function change_theme() {
        let body = document.querySelector('body');
        let icon = document.querySelector('#btn_theme > i');

        if (body.getAttribute('data-bs-theme') === 'light') {
            body.setAttribute('data-bs-theme', 'dark');
            icon.setAttribute('class', 'fa-xl fa-solid fa-sun');
            
        } else {
            body.setAttribute('data-bs-theme', 'light');
            icon.setAttribute('class', 'fa-xl fa-solid fa-moon');
        }
    }

    function Convert_Deadline(string) {
        return string.split('-').reverse().join('/');
    }

    function Show_Message(text) {
        let content = document.querySelector('.content');
        let alert_template = document.querySelector('#alert_template');
        let clone_template = alert_template.content.cloneNode(true);
        let alert_text = clone_template.querySelector('#alert_text');

        alert_text.textContent = text;
        content.appendChild(clone_template);
    }

    function Insert_Table(template, body_element, element, convert_deadline = true) {
        //Referencias al Template
        const clone_template = template.content.cloneNode(true);
        let fil_id = clone_template.querySelector('#fil_id');
        let fil_name = clone_template.querySelector('#fil_name');
        let fil_deadline = clone_template.querySelector('#fil_deadline');
        let fil_complete = clone_template.querySelector('#fil_complete .form-check-input');

        //Llenar Campos
        fil_id.textContent = element['id'];
        fil_name.textContent = element['name'];

        if (convert_deadline === true) {
            fil_deadline.textContent = Convert_Deadline(element['deadline']);
        } else {
            fil_deadline.textContent = element['deadline'];
        }

        if (element['complete'] === 'si') {
            fil_complete.checked = true;
        }

        //Agregar en el Tbody
        body_element.appendChild(clone_template);
    }

    async function Make_Consult(url, method, data = null) {
        return await fetch(`./api/quehaceres/${url.toLowerCase()}`, {
            method: method.toUpperCase(),
            body: data
        })

            //Respuesta del servidor
            .then(respuesta => {
                if (respuesta.ok) {
                    return respuesta.json();

                } else {
                    throw "Error al Ejecutar la Consulta"; //throw = Representamos Error para ponerlo en el Cath
                }
            })//Fin Respuesta del Servidor

            //Datos de la Respuesta
            .then(data => {
                return data;
            })

            //Control del error si existe
            .catch(error => console.error(error));
    }
});