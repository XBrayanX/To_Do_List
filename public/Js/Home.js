window.addEventListener('load', () => {
    //Valores Globales
    let template = document.querySelector('#fil_template');
    let fil_tbody = document.querySelector('#fil_tbody');
    let btn_insert = document.querySelector('#btn_insert');
    let btn_theme = document.querySelector('#btn_theme');
    let btn_delete = document.querySelector('#btn_delete');
    let btn_select_all = document.querySelector('#btn_select_all');

    //Eventos de Escucha
    btn_insert.addEventListener('click', function (event) {
        event.preventDefault();
        Store();
    });
    btn_theme.addEventListener('click', Change_Theme);
    btn_delete.addEventListener('click', Delete_Items);
    btn_select_all.addEventListener('click', Delete_All);

    Fill_Table();//Método inicial para llenar datos

    //Validación y eventos en los campos del formulario
    let name = document.querySelector('#name');
    let deadline = document.querySelector('#deadline');
    name.addEventListener('keyup', () => { Validate_Input(name); });
    deadline.addEventListener('keyup', () => { Validate_Input(deadline); });

    //Funciones para Generar Consultas
    //--------------------------------------------------------------------------------------------------
    async function Store() {
        let name = document.querySelector('#name');
        let deadline = document.querySelector('#deadline');

        if (name.checkValidity() !== true || deadline.checkValidity() !== true) {
            return false;
        }

        //Convertir a fecha valida en el servidor #/#/####
        deadline = deadline.value.split('-').reverse().join('-');

        let data = new FormData();
        data.append('name', name.value);
        data.append('deadline', deadline);

        let data_result = await Make_Consult('store', 'post', data);

        // //Agregar el nuevo dato a la tabla
        if (data_result['code'] === 201) {
            data_array = {
                'id': data_result.data[0]['id'],
                'name': name.value,
                'deadline': deadline.replaceAll('-', '/')
            };
            Insert_Table(template, fil_tbody, data_array, false);

            Show_Message_Time('Agregado');
        }
    }

    async function Fill_Table() {
        //Traer datos de la Base
        let data_result = await Make_Consult('index', 'get');

        //Llenar Datos
        data_result.data.forEach(element => {
            Insert_Table(template, fil_tbody, element);
        });
    }

    async function Delete_Items() {
        let fil_check_select = document.querySelectorAll('.fil_check_select');

        let data_send = '';
        const data_ids = [];//Solo se usaran para hacer referencia al DOM para borrar

        //Sacar el id de los elementos seleccionados
        fil_check_select.forEach(element => {
            if (element.checked === true) {
                data_ids.push(element.getAttribute('data-id'));

                if (data_send.length === 0) {
                    data_send = element.getAttribute('data-id');

                } else {
                    data_send += ',' + element.getAttribute('data-id');
                }

            }
        });

        if (data_send.length === 1) {//Borrar un solo ID
            let data_result = await Make_Consult('delete?id=' + parseInt(data_send), 'delete');

            if (data_result.data['affected'] > 0) {
                Delete_Fil_Container(data_send);
                Show_Message_Time('Dato Eliminado');
            }

        } else if (data_send.length > 1) {//Borrar Varios ID
            let data_result = await Make_Consult("delete?option=many&ids=" + data_send, 'delete');

            if (data_result.data['affected'] > 0) {
                data_ids.forEach(element => {
                    Delete_Fil_Container(element);
                });
                Show_Message_Time('Datos Eliminados');
            }
        }
    }

    async function Delete_All() {
        let response_user = false;
        await Swal.fire({
            title: 'Estas Seguro?',
            text: "Esta acción no se puede revertir",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Eliminar!'
        }).then((result) => {
            if (result.isConfirmed) {
                response_user = true;
            }
        });

        if (response_user === true) {
            let data_result = await Make_Consult('delete?option=all', 'delete');

            if (data_result.data['affected'] > 0) {
                let fil_check_select = document.querySelectorAll('.fil_check_select');

                fil_check_select.forEach(element => {
                    Delete_Fil_Container(element.getAttribute('data-id'));
                });

                Swal.fire(
                    'Datos Eliminado!',
                    'Todos los Datos han sido eliminados',
                    'success'
                );
            }
        }
    }

    async function Update(input) {
        let complete = 'no';
        input.checked === true ? complete = 'si' : complete = 'no';

        let data_send = '?id=' + input.getAttribute('data-id') + '&complete=' + complete;
        let data_result = await Make_Consult('update' + data_send, 'put');
    }

    //Funciones de Ayuda
    //--------------------------------------------------------------------------------------------------
    function Validate_Input(input) {
        if (input.checkValidity() !== true) {
            input.classList.add('is-invalid');

        } else {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        }
    }

    function Delete_Fil_Container(id) {
        document.querySelector(`#fil_container-${id}`).remove();
    }

    function Change_Theme() {
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

    function Show_Message_Time(text, icon = 'success') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        Toast.fire({
            icon: icon,
            title: text
        });
    }

    function Insert_Table(template, body_element, element, convert_deadline = true) {
        //Referencias al Template
        const clone_template = template.content.cloneNode(true);
        let fil_container = clone_template.querySelector('#fil_container');
        let fil_id = clone_template.querySelector('#fil_id');
        let fil_check_select = clone_template.querySelector('#fil_check_select');
        let fil_check_switch = clone_template.querySelector('#fil_check_switch');
        let fil_name = clone_template.querySelector('#fil_name');
        let fil_deadline = clone_template.querySelector('#fil_deadline');
        let fil_complete = clone_template.querySelector('#fil_complete .form-check-input');

        //Llenar Campos
        fil_id.textContent = element['id'];
        fil_name.textContent = element['name'];

        //Verificar si la fecha esta en el formato correcto
        if (convert_deadline === true) {
            fil_deadline.textContent = Convert_Deadline(element['deadline']);
        } else {
            fil_deadline.textContent = element['deadline'];
        }

        //Verificar estado de la tarea
        if (element['complete'] === 'si') {
            fil_complete.checked = true;
        }

        //Establecer campos de ID para otras tareas posteriores
        fil_container.setAttribute('id', `fil_container-${element['id']}`);
        fil_check_select.setAttribute('data-id', element['id']);
        fil_check_switch.setAttribute('data-id', element['id']);
        fil_complete.setAttribute('data-id', element['id']);

        //Agregar en el Tbody
        body_element.appendChild(clone_template);

        //Poner a la escucha el Botón de Actualizar tarea
        fil_check_switch.addEventListener('change', () => Update(fil_check_switch));
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