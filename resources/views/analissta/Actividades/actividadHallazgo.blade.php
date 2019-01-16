@extends('analissta.layouts.appSideBar')
<?php
use App\ActividadesCalendario;
use App\Presupuesto;
use App\PeligrosHallazgosAccidente;
use App\Hallazgos\Hallazgo;

$xml_GTC45 = simplexml_load_file(base_path("archivosXML/Peligros_GTC45/xml_Peligros_GTC45.xml"));
?>
@section('sistem-menu')
<style>
    .titulo-origenes{
        font-size: 16px;
        font-weight: bold;
        color: #3c3737;
    }
    .a-hallazgo{
        width: auto;
        height: auto;
        max-width: 80%;
        max-height: 25px;
        overflow: hidden;

    }
    .a-hallazgo a{
        text-decoration: underline;
    }
</style>
    @include('analissta.layouts.appTopMenu')

@endsection

@section('sidebar')
    @include('analissta.Actividades.menuActividades')
@endsection

@section('content')
    @section('titulo-encabezado')
        Actividades por Hallazgos
    @endsection
    @section('buttons-submenus')
        <a class="button small" data-open="modal-crear-actividad-obligatoria">Crear Actividad Obligatoria</a>
        <a class="button small" data-open="modal-crear-actividad-sugerida">Crear Actividad Sugerida</a>
        <a class="button small warning" href="{{route('indicadores-actividades')}}">Indicadores Actividades</a>
        <a class="button small alert" href="{{route('actividades')}}">Calendario Actividades</a>
    @endsection
    <div class="row">
        <div class="columns small-12 text-center">
            <div style="background:#0c4d78; color:white"><h5>{{$actividad->nombre}}</h5></div>
        </div>
        <br/>
        <div class="row">
            <div class='columns small-12 medium-7 small-centered text-center'>
                <div class="fieldset">
                    <div><i class="fi-info" style="color:#cc0000;font-size: 28px"></i></div>
                    <div><b><i>**Esta Actividad esta asociada con un Hallazgo</i></b></div>
                    <br/>
                    <div><a href="{{route('hallazgo',['id'=>$actividad->hallazgo_id])}}" class="button small">Ver Hallazgo</a></div>
                </div>
            </div>
        </div>
        
        @if($actividad->estado === '')
            <div class="columns small-12 text-center" >
                <div ><i><b>No existe ninguna programación para esta actividad</b></i></div>
                <br/>
                <div class="columns small-12">
                    <a class="button small" data-open="reveal-programar-actividad">Programar</a>
                </div>
                <div class="small reveal" data-reveal="" id="reveal-programar-actividad">
                    <div class="row columns text-center">
                        <i class="fi-alert" style="font-size:32px; color:red"></i>
                    </div>
                    <div class="row columns text-center">
                        <div><b>Al finalizar la programación de la  Actividad, está no se podrá eliminar del Plan Anual</b></div>
                        <div><b>¿Desea continuar con la programación?</b></div>
                    </div>
                    <br/>
                    <div class="row columns text-center">
                        <a class="button small success-2" href="{{route('programar-actividad',['id'=>$actividad->id,'tipo'=>'hallazgo'])}}">Continuar</a>
                        <a class="button small alert" data-close="">Cancelar</a>
                    </div>
                </div>
            </div>
        @else
            <!--Quiere decir que la actividad esta programada-->
        <div class="row">
            
            <div class='columns small-12 text-center'>
                <br/>
                <b>Porcentaje Total de Ejecución</b>
                @if($actividad->ejecucionTotal < 20)
                    @php $colorBarra = 'warning' @endphp
                @elseif($actividad->ejecucionTotal > 80)
                    @php $colorBarra = 'success' @endphp
                @else
                    @php $colorBarra = '' @endphp
                @endif
                <div role="progressbar" tabindex="0" class="progress <?php echo $colorBarra ?>">
                  <span class="progress-meter" style='width:<?php echo $actividad->ejecucionTotal?>%'>
                    <p class="progress-meter-text">{{$actividad->ejecucionTotal}} %</p>
                  </span>
                </div>
            </div>
        </div>    
        <div class="row">
            <div class="columns small-12" style="border-right: 3px solid lightgrey">
                <div class="row columns text-center">
                    <h5  style="text-decoration: underline"><b>Datos Generales</b></h5>
                </div>
                <div class="row">
                    <div class="columns small-12"><b>Cargo Responsable: </b>{{$actividad->cargo}}</div>
                    <div class="columns small-12"></div>
                </div>
                <div class="row">
                    <div class="columns small-12"><b>Evidencias</b></div>
                    <div class="columns small-12">
                        <textarea style="min-height: 80px" disabled="true">{{$actividad->evidencias}}</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="columns small-12"><b>Observaciones</b></div>
                    <div class="columns small-12">
                        <textarea style="min-height: 80px" disabled="true">{{$actividad->observaciones}}</textarea>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="columns small-12 medium-12">
                <div class="row columns text-center ">
                    <h5 style="text-decoration: underline"><b>Programación</b></h5>
                </div>
                <div class="row">
                    <div class="columns small-5"><b>Frecuencia de ejecución</b></div>
                    <div class="columns small-2 end">{{ucfirst($actividad->frecuencia)}}</div>
                </div>
                <div class="row">
                    <div class="columns small-5"><b>% Total de ejecución</b></div>
                    <div class="columns small-3 end">{{$actividad->ejecucionTotal}} %</div>
                </div>
                <br/>
                <!--esto es lo que debe salir si la valoracion es una matriz general-->
                <div class="row">
                    <div class="columns small-12">
                        @foreach($empresa->centrosTrabajo as $centro)
                            @php
                                $calendarioCentro = ActividadesCalendario::where('sistema_id',$sistema->id)  
                                    ->where('centroTrabajo_id',$centro->id)
                                    ->where('actividad_id',$actividad->id)
                                    ->where('tipo','hallazgo')
                                    ->get();
                            @endphp
                            @if(count($calendarioCentro) > 0)
                                <div class="row">
                                    <div class="columns small-12" style="font-size:16px">
                                        <div class="columns small-12 text-center" style="background-color:#0c4d78; color:white; font-size: 18px">
                                            <b>{{$centro->nombre}}</b>
                                        </div>
                                        <div class="columns small-12 text-center"></div>
                                    </div>
                                    <div class="columns small-12">
                                        <div class="columns small-12 text-center" style="background-color:#666666; color:white"><b>Fechas de Ejecución</b></div>
                                        <div class="columns small-12" style="font-size:16px">
                                            <br/>
                                            <b>Responsable: </b>{{$calendarioCentro[0]->responsable}}
                                        </div>
                                    </div>
                                </div>
                                <br/>
                                <?php
                                    $arr=$arrMeses=[];  
                                    foreach($calendarioCentro as $calendario):
                                        array_push($arr,$calendario->mes);
                                    endforeach;
                                    $arrMeses = array_unique($arr);    
                                    foreach($arrMeses as $mes):
                                ?>        
                                    <div class="row">
                                        <div class="columns small-12 medium-2"><a href="{{route('calendario-actividades-mes',['mes'=>$mes])}}"><b>{{$mes}}</b></a></div>
                                        <?php
                                            $semanas = ActividadesCalendario::where('sistema_id',$sistema->id)  
                                            ->where('centroTrabajo_id',$centro->id)
                                            ->where('actividad_id',$actividad->id)
                                            ->where('tipo','hallazgo')
                                            ->where('mes',$mes)
                                            ->get();

                                            foreach ($semanas as $semana):
                                        ?>
                                        <div class="columns small-12 medium-2 end">
                                            <a href="{{route('calendario-actividades-semana',['mes'=>$mes,'semana'=>$semana->semana])}}" style="text-decoration:underline" title="Ver toda la programacion de esta fecha">
                                                Semana {{$semana->semana}}
                                                @if($semana->ejecutada == "Si")
                                                <i class="fi-check" style="color:#007a00"></i>
                                                @else
                                                <i class="fi-x" style="color:#c12e2a"></i>
                                                @endif
                                            </a>
                                        </div>
                                        <?php
                                            endforeach;
                                        ?>
                                    </div>

                                <?php
                                    endforeach;
                                ?>
                                <hr/>
                            @endif
                        @endforeach
                    </div>
                </div>
                <!--esto es lo que debe salir si la valoracion es una matriz por centro-->
            </div>
            <div id="div-presupuesto" class="columns small-12" >
                @include('analissta.Actividades.presupuestoActividades',['tabla_origen'=>'actividades_hallazgos','tipo'=>'hallazgo','origen'=>'actividad-hallazgo'])
            </div>
            <div class="columns small-12 text-center">
                <br/><br/>
                <div class="reveal small" data-reveal="" id="Reveal-reprogramar2">
                    <div class="row columns text-center">
                        <i class="fi-alert" style="font-size:32px; color:red"></i>
                    </div>
                    <div class="row columns text-center">
                        <div><b>Al realizar una "Reprogramación" perderá toda la información de programación y ejecución actual</b></div>
                        <div><b>¿Desea Continuar?</b></div>
                    </div>
                    <br/>
                    <div class="row columns text-center">
                        <a class="button small success-2" href="{{route('programar-actividad',['id'=>$actividad->id,'tipo'=>'hallazgo'])}}">Continuar</a>
                        <a class="button small alert" data-close="">Cancelar</a>
                    </div>
                    <button class="close-button" data-close="" aria-label="Close modal" type="button">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <a data-idDiv="0" class="button small" data-open="Reveal-reprogramar2">Reprogramar</a>
                <a data-idDiv="0" class="button small success-2" href="{{route('ejecucion-actividad',['id'=>$actividad->id,'tipo'=>'hallazgo'])}}">Ejecucion</a>
            </div>
        </div>
        @endif
    </div>
    <br/>
    <script>
        $(document).ready(function(){
           $("#btn-agregar-presupuesto").on("click",function(){
              $("#frm-presupuesto").removeClass("hide");
              $("#items-presupuesto").addClass("hide");
           }); 
           
           $("#btn-cancelar-presupuesto").on("click",function(){
              $("#frm-presupuesto").addClass("hide");
              $("#items-presupuesto").removeClass("hide");
           }); 
        });
    </script>
    @include('analissta.Actividades.modalCrearActividadObligatoria')
    @include('analissta.Actividades.modalCrearActividadSugerida')
@endsection

