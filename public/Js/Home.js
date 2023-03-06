window.addEventListener('load', () => {
    //Valores Globales
    let fil_tbody = document.querySelector('#fil_tbody');
    let template = document.querySelector('#fil_template');

    Fill_Table();

    async function Fill_Table() {
        //Traer datos de la Base
        let data = await Make_Consult('index', 'get');

        //Llenar Datos
        data.quehaceres.forEach(element => {
            //Referencias al Template
            const clone_template = template.content.cloneNode(true);
            let fil_id = clone_template.querySelector('#fil_id');
            let fil_name = clone_template.querySelector('#fil_name');
            let fil_deadline = clone_template.querySelector('#fil_deadline');
            let fil_complete = clone_template.querySelector('#fil_complete .form-check-input');

            //Llenar Campos
            fil_id.textContent = element['id'];
            fil_name.textContent = element['name'];
            fil_deadline.textContent = element['deadline'];
            
            if(element['complete'] === 'si'){
                fil_complete.checked = true;
                console.log(fil_complete);
            }

            //Agregar en el Tbody
            fil_tbody.appendChild(clone_template);
        });
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