<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportesResolucionExport;

class ReporteResolucionController extends Controller
{
    private function mapaCodigosResolucion()
    {
        $archivados = DB::table('archivados')
            ->select('cod_res', 'cod_carch');

        $archivados1 = DB::table('archivados1')
            ->select('cod_res', 'cod_carch');

        $registros = DB::query()
            ->fromSub($archivados->unionAll($archivados1), 'archivos_resolucion')
            ->leftJoin('codigo_archivos', 'archivos_resolucion.cod_carch', '=', 'codigo_archivos.cod_carch')
            ->leftJoin('plan_archivos', 'codigo_archivos.cod_plan', '=', 'plan_archivos.cod_plan')
            ->select(
                'archivos_resolucion.cod_res',
                'plan_archivos.plan_numero',
                'codigo_archivos.carch_numero'
            )
            ->get();

        $mapa = collect();
        $agrupado = $registros->groupBy('cod_res');

        foreach ($agrupado as $cod_res => $items) {
            $codigos = collect();
            foreach ($items as $item) {
                if (isset($item->plan_numero) && isset($item->carch_numero)) {
                    $codigo = $item->plan_numero . '/' . $item->carch_numero;
                    if (!$codigos->contains($codigo)) {
                        $codigos->push($codigo);
                    }
                }
            }
            $mapa[$cod_res] = $codigos->implode(', ');
        }

        return $mapa;
    }

    private function consultaReportesResolucion()
    {
        return DB::table('resolucions')
            ->join('tomos', 'resolucions.cod_tom', '=', 'tomos.cod_tom')
            ->select(
                'resolucions.cod_res',
                'resolucions.res_numero',
                'resolucions.res_fecha',
                'resolucions.res_desc',
                'resolucions.res_objeto',
                'resolucions.res_tema',
                'resolucions.res_tipo',
                'resolucions.res_gestion',
                'resolucions.res_pdf',
                'tomos.tom_numero',
                'tomos.tom_gestion',
                DB::raw("'' as codigos")
            )
            ->groupBy(
                'resolucions.cod_res',
                'resolucions.res_numero',
                'resolucions.res_fecha',
                'resolucions.res_desc',
                'resolucions.res_objeto',
                'resolucions.res_tema',
                'resolucions.res_tipo',
                'resolucions.res_gestion',
                'resolucions.res_pdf',
                'tomos.tom_numero',
                'tomos.tom_gestion'
            )
            ->orderBy('tomos.tom_gestion', 'desc')
            ->orderBy('resolucions.res_numero');
    }

    public function lista_reportes_resolucion(Request $request){
        // Obtener y limpiar años válidos para el filtro
        $años = collect(
            DB::table('tomos')
                ->whereNotNull('tom_gestion')
                ->distinct()
                ->pluck('tom_gestion')
        )
            ->map(function ($anio) {
                return trim((string) $anio);
            })
            ->filter(function ($anio) {
                return preg_match('/^[0-9]{4}$/', $anio) === 1;
            })
            ->map(function ($anio) {
                return (int) $anio;
            })
            ->filter(function ($anio) {
                $maximo = (int) date('Y') + 1;
                return $anio >= 1900 && $anio <= $maximo;
            })
            ->unique()
            ->sortDesc()
            ->values()
            ->all();

        // Obtener y limpiar tipos de resolución válidos para el filtro
        $tipos = collect(
            DB::table('resolucions')
                ->whereNotNull('res_tipo')
                ->distinct()
                ->pluck('res_tipo')
        )
            ->map(function ($tipo) {
                return strtoupper(trim((string) $tipo));
            })
            ->filter(function ($tipo) {
                return $tipo !== '';
            })
            ->unique()
            ->sort()
            ->values()
            ->all();

        $anioSeleccionado = $request->query('anio');
        if ($anioSeleccionado !== 'todos') {
            $anioSeleccionado = trim((string) $anioSeleccionado);
            if (preg_match('/^[0-9]{4}$/', $anioSeleccionado) !== 1) {
                $anioSeleccionado = null;
            }
        }

        if (($anioSeleccionado === null || $anioSeleccionado === '') && !empty($años)) {
            $anioSeleccionado = (string) $años[0];
        }
        if ($anioSeleccionado !== 'todos' && $anioSeleccionado !== null && $anioSeleccionado !== '' && !in_array((int) $anioSeleccionado, $años, true)) {
            $anioSeleccionado = !empty($años) ? (string) $años[0] : 'todos';
        }
        if ($anioSeleccionado === null || $anioSeleccionado === '') {
            $anioSeleccionado = 'todos';
        }

        $tipoSeleccionado = $request->query('tipo');
        if ($tipoSeleccionado !== 'todos') {
            $tipoSeleccionado = strtoupper(trim((string) $tipoSeleccionado));
            if ($tipoSeleccionado === '') {
                $tipoSeleccionado = null;
            }
        }
        if ($tipoSeleccionado !== 'todos' && $tipoSeleccionado !== null && !in_array($tipoSeleccionado, $tipos, true)) {
            $tipoSeleccionado = !empty($tipos) ? $tipos[0] : 'todos';
        }
        if ($tipoSeleccionado === null || $tipoSeleccionado === '') {
            $tipoSeleccionado = 'todos';
        }

        $consulta = $this->consultaReportesResolucion();
        if ($anioSeleccionado !== 'todos' && $anioSeleccionado !== null && $anioSeleccionado !== '') {
            $consulta->where('tomos.tom_gestion', (int) $anioSeleccionado);
        }
        if ($tipoSeleccionado !== 'todos' && $tipoSeleccionado !== null && $tipoSeleccionado !== '') {
            $consulta->whereRaw('UPPER(TRIM(resolucions.res_tipo)) = ?', [$tipoSeleccionado]);
        }

        $resoluciones = $consulta->simplePaginate(150)->appends($request->query());
        $codigosPorResolucion = $this->mapaCodigosResolucion();

        foreach ($resoluciones as $resolucion) {
            $resolucion->codigos = $codigosPorResolucion->get($resolucion->cod_res, '');
        }

        return view('resoluciones.reporte.lista_reportes_resolucion', compact('resoluciones', 'años', 'tipos', 'anioSeleccionado', 'tipoSeleccionado'));
    }

    public function panel_nuevo_reporte(){
        return view('resoluciones.reporte.panel_nuevo_reporte');
    }

    public function guardar_reporte(Request $request){
        // Aquí se implementará la lógica para guardar el reporte
        // Validar los campos
        $request->validate([
            'fecha' => 'required|date',
            'referencia' => 'required|string',
            'nombre' => 'required|string',
            'descriptor' => 'required|string',
            'codigo' => 'required|string',
            'anio' => 'required|integer',
            'tomo' => 'required|string',
        ]);

        // Aquí puedes insertar en la base de datos
        // Ejemplo:
        // DB::table('reportes_resoluciones')->insert([
        //     'fecha' => $request->fecha,
        //     'referencia' => $request->referencia,
        //     'nombre' => $request->nombre,
        //     'descriptor' => $request->descriptor,
        //     'codigo' => $request->codigo,
        //     'anio' => $request->anio,
        //     'tomo' => $request->tomo,
        //     'created_at' => now(),
        //     'updated_at' => now()
        // ]);

        return redirect()->back()->with('exito', 'Reporte guardado correctamente');
    }

    public function exportar_excel(Request $request){
        $anioSeleccionado = $request->query('anio');
        if ($anioSeleccionado !== 'todos') {
            $anioSeleccionado = trim((string) $anioSeleccionado);
            if (preg_match('/^[0-9]{4}$/', $anioSeleccionado) !== 1) {
                $anioSeleccionado = null;
            }
        }

        $tipoSeleccionado = $request->query('tipo');
        if ($tipoSeleccionado !== 'todos') {
            $tipoSeleccionado = strtoupper(trim((string) $tipoSeleccionado));
            if ($tipoSeleccionado === '') {
                $tipoSeleccionado = null;
            }
        }

        $consulta = $this->consultaReportesResolucion();
        if ($anioSeleccionado !== 'todos' && $anioSeleccionado !== null && $anioSeleccionado !== '') {
            $consulta->where('tomos.tom_gestion', (int) $anioSeleccionado);
        }
        if ($tipoSeleccionado !== 'todos' && $tipoSeleccionado !== null && $tipoSeleccionado !== '') {
            $consulta->whereRaw('UPPER(TRIM(resolucions.res_tipo)) = ?', [$tipoSeleccionado]);
        }

        $resoluciones = $consulta->get();
        $codigosPorResolucion = $this->mapaCodigosResolucion();

        $resoluciones->transform(function ($resolucion) use ($codigosPorResolucion) {
            $resolucion->codigos = $codigosPorResolucion->get($resolucion->cod_res, '');
            return $resolucion;
        });

        $filename = 'reportes_resoluciones_' . date('Y-m-d_His') . '.xlsx';

        return Excel::download(new ReportesResolucionExport($resoluciones), $filename);
    }
}
