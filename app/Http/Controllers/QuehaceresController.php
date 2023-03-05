<?php
namespace App\Http\Controllers;

use App\Http\Middleware\Validate_Quehaceres;
use App\Models\Quehaceres;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuehaceresController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        return DB::select('SELECT * from quehaceres');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $this->validate_name($request);
        $this->validate_deadline($request);

        //cambiar formato de la fecha para poder ser Insertada en Mysql
        $deadline = $this->convert_data($request->deadline);

        return DB::insert('INSERT into quehaceres(name, deadline)
        values(?, ?)', [$request->name, $deadline]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request) {
        //Validaciones
        $this->validate_id($request);

        return DB::select('SELECT * from quehaceres where id = ? limit 1', [$request->id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request) {
        //Validaciones
        $this->validate_id($request);
        $request->validate([
            'name'     => 'sometimes|regex:/^[\w\d ]+$/|min:4|max:150',
            'deadline' => 'sometimes|date_format:d/m/Y',
            'complete' => 'sometimes|in:si,no'
        ]);

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

            return $update;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request) {
        $this->validate_id($request);
        return DB::delete('DELETE from quehaceres
        where id = ? limit 1', [$request->id]);
    }

    //Funciones Adicionales
    //--------------------------------------------------------------------------------------------------
    private function convert_data($date) {
        return Carbon::createFromFormat('d/m/Y', $date)->format('Y/m/d');
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

    private function validate_name(Request $request): void {
        $request->validate([
            'name' => 'required|regex:/^[\w\d ]+$/|min:4|max:150'
        ]);
    }

    private function validate_deadline(Request $request): void {
        $request->validate([
            'deadline' => 'required|date_format:d/m/Y'
        ]);
    }
}
