<div id="modal-crear-inspeccion-sugerida" class="reveal" data-reveal>
    <div class="row columns text-center">
        <h5>Crear Inspección Sugerida</h5>
        <form  accept-charset="UTF-8" method="post" action="{{route('crear-inspeccion-sugerida')}}" name="crear-inspeccion-sugerida">
            {{csrf_field()}}
            <div class="row columns text-center">
                <b>Nombre de la Inspección</b>
            </div>
            <div class="row columns text-center">
                <input type="text" name="nombre" value="{{old('nombre')}}"/>
            </div>
            <div class="row columns text-center">
                <input type="submit" class="button small" value="Crear"/>
                <a class="button small alert" aria-label="Close modal" data-close>Cancelar</a>
            </div>
        </form>
        
    </div>
    <button class="close-button" data-close="" aria-label="Close modal" type="button">
        <span aria-hidden="true">x</span>
    </button> 
</div>
