window.addEventListener('load', () => {
    //Global Variables
    let template = document.querySelector('#fil_template');
    let fil_tbody = document.querySelector('#fil_tbody');

    Fill_Table();//Initial method for filling data

    //Events
    let btn_insert = document.querySelector('#btn_insert');
    btn_insert.addEventListener('click', function (event) {
        event.preventDefault();
        Store();
    });

    document.querySelector('#btn_delete').addEventListener('click', Delete_Items);
    document.querySelector('#btn_select_all').addEventListener('click', Delete_All);

    //Validation and events in form fields
    document.querySelector('#name').addEventListener('keyup', (e) => { Validate_Input(e.target); });
    document.querySelector('#deadline').addEventListener('keyup', (e) => { Validate_Input(e.target); });

    //Functions to Generate Queries
    //--------------------------------------------------------------------------------------------------
    async function Store() {
        let form_task = document.querySelector('#form_task');

        if (form_task.checkValidity() !== true) {
            return Show_Message_Time('Please fill in the details correctly', 'info', 2000);
        }

        let data = new FormData(form_task);
        let data_result = await Make_Consult('store', 'post', data);

        //Add the new data to the table
        if (data_result['code'] === 201) {
            let data_array = {
                'id': data_result.data.id,
                'name': data.get('name'),
                'deadline': data.get('deadline').replaceAll('-', '/')
            };

            Insert_Table(template, fil_tbody, data_array, false);
            Show_Message_Time('Aggregate');
        }
    }

    async function Fill_Table() {
        //Bring data from the Base
        let data_result = await Make_Consult('index', 'get');

        //Fill Data
        data_result.data.forEach(element => {
            Insert_Table(template, fil_tbody, element);
        });
    }

    async function Delete_Items() {
        let fil_check_select = document.querySelectorAll('.fil_check_select');

        let data_send = '';
        const data_ids = [];//Only used to reference the DOM for deletion

        //Sacar el id de los elementos seleccionados
        fil_check_select.forEach(element => {
            if (element.checked === true) {
                data_ids.push(element.getAttribute('data-id'));
                data_send.length === 0 ? data_send = element.getAttribute('data-id') : data_send += ',' + element.getAttribute('data-id');
            }
        });

        if (data_ids.length === 1) {//Delete a single ID
            let data_result = await Make_Consult('delete?id=' + parseInt(data_send), 'delete');

            if (data_result.data['affected'] > 0) {
                Delete_Fil_Container(data_send);
                Show_Message_Time('Deleted Data');
            }

        } else if (data_ids.length > 1) {//Delete Multiple IDs
            let data_result = await Make_Consult("delete?option=many&ids=" + data_send, 'delete');

            if (data_result.data['affected'] > 0) {
                data_ids.forEach(element => {
                    Delete_Fil_Container(element);
                });
                Show_Message_Time('Selected data deleted');
            }

        } else {
            Show_Message_Time('Select at least 1 record', 'info');
        }
    }

    async function Delete_All() {
        let response_user = false;
        await Swal.fire({
            title: '¿You are sure?',
            text: "This action cannot be reversed",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete!',
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
                    'Deleted all Data!',
                    'All Data has been deleted',
                    'success'
                );
            }
        }
    }

    async function Update_Name(input, event) {
        if (Validate_Input(input) === true && event.key === 'Enter') {
            let fil_container = input.parentNode.parentNode;
            let data_send = '?id=' + fil_container.getAttribute('data-id') + '&name=';
            let data_result = await Make_Consult('update' + data_send + input.value, 'put');

            if (data_result.data['affected'] > 0) {
                Change_Status_Input(input);
            }
        }
    }

    function Change_Status_Input(input) {
        input.classList.remove('is-valid');
        input.classList.remove('input-write');
        input.setAttribute('data-prev', input.value);
        input.blur();
    }

    async function Lost_Focus(input) {
        input.value = input.getAttribute('data-prev');
        input.classList.remove('is-valid');
        input.classList.remove('input-write');
        input.blur();
    }
    async function Update_Deadline(input, event) {
        if (Validate_Input(input) === true && event.key === 'Enter') {
            let fil_container = input.parentNode.parentNode;
            let data_result = await Make_Consult('update' + '?id=' + fil_container.getAttribute('data-id') + '&deadline=' + input.value, 'put');

            if (data_result.data['affected'] > 0) {
                Change_Status_Input(input);
            }
        }
    }

    async function Update_Complete(input) {
        let complete = 'no';
        input.checked === true ? complete = 'si' : complete = 'no';
        let data_send = '?id=' + input.getAttribute('data-id') + '&complete=' + complete;
        await Make_Consult('update' + data_send, 'put');
    }

    //Funciones de Ayuda
    //--------------------------------------------------------------------------------------------------
    function Validate_Input(input) {
        if (input.checkValidity() !== true) {
            input.classList.add('is-invalid');
            return false;

        } else {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            return true;
        }
    }

    function Delete_Fil_Container(id) {
        document.querySelector(`#fil_container-${id}`).remove();
    }

    function Show_Message_Time(text, icon = 'success', timer = 1000) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: timer,
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

    function Insert_Table(template, body_element, element) {
        //References to the Template
        const clone_template = template.content.cloneNode(true);
        let fil_container = clone_template.querySelector('#fil_container');
        let fil_id = clone_template.querySelector('#fil_id');
        let fil_check_select = clone_template.querySelector('#fil_check_select');
        let fil_check_switch = clone_template.querySelector('#fil_check_switch');
        let fil_name = clone_template.querySelector('#fil_name');
        let fil_deadline = clone_template.querySelector('#fil_deadline');
        let fil_complete = clone_template.querySelector('#fil_complete .form-check-input');
        let input_name = fil_name.querySelector('.name');
        let input_deadline = fil_deadline.querySelector('.deadline');

        //Fill fields
        fil_id.textContent = element['id'];

        input_name.value = element['name'];
        input_name.setAttribute('data-prev', element['name']);
        input_name.addEventListener('click', () => input_name.classList.add('input-write'));
        input_name.addEventListener('blur', () => Lost_Focus(input_name));
        input_name.addEventListener('keyup', (event) => Update_Name(input_name, event));

        input_deadline.value = element['deadline'];
        input_deadline.setAttribute('data-prev', element['deadline']);
        input_deadline.addEventListener('blur', () => Lost_Focus(input_deadline));
        input_deadline.addEventListener('keydown', (event) => Update_Deadline(input_deadline, event));


        //Check task status
        if (element['complete'] === 'si') {
            fil_complete.checked = true;
        }

        //Set ID fields for subsequent tasks
        fil_container.setAttribute('id', `fil_container-${element['id']}`);
        fil_container.setAttribute('data-id', `${element['id']}`);
        fil_check_select.setAttribute('data-id', element['id']);
        fil_check_switch.setAttribute('data-id', element['id']);
        fil_complete.setAttribute('data-id', element['id']);

        //Add on Tbody
        body_element.appendChild(clone_template);

        //Listen for the Update Task Button
        fil_check_switch.addEventListener('change', () => Update_Complete(fil_check_switch));
    }

    //This is a example of fetch with async and await, in other case it's recommended use Axios
    async function Make_Consult(url, method, data = null) {
        return await fetch(`./api/todolist/${url.toLowerCase()}`, {
            method: method.toUpperCase(),
            body: data
        })
            .then(respuesta => {
                if (respuesta.ok) {
                    return respuesta.json();

                } else {
                    throw "Error al Ejecutar la Consulta"; //throw = Representamos Error para ponerlo en el Cath
                }
            })

            //Data 
            .then(data => {
                return data;
            })

            //If it exists, check the error.
            .catch(error => console.error(error));
    }
});