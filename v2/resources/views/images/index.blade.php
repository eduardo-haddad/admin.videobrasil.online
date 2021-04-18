@extends('layouts.app')

@section('content')
<div class="page-title">
    <div class="title_left">
        <h3>Busca de Imagens dos Lançamentos</h3>
    </div>
</div>

@include('includes.success')
@include('includes.errors')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Lista de clientes</h2>
                <div class="clearfix"></div>
            </div>
            
            <div class="row">
                <div class="item form-group">
                    @include('includes.clients')
                </div>
            </div>
            
            {{-- <div class="row">
                <div class="form-group col-xs-12 col-sm-6 col-lg-4 pull-right">
                    {{ Form::open(['route' => 'image.index', 'method' => 'GET', 'name' => 'tag_search', 'novalidate']) }}
                    @component('components.searchbox', ['placeholder' => 'Pesquisar por ID']) @endcomponent
                    {{ Form::close() }}
                </div>
            </div> --}}
            
            <div class="row">
                <div class="btn-group pull-right">
                    <button type="button" class="btn btn-primary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Ações MCMV <span class="caret"></span>
                    </button>
                    
                    <ul class="dropdown-menu">
                        <li><a href="#" class="bulk-client-mcmv" data-mcmv="on">Marcar todos empreencimentos do cliente como MCMV</a></li>
                        <li><a href="#" class="bulk-client-mcmv" data-mcmv="off">Desmarcar todos empreencimentos do cliente como MCMV</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="row mt-15 bulk-action">
                <table class="table table-bordered table-fit data-table client-listings listing-images">
                    <thead>
                        <tr>
                            <th class="text-center">MCMV</th>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Grupo</th>
                            <th>Condomínio</th>
                            <th>Quant. Imagens</th>
                            <th>Tour Virtual</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">
                                <label class="cbx">
                                    <input type="checkbox" name="ids" value="" />
                                    <span></span>
                                </label>
                            </td>
                            <td>Client_id</td>
                            <td>Client_name</td>
                            <td>Cond_name</td>
                            <td>0</td>
                            <td>Client_name</td>
                            <td class="virtual-tour ">
                                <div class="form-group">
                                    <input name="url" type="text" class="form-control virtual-tour">
                                </div>
                            </td>
                            <td><a href="{{ url('image/image_id/edit') }}" class="btn btn-primary" target="_blank"><i class="fa fa-edit"></i></a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/app.listings.js') }}"></script>
    <script src="{{ asset('js/app.virtual-tour.js') }}"></script>
@endpush
