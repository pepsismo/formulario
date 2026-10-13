<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Provincia;
use App\Models\Habilidad;
use App\Models\Domicilio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FormularioController extends Controller
{
    public function mostrar()
    {
        return view('formulario', [
            'provincias' => Provincia::all(),
            'habilidades' => Habilidad::all(),
        ]);
    }

    public function recibir(Request $request)
    {
        $datos = $this->validarDatos($request);

        // 1) Se crea la persona
        $persona = Persona::create([
            'nombre' => $datos['nombre'],
            'apellido' => $datos['apellido'],
            'correo' => $datos['correo'],
            'clave' => Hash::make($datos['clave']),
            'edad' => $datos['edad'],
            'nacimiento' => $datos['nacimiento'],
            'estado_laboral' => $datos['estado_laboral'],
            'provincia_id' => $datos['provincia_id'],
            'presentacion' => $datos['presentacion'],
        ]);

        // 2) Se crea el domicilio a partir de esa persona
        $persona->domicilio()->create([
            'calle' => $datos['calle'],
            'numero' => $datos['numero'],
            'localidad' => $datos['localidad'],
        ]);

        // 3) Se asocian las habilidades
        if ($request->has('habilidades')) {
            $persona->habilidades()->attach($request->habilidades);
        }

        return redirect()->route('formulario.listar')
            ->with('exito', 'Datos guardados correctamente.');
    }

    public function listar(Request $request)
    {
        $filtros = $request->validate([
            'buscar' => ['nullable', 'string', 'max:100'],
            'provincia_id' => ['nullable', 'integer'],
            'estado_laboral' => ['nullable', 'in:Estudiante,Desempleado,Trabajando'],
        ]);

        $personas = Persona::with(['domicilio', 'provincia', 'habilidades'])
            ->when($filtros['buscar'] ?? null, function ($query, $buscar) {
                $query->where(function ($query) use ($buscar) {
                    $query->where('nombre', 'like', "%{$buscar}%")
                        ->orWhere('apellido', 'like', "%{$buscar}%")
                        ->orWhere('correo', 'like', "%{$buscar}%");
                });
            })
            ->when($filtros['provincia_id'] ?? null, fn ($query, $provId) => $query->where('provincia_id', $provId))
            ->when($filtros['estado_laboral'] ?? null, fn ($query, $estado) => $query->where('estado_laboral', $estado))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('listado', [
            'datos' => $personas,
            'filtros' => $filtros,
            'provincias' => Provincia::all()
        ]);
    }

    public function editar(Persona $persona)
    {
        return view('formulario', [
            'dato' => $persona,
            'provincias' => Provincia::all(),
            'habilidades' => Habilidad::all(),
        ]);
    }

    public function actualizar(Request $request, Persona $persona)
    {
        $datos = $this->validarDatos($request, false);

        $updateData = [
            'nombre' => $datos['nombre'],
            'apellido' => $datos['apellido'],
            'correo' => $datos['correo'],
            'edad' => $datos['edad'],
            'nacimiento' => $datos['nacimiento'],
            'estado_laboral' => $datos['estado_laboral'],
            'provincia_id' => $datos['provincia_id'],
            'presentacion' => $datos['presentacion'],
        ];

        if (filled($datos['clave'] ?? null)) {
            $updateData['clave'] = Hash::make($datos['clave']);
        }

        $persona->update($updateData);

        $persona->domicilio()->updateOrCreate(
            ['persona_id' => $persona->id],
            [
                'calle' => $datos['calle'],
                'numero' => $datos['numero'],
                'localidad' => $datos['localidad'],
            ]
        );

        $persona->habilidades()->sync($request->habilidades ?? []);

        return redirect()->route('formulario.listar')
            ->with('exito', 'Datos actualizados correctamente.');
    }

    public function eliminar(Persona $persona)
    {
        $persona->delete();

        return redirect()->route('formulario.listar')
            ->with('exito', 'Registro eliminado correctamente.');
    }

    private function validarDatos(Request $request, bool $claveObligatoria = true): array
    {
        return $request->validate(
            [
                'nombre' => ['required', 'string', 'max:50'],
                'apellido' => ['required', 'string', 'max:50'],
                'correo' => ['required', 'email', 'max:100'],
                'clave' => [$claveObligatoria ? 'required' : 'nullable', 'string', 'min:6', 'confirmed'],
                'edad' => ['required', 'numeric', 'min:16', 'max:80'],
                'nacimiento' => ['required', 'date'],
                'estado_laboral' => ['required', 'in:Estudiante,Desempleado,Trabajando'],
                'provincia_id' => ['required', 'exists:provincias,id'],
                'calle' => ['required', 'string', 'max:100'],
                'numero' => ['required', 'string', 'max:10'],
                'localidad' => ['required', 'string', 'max:100'],
                'presentacion' => ['required', 'string', 'max:100'],
            ],
            [
                'nombre.required' => 'El nombre es obligatorio',
                'apellido.required' => 'El apellido es obligatorio',
                'correo.required' => 'El correo es obligatorio',
                'provincia_id.required' => 'Seleccione una provincia',
                'calle.required' => 'La calle es obligatoria',
                'numero.required' => 'El número es obligatorio',
                'localidad.required' => 'La localidad es obligatoria',
            ]
        );
    }
}
