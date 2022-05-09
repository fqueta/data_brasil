@if (isset($config['svg_file']) && isset($config['dados']))
<style type="text/css">

        .input-group-mapa {
            max-width: 250px;
            z-index: 2;
        }

        .mini-card {
            position: absolute;
            min-width: 250px;
            display: none;
            z-index: 2;
        }

        .mini-card.active {
            position: absolute;
            min-width: 250px;
            display: block;
        }

        .mini-card-geral {
            position: absolute;
            right: 1rem;
            bottom: 1rem;
            min-width: 250px;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 2;
        }

        .mini-card-geral h6 {
            font-size: 15px;
        }

        .mini-card-geral p {
            font-size: 13px;
            margin-bottom: 8px;
        }
</style>
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <div class="input-group input-group-sm input-group-mapa">
            <div class="input-group-prepend">
                <label class="input-group-text" for="select_bairro">Bairro</label>
            </div>
            <select class="custom-select" id="select_bairro">
                <option selected>Selecione o bairro...</option>
                <option value="1">Camponesa</option>
                <option value="2">Córrego Pereira</option>
                <option value="3">Matozinhos</option>
            </select>
        </div>
        <div class="input-group input-group-sm input-group-mapa">
            <div class="input-group-prepend">
                <label class="input-group-text" for="select_quadra">Quadra</label>
            </div>
            <select class="custom-select" id="select_quadra">
                <option selected>Selecione a quadra...</option>
                <option value="1">70.2</option>
                <option value="2">22.4</option>
            </select>
        </div>
    </div>
    <div class="card-body" style="overflow: auto;height: 600px;">
            <!-- Mini card de informações do lote:  -->

            <!-- A class "active" junto com "mini-card" muda o display para exibir -->

            <div class="card mini-card border-0 shadow active">
                <div class="card-body p-0">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action py-1 px-2">Informação 1 <i
                                class="fa fa-link ml-3"></i></a>
                        <a href="#" class="list-group-item list-group-item-action py-1 px-2">Informação 2 <i
                                class="fa fa-link ml-3"></i></a>
                        <a href="#" class="list-group-item list-group-item-action py-1 px-2">Informação 3 <i
                                class="fa fa-link ml-3"></i></a>
                    </div>
                </div>
            </div>

            <!-- Mini card de informações do lote termina aqui! -->

            <!-- Mini card de informações gerais:  -->

            <div class="card mini-card-geral border-light">
                <div class="card-body text-light text-center p-2">
                    <h6 class="border-bottom border-secondary pb-2">Camponesa</h6>
                    <p><b>Quadra:</b> 70.2</p>
                    <p><b>Total de lotes:</b> XX</p>
                    <p><b>Total de famílias:</b> XX</p>
                </div>
            </div>
            <!--include('mapas.'.$config['local'].'.'.$config['dados']['id'])-->
            <?xml version="1.0" encoding="utf-8"?>
            @php
                $p = $_SERVER['DOCUMENT_ROOT'].$config['svg_file'];
                include $p;
            @endphp
        <div class="painel-zoom" style="position: absolute;left:10px;bottom:50%;width: 40px;">
            <button type="button" onclick="zoom('p')" title="{{__('Aumentar mapa')}}" id="zoom-p" class="btn btn-outline-primary mb-1"><i class="fas fa-plus"></i></button>
            <button type="button" onclick="zoom('r')" id="zoom-r"  title="{{__('Restaurar tamanho')}}" class="btn btn-outline-secondary mb-1" ><i class="fas fa-sync"></i></button>
            <button type="button" onclick="zoom('m')" title="{{__('Diminuir mapa')}}" id="zoom-m" class="btn btn-outline-primary" ><i class="fas fa-minus"></i></button>
        </div>
    </div>
</div>
@endif
