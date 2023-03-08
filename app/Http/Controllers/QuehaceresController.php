<?php
namespace App\Http\Controllers;

use App\Models\Quehaceres;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuehaceresController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $data = DB::select('SELECT * from quehaceres');

        return $this->response_api(true, $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $this->validate_name($request);
        $this->validate_deadline($request);

        //cambiar formato de la fecha para poder ser Insertada en Mysql
        $deadline = $this->convert_data($request->deadline);

        DB::insert('INSERT into quehaceres(name, deadline)
        values(?, ?)', [$request->name, $deadline]);

        $last_id = DB::select('SELECT id from quehaceres
        order by id desc limit 1');

        return $this->response_api(true, $last_id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request) {
        //Validaciones
        $this->validate_id($request);

        $data = DB::select('SELECT * from quehaceres where id = ? limit 1', [$request->id]);

        return $this->response_api(true, $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request) {
        //Validaciones
        $this->validate_id($request);
        $this->validate_name($request, 'sometimes');
        $this->validate_deadline($request, 'sometimes');
        $this->validate_complete($request);

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
            $update = DB::update("UPDATE quehaceres
            set $query
            where id = ?
            limit 1", [$request->id]);

            return $this->response_api(true);
        }
        return $this->response_api(false);
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

        //Ejecutar consulta
        DB::delete("DELETE from quehaceres $query");

        return $this->response_api(true);
    }

    //Funciones Adicionales
    //--------------------------------------------------------------------------------------------------
    private function response_api(bool $status, array | null $data = null, int $code = 200) {
        return response()->json([
            'success'    => $status,
            'quehaceres' => $data,
        ], $code);
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
    private function validate_id(Request $request): void {
        $request->validate([
            'id' => 'required|numeric'
        ]);
    }

    private function validate_name(Request $request, string $require = 'required'): void {
        $request->validate([
            'name' => "$require|regex:/^[#\-+\w ]+$/|min:4|max:60"
        ]);
    }

    private function validate_deadline(Request $request, string $require = 'required'): void {
        $request->validate([
            'deadline' => "$require|date_format:d-m-Y"
        ]);
    }

    private function validate_many_id(Request $request) {
        $request->validate([
            'ids' => 'required|regex:/^(\d+,{1})+(\d)+$/'
        ]);
    }

    private function validate_complete(Request $request): void {
        $request->validate([
            'complete' => 'sometimes|in:si,no'
        ]);
    }
}
