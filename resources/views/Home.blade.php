<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Todo List</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>

    {{-- Fon Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
          integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- CSS --}}
    @vite(['resources/sass/home.scss'])

    {{-- Javascript --}}
    @vite(['resources/js/app.js', 'resources/js/home.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body data-bs-theme="light">
    @include('layouts.navbar')

    <div class="container-fluid" id="ct-todolist">
        <section class="content">
            <h2 class="h3 fw-bold mb-2 user-select-none text-center">Todo List</h2>

            <form id="form_task">
                <div class="input-group mb-3">
                    <div id="container_name">
                        <input type="text" id="name" name="name" class="form-control" placeholder="New task"
                               minlength="4" maxlength="60" required pattern="[#\-+\w ]+"
                               title="Solo se permiten letras, números y los símbolos [- + _ #]">
                    </div>

                    <div class="d-flex ms-1">
                        <span class="input-group-text user-select-none">Deadline</span>
                        <input type="date" id="deadline" name="deadline" class="form-control" required>
                    </div>

                    <div class="ms-2">
                        <button id="btn_insert" type="submit" class="btn" aria-label="add_item">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>

                    <div class="ms-2">
                        <button type="button" class="btn btn-secondary fw-bold" data-bs-toggle="modal" data-bs-target="#modal_help">
                            ?
                        </button>
                    </div>
                </div>
            </form>

            <!-- Modal -->
            <div class="modal fade" id="modal_help" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold text-center w-100 fs-2">Help information</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h3>User Manual:</h3>
                            <p>To accept any changes within the task table: please press <strong>Enter</strong>. If you exit the element, the program will consider the operation
                                canceled and revert to its initial value.</p>
                            <p>All input fields have validations. Make sure they are in green before saving. If they are not, you won't be able to save the changes.</p>
                            <p class="text-info">Note: Fields in green indicate they have passed validations. Fields in red indicate <span class="text-danger">errors</span> that need to be corrected before saving.
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Select</th>
                        <th scope="col">Description</th>
                        <th scope="col">Deadline</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody id="fil_tbody">
                    <template id="fil_template">
                        <tr id="fil_container">
                            <td id="fil_id" value="" readonly disabled hidden></td>
                            <td>
                                <div class="form-check">
                                    <input id="fil_check_select" class="form-check-input fil_check_select" type="checkbox">
                                </div>
                            </td>
                            <td id="fil_name">
                                <input type="text" class="name form-control" name="name" minlength="4" maxlength="60" required pattern="[#\-+\w ]+"
                                       title="Solo se permiten letras, números y los símbolos [- + _ #]">
                            </td>
                            <td id="fil_deadline">
                                <input type="date" class="deadline form-control" name="deadline">
                            </td>
                            <td id="fil_complete">
                                <div class="form-check form-switch">
                                    <input id="fil_check_switch" class="form-check-input" type="checkbox" role="switch">
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>

            <div class="options position-fixed bottom-0 end-0 m-2">
                <button type="button" class="btn btn-secondary fw-bold" id="btn_delete" aria-label="delete_item">
                    <i class="fa fa-trash"></i>
                    Delete Selected
                </button>

                <button type="button" class="btn btn-danger fw-bold" id="btn_select_all">
                    <i class="fa fa-trash"></i>
                    Delete All
                </button>
            </div>
        </section>

        <footer>
        </footer>
    </div>

</body>

</html>
