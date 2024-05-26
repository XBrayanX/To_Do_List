<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class todoListController extends Controller {
    private $validator;
    private $validator_rules;
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $data = DB::select('SELECT * from todolist');

        return $this->response_api($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //Validaciones
        $this->validate_name();
        $this->validate_deadline();
        $this->validator = Validator::make($request->all(), $this->validator_rules);

        if ($this->validator->fails()) {
            return $this->response_api(null, 400);
        }

        //cambiar formato de la fecha para poder ser Insertada en Mysql
        $deadline = $this->convert_data($request->deadline);

        DB::insert('INSERT into todolist(name, deadline)
        values(?, ?)', [$request->name, $deadline]);

        $last_id = DB::select('SELECT id from todolist
        order by id desc limit 1');

        return $this->response_api($last_id, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request) {
        //Validaciones
        $this->validate_id($request);
        $this->validator = Validator::make($request->all(), $this->validator_rules);

        if ($this->validator->fails()) {
            return $this->response_api(null, 400);
        }

        $data = DB::select('SELECT * from todolist where id = ? limit 1', [$request->id]);
        return $this->response_api($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request) {
        //Validaciones
        $this->validate_id();
        $this->validate_name('sometimes');
        $this->validate_deadline('sometimes');
        $this->validate_complete();
        $this->validator = Validator::make($request->all(), $this->validator_rules);

        if ($this->validator->fails()) {
            return $this->response_api(null, 400);
        }

        $data = [
            'name'     => $request->name ?? null,
            'deadline' => $request->deadline ?? null,
            'complete' => $request->complete ?? null
        ];

        //Eliminar Valores Vacíos
        $data = array_filter($data);

        if (count($data) > 0) {
            if (!empty($data['deadline'])) {
                //Convertir fecha
                $data['deadline'] = $this->convert_data($data['deadline']);
            }

            //Crear Consulta
            $query = $this->create_query($data);

            //Ejecutar Consulta
            $affected = DB::update("UPDATE todolist
            set $query
            where id = ?
            limit 1", [$request->id]);

            return $this->response_api(['affected' => $affected]);
        }
        return $this->response_api(null, 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request) {
        $query = '';

        if (strtolower($request->option) === 'many') {
            //Validación
            $this->validate_many_id($request);
            $query = "where id in($request->ids)";

        } elseif (strtolower($request->option) === 'all') {
            $query = '';

        } else {
            //Validación
            $this->validate_id($request);
            $query = "where id = $request->id limit 1";
        }

        if (!empty($this->validator_rules)) {
            $this->validator = Validator::make($request->all(), $this->validator_rules);

            if ($this->validator->fails()) {
                return $this->response_api(null, 400);
            }
        }

        //Ejecutar consulta
        $affected = DB::delete("DELETE from todolist $query");

        return $this->response_api(['affected' => $affected]);
    }

    //Funciones Adicionales
    //--------------------------------------------------------------------------------------------------
    private function response_api($data = null, int $code = 200) {
        $errors = null;

        if (!empty($this->validator) && $this->validator->fails()) {
            $errors = $this->validator->messages();
        }

        $response = [
            'code'   => $code,
            'data'   => $data,
            'errors' => $errors,
        ]; 

        //Quitar Valores vacíos
        $response = array_filter($response);

        return response()->json($response, $code);
    }

    private function convert_data($date) {
        return Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d');
    }

    private function create_query(array $data): string {
        $string = implode(',', array_map(function ($key, $value) {
            return "$key = '$value'";
        }, array_keys($data), $data));

        return $string;
    }

    //Funciones de Validación
    //--------------------------------------------------------------------------------------------------
    private function validate_id(): void {
        $this->validator_rules['id'] = 'required|numeric';
    }

    private function validate_name(string $require = 'required'): void {
        $this->validator_rules['name'] = "$require|regex:/^[#\-+\w ]+$/|min:4|max:60";
    }

    private function validate_deadline(string $require = 'required'): void {
        $this->validator_rules['deadline'] = "$require|date_format:d-m-Y";
    }

    private function validate_many_id(): void {
        $this->validator_rules['ids'] = 'required|regex:/^(\d+,{1})+(\d)+$/';
    }

    private function validate_complete(): void {
        $this->validator_rules['complete'] = 'sometimes|in:si,no';
    }
}
