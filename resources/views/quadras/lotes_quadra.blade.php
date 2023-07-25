<div class="row">
    <div class="col-md-12 mens">
    </div>
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">{{__('Mapas dos lotes')}}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool d-print-none" data-card-widget="collapse" title="Collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                {!! App\Http\Controllers\MapasController::exibeMapas(@$config['mapas']['config']) !!}
            </div>
        </div>
    </div>
    {{-- @include('qlib.btnedit') --}}
</div>

