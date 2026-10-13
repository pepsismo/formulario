<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($dato) ? 'Editar registro' : 'Nuevo registro' }}</title>
    <style>
        body{margin:0;padding:30px;font-family:Arial,sans-serif;background:#eef2f7}
        .formulario{max-width:600px;margin:auto;padding:30px;background:#fff;border-radius:12px;box-shadow:0 5px 15px rgba(0,0,0,.15)}
        h1{color:#234a91;text-align:center}
        .campo{margin-bottom:18px}
        label{display:block;margin-bottom:6px;font-weight:bold}
        .opcion{display:flex;align-items:center;gap:8px;margin-bottom:4px;font-weight:normal}
        .opcion input{width:auto}
        input,select,textarea{width:100%;padding:10px;border:1px solid #aeb8c4;border-radius:6px;box-sizing:border-box}
        textarea{resize:vertical}
        .error{color:#c0392b;font-size:14px;margin:4px 0 0}
        .botones{display:flex;gap:10px}
        button,.volver{flex:1;padding:12px;border:0;border-radius:6px;cursor:pointer;font-size:16px;text-align:center;text-decoration:none;box-sizing:border-box}
        .boton-enviar{color:#fff;background:#234a91}
        .volver{color:#234a91;background:#edf1f7}
        .ayuda{color:#6b7684;font-size:13px;margin:4px 0 0}
        .seccion-titulo { margin: 20px 0 10px; color: #234a91; border-bottom: 2px solid #234a91; padding-bottom: 5px; }
    </style>
</head>
<body>
    @php($editando = isset($dato)) 
    @php($habilidadesSeleccionadas = old('habilidades', $editando ? $dato->habilidades->pluck('id')->toArray() : []))
    
    <div class="formulario">
        <h1>{{ $editando ? 'Editar registro' : 'Registro de datos' }}</h1>
        <form action="{{ $editando ? route('formulario.actualizar', $dato) : route('formulario.recibir') }}" method="POST">
            @csrf 
            @if($editando) @method('PUT') @endif

            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $dato->nombre ?? '') }}">
                @error('nombre')<p class="error">{{ $message }}</p>@enderror
            </div>

            <div class="campo">
                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" name="apellido" value="{{ old('apellido', $dato->apellido ?? '') }}">
                @error('apellido')<p class="error">{{ $message }}</p>@enderror
            </div>

            <div class="campo">
                <label for="correo">Correo electrónico</label>
                <input type="email" id="correo" name="correo" value="{{ old('correo', $dato->correo ?? '') }}">
                @error('correo')<p class="error">{{ $message }}</p>@enderror
            </div>

            <div class="campo">
                <label for="clave">{{ $editando ? 'Nueva contraseña' : 'Contraseña' }}</label>
                <input type="password" id="clave" name="clave">
                @if($editando)<p class="ayuda">Déjala vacía para conservar la actual.</p>@endif 
                @error('clave')<p class="error">{{ $message }}</p>@enderror
            </div>

            <div class="campo">
                <label for="clave_confirmation">Repetir contraseña</label>
                <input type="password" id="clave_confirmation" name="clave_confirmation">
            </div>

            <div class="campo">
                <label for="edad">Edad</label>
                <input type="number" id="edad" name="edad" min="16" max="80" value="{{ old('edad', $dato->edad ?? '') }}">
                @error('edad')<p class="error">{{ $message }}</p>@enderror
            </div>

            <div class="campo">
                <label for="nacimiento">Fecha de nacimiento</label>
                <input type="date" id="nacimiento" name="nacimiento" value="{{ old('nacimiento', isset($dato) ? $dato->nacimiento->format('Y-m-d') : '') }}">
                @error('nacimiento')<p class="error">{{ $message }}</p>@enderror
            </div>

            <div class="campo">
                <label>Estado laboral</label>
                @foreach(['Estudiante','Desempleado','Trabajando'] as $estado)
                    <label class="opcion">
                        <input type="radio" name="estado_laboral" value="{{ $estado }}" @checked(old('estado_laboral', $dato->estado_laboral ?? '') === $estado)>
                        {{ $estado }}
                    </label>
                @endforeach 
                @error('estado_laboral')<p class="error">{{ $message }}</p>@enderror
            </div>

            <div class="campo">
                <label>Habilidades</label>
                @foreach($habilidades as $habilidad)
                    <label class="opcion">
                        <input type="checkbox" name="habilidades[]" value="{{ $habilidad->id }}" @checked(in_array($habilidad->id, (array) $habilidadesSeleccionadas))>
                        {{ $habilidad->nombre }}
                    </label>
                @endforeach 
                @error('habilidades')<p class="error">{{ $message }}</p>@enderror
            </div>

            <div class="campo">
                <label for="provincia_id">Provincia</label>
                <select id="provincia_id" name="provincia_id">
                    <option value="">Seleccionar</option>
                    @foreach($provincias as $provincia)
                        <option value="{{ $provincia->id }}" @selected(old('provincia_id', $dato->provincia_id ?? '') == $provincia->id)>
                            {{ $provincia->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('provincia_id')<p class="error">{{ $message }}</p>@enderror
            </div>

            <div class="seccion-titulo">Domicilio</div>

            <div class="campo">
                <label for="calle">Calle</label>
                <input type="text" id="calle" name="calle" value="{{ old('calle', $dato->domicilio->calle ?? '') }}">
                @error('calle')<p class="error">{{ $message }}</p>@enderror
            </div>

            <div class="campo">
                <label for="numero">Número</label>
                <input type="text" id="numero" name="numero" value="{{ old('numero', $dato->domicilio->numero ?? '') }}">
                @error('numero')<p class="error">{{ $message }}</p>@enderror
            </div>

            <div class="campo">
                <label for="localidad">Localidad</label>
                <input type="text" id="localidad" name="localidad" value="{{ old('localidad', $dato->domicilio->localidad ?? '') }}">
                @error('localidad')<p class="error">{{ $message }}</p>@enderror
            </div>

            <div class="campo">
                <label for="presentacion">Presentación</label>
                <textarea id="presentacion" name="presentacion" maxlength="100" rows="4">{{ old('presentacion', $dato->presentacion ?? '') }}</textarea>
                @error('presentacion')<p class="error">{{ $message }}</p>@enderror
            </div>

            <div class="botones">
                <button type="submit" class="boton-enviar">{{ $editando ? 'Guardar cambios' : 'Enviar' }}</button>
                <a href="{{ route('formulario.listar') }}" class="volver">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
