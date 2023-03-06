<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>

    {{-- CSS --}}
    <link rel="stylesheet" href="{{asset('Css/Home.min.css')}}">

    {{-- Javascript --}}
    <script src="{{asset('js/Home.js')}}"></script>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    {{-- Fon Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body data-bs-theme="light">
    <header class="container-xxl user-select-none">
        <nav class="row navbar navbar-expand-lg p-1 bg-body-tertiary">
            <div class="col">
                <a class="navbar-brand fw-bold" href="" target="_blank">Brayan Aguilar</a>
            </div>

            <div class="col d-flex justify-content-end align-items-center">
                <a id="btn_theme" class="nav-link">
                    <i class="fa-xl fa-solid fa-moon"></i>
                </a>
                <a class="ms-4 nav-link" href="" target="_blank">
                    <i class="fa-xl fa-brands fa-github"></i>
                </a>
                <a class="ms-3 nav-link fw-bold fs-5" href="" target="_blank">Api</a>
            </div>
        </nav>
    </header>

    <div class="container-xxl mt-4">
        <section class="content">
            <template id="alert_template">
                <div class="alert alert-success alert-dismissible fade show position-fixed bottom-0 end-0" role="alert">
                    <strong id="alert_text"></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </template>

            <h2 class="h2 border-4 border-bottom mb-2 user-select-none">Lista de Quehaceres</h2>

            <form>
                <div class="input-group mb-3">
                    <div id="container_name">
                        <input type="text" id="name" class="form-control" placeholder="Nueva Tarea" maxlength=60>
                    </div>
                    
                    <div class="d-flex">
                        <span class="input-group-text user-select-none">Fecha limite</span>
                        <input type="date" id="deadline" class="form-control">
                    </div>

                    <div class="ms-2">
                        <button id="btn_insert" type="button" class="btn btn-success">+</button>
                    </div>
                </div>
            </form>

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Seleccionar</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Fecha Limite</th>
                        <th scope="col">Completado</th>
                    </tr>
                </thead>
                <tbody id="fil_tbody">
                    <template id="fil_template">
                        <tr id="fil_container">
                            <td id="fil_id" value="" readonly disabled hidden></td>
                            {{-- <input  type="text"  > --}}
                            <td id="fil_check_select">
                                <div class="form-check">
                                    <input class="form-check-input select" type="checkbox">
                                </div>
                            </td>
                            <td id="fil_name"></td>
                            <td id="fil_deadline"></td>
                            <td id="fil_complete">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch">
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>

            <div class="options position-fixed bottom-0 end-0 m-2 invisible">
                <button type="button" class="btn btn-primary" id="btn_select_all">Marcar Todos</button>
                <button type="button" class="btn btn-danger" id="btn_delete">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </section>

        <footer>
        </footer>
    </div>

</body>
</html>
