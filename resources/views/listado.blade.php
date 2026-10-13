<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de datos</title>
    <style>
        body{margin:0;padding:30px;font-family:Arial,sans-serif;background:#eef2f7}
        .listado{max-width:1200px;margin:auto;padding:30px;background:#fff;border-radius:12px;box-shadow:0 5px 15px rgba(0,0,0,.15)}
        h1{color:#234a91;text-align:center}
        .exito{padding:12px;margin-bottom:20px;border-radius:6px;color:#1d7a3a;background:#e6f7ec;border:1px solid #b5e5c8;text-align:center}
        .filtros{display:flex;gap:10px;align-items:end;flex-wrap:wrap;padding:15px;background:#f7f9fc;margin-bottom:20px;border-radius:8px}
        .filtros label{display:block;font-size:13px;font-weight:bold;margin-bottom:4px}
        .filtros input,.filtros select{padding:8px;border:1px solid #aeb8c4;border-radius:5px}
        .boton,.acciones a,.acciones button{padding:9px 13px;border:0;border-radius:5px;background:#234a91;color:#fff;text-decoration:none;cursor:pointer;font-size:14px}
        .limpiar{background:#697586}
        .nuevo{display:inline-block;margin-bottom:18px}
        table{width:100%;border-collapse:collapse;font-size:14px}
        th,td{padding:10px;text-align:left;border-bottom:1px solid #d5dbe3;vertical-align:top}
        th{color:#234a91;background:#f7f9fc}
        .vacio{text-align:center;color:#6b7684;margin:20px 0}
        .acciones{display:flex;gap:6px}
        .acciones form{margin:0}
        .eliminar{background:#b9342c!important}
        .paginacion{margin-top:20px}
    </style>
</head>
<body>
    <div class="listado">
        <h1>Datos guardados</h1>
        @if(session('exito'))
            <p class="exito">{{ session('exito') }}</p>
        @endif 
        <a href="{{ route('formulario.mostrar') }}" class="boton nuevo">Nuevo registro</a>
        
        <form class="filtros" method="GET" action="{{ route('formulario.listar') }}">
            <div>
                <label for="buscar">Buscar</label>
                <input id="buscar" name="buscar" value="{{ $filtros['buscar'] ?? '' }}" placeholder="Nombre, apellido o correo">
            </div>
            <div>
                <label for="provincia_id">Provincia</label>
                <select id="provincia_id" name="provincia_id">
                    <option value="">Todas</option>
                    @foreach($provincias as $provincia)
                        <option value="{{ $provincia->id }}" @selected(($filtros['provincia_id'] ?? '') == $provincia->id)>
                            {{ $provincia->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="estado_laboral">Estado laboral</label>
                <select id="estado_laboral" name="estado_laboral">
                    <option value="">Todos</option>
                    @foreach(['Estudiante','Desempleado','Trabajando'] as $estado)
                        <option value="{{ $estado }}" @selected(($filtros['estado_laboral'] ?? '') === $estado)>
                            {{ $estado }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button class="boton" type="submit">Filtrar</button>
            <a class="boton limpiar" href="{{ route('formulario.listar') }}">Limpiar</a>
        </form>

        @if($datos->isEmpty())
            <p class="vacio">No se encontraron registros.</p>
        @else 
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Edad</th>
                        <th>Nacimiento</th>
                        <th>Estado</th>
                        <th>Habilidades</th>
                        <th>Provincia</th>
                        <th>Domicilio</th>
                        <th>Presentación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($datos as $persona)
                    <tr>
                        <td>{{ $persona->nombre }} {{ $persona->apellido }}</td>
                        <td>{{ $persona->correo }}</td>
                        <td>{{ $persona->edad }}</td>
                        <td>{{ $persona->nacimiento->format('d/m/Y') }}</td>
                        <td>{{ $persona->estado_laboral }}</td>
                        <td>
                            @foreach($persona->habilidades as $habilidad)
                                {{ $habilidad->nombre }}@if(!$loop->last), @endif
                            @endforeach
                        </td>
                        <td>{{ $persona->provincia->nombre ?? 'N/A' }}</td>
                        <td>
                            @if($persona->domicilio)
                                {{ $persona->domicilio->calle }} {{ $persona->domicilio->numero }}, {{ $persona->domicilio->localidad }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $persona->presentacion }}</td>
                        <td class="acciones">
                            <a href="{{ route('formulario.editar', $persona) }}">Editar</a>
                            <form method="POST" action="{{ route('formulario.eliminar', $persona) }}">
                                @csrf @method('DELETE')
                                <button class="eliminar" type="submit" onclick="return confirm('¿Eliminar este registro?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="paginacion">
                {{ $datos->links() }}
            </div>
        @endif
    </div>
</body>
</html>
