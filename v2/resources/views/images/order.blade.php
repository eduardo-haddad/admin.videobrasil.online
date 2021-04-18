@extends('layouts.app')

@section('content')
<div class="page-title">
    <div class="title_left">
        <h3>Orderm geral de tags</h3>
    </div>
</div>

@include('includes.success')
@include('includes.errors')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h5><br>Selecione a ordem de exibição das imagens de todos os empreendimentos utilizando tags<br><br></h5>
                <div class="clearfix"></div>
            </div>
            
            <div class="row">
                <div class="col-sm-12 select-tag">
                    {{ Form::open(['url' => '', 'method' => 'GET', 'name' => '', 'novalidate']) }}
                    <div class="row">
                        <div class="col-sm-12">
                            <label class="control-label">Imagem 1</label>
                            {{ Form::select('tag', $tags, $tag_order[0], ['class' => 'form-control input-sm', 'id' => 'tag-order-1']) }}
                        </div>
                        <div class="col-sm-12">
                            <label class="control-label">Imagem 2</label>
                            {{ Form::select('tag', $tags, $tag_order[1], ['class' => 'form-control input-sm', 'id' => 'tag-order-2']) }}
                        </div>
                        <div class="col-sm-12">
                            <label class="control-label">Imagem 3</label>
                            {{ Form::select('tag', $tags, $tag_order[2], ['class' => 'form-control input-sm', 'id' => 'tag-order-3']) }}
                        </div>
                        <div class="col-sm-12">
                            <label class="control-label">Imagem 4</label>
                            {{ Form::select('tag', $tags, $tag_order[3], ['class' => 'form-control input-sm', 'id' => 'tag-order-4']) }}
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('/js/axios.min.js') }}"></script>
<script>
    $(function(){
        // Tag order
        let tag_order = [];
        $('[id*="tag-order"]').on('change', function(){

            $('[id*="tag-order"]').each(function(){
                tag_order.push($(this).val().toString());
            });

            axios.post(`${BASE_URL}/image/setTagOrderAll`, {tag_order: tag_order})
                .then((response)=>{
                    if(response.status === 200){
                        notify('Sucesso!', response.data, 'success');
                    }
                }).catch((error) => {
                notify('Erro!', error.responseText, 'error');
            });
            tag_order = [];
        });
    });
</script>
@endpush
