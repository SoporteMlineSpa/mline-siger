@if (count($notifications) > 0)
    @foreach ($notifications as $notification)
        @switch($notification->estado)
        @case('PROCESAMIENTO')
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            El Requerimiento con nombre:
            <b>{{$notification->nombre}}</b>,
            fue actualizado al estado:
            <b>{{$notification->estado}}</b>,
            a la fecha de: <b>{{$notification->updated_at}}</b>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @break
        @case('BODEGA')
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            El Requerimiento con nombre:
            <b>{{$notification->nombre}}</b>,
            fue actualizado al estado:
            <b>{{$notification->estado}}</b>,
            a la fecha de: <b>{{$notification->updated_at}}</b>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @break
        @case('RECHAZADO')
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            El Requerimiento con nombre:
            <b>{{$notification->nombre}}</b>,
            fue actualizado al estado:
            <b>{{$notification->estado}}</b>,
            a la fecha de: <b>{{$notification->updated_at}}</b>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @break
        @default
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            El Requerimiento con nombre:
            <b>{{$notification->nombre}}</b>,
            fue actualizado al estado:
            <b>{{$notification->estado}}</b>,
            a la fecha de: <b>{{$notification->updated_at}}</b>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @break
    @endswitch
@endforeach
                    @else
                        <div class="alert alert-secondary">No tienes notificaciones nuevas</div>
                    @endif
