@can('ler_arquivos',$routa)
        <div class="card card-primary card-outline mb-5">
            <div class="card-header">
                <h3 class="card-title">{{__('Arquivos')}}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                {!! App\Qlib\Qlib::show_files([
                    'token'=>$value['token'],
                ]) !!}
            </div>
        </div>
        @if (isset($value['videos']['videos_alt']) && is_array($value['videos']['videos_alt']))
        <div class="card card-primary card-outline mb-5">
            <div class="card-header">
                <h3 class="card-title">{{__('videos')}}</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                @include('qlib.show_videos',['dados'=>$value['videos']['videos_alt']])
            </div>
        </div>
        @endif
@endcan
