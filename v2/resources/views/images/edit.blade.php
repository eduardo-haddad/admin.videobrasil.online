@extends('layouts.app')

@push('styles')
<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
<link href="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.1/cropper.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.5.1/css/iziModal.min.css">
@endpush

@section('content')
<div class="page-title">
    <div class="title_left">
        <h3>Imagens para lançamento</h3>
    </div>
</div>

@include('includes.success')
@include('includes.errors')

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Carregamento de imagens para laçamento</h2>
                <div class="clearfix"></div>
            </div>
            
            <div id="image-editor">
                <div id="modal">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-3">
                    <h5>Informações do empreendimento</h5>
                    <ul>
                        @if(!empty($listing->newconst))
                        <li>Nome: {{$listing->newconst->listing_title_pt}}</li>
                        @endif
                        <li>Endereço: {{$listing->listing_stname}} - {{$listing->listing_district}}, {{$listing->listing_city}}</li>
                    </ul>
                    <a href="{{env('PORTAL_URL')}}{{propertyUrl($listing)}}" class="btn btn-primary" target="_blank">Link do empreendimento</a>
                </div>
                <div class="col-sm-2">
                    {{-- <div class="form-group">
                        <div class="">
                            <input name="listing_mcmv" type="checkbox" id="mcmv" {{$listing->newconst->listing_mcmv ? 'checked' : ''}}>
                            <label>Minha Casa Minha Vida</label>
                        </div>
                    </div> --}}
                    <div class="can-toggle can-toggle--size-small">
                        <p>Minha casa minha vida</p>
                        @if(!empty($listing->newconst))
                        <input name="listing_mcmv" id="mcmv" type="checkbox" {{$listing->newconst->listing_mcmv == '1' ? 'checked' : ''}}>
                        @endif
                        <label for="mcmv">
                          <div class="can-toggle__switch" data-checked="Sim" data-unchecked="Não"></div>
                        </label>
                    </div>
                </div>
                <div class="col-sm-6">
                    {{ Form::open(['url' => 'listings/'.$listing->listing_id, 'method' => 'PUT', 'name' => 'listing_status'])}}
                    <h5>Status do Empreendimento</h5>
                    <div class="col-12">
                    <span class="label {{ $listing->listing_status == 6 ? 'label-warning' : ($listing->listing_status == 1 ? 'label-success' : 'label-danger') }}"> 
                         {{ $listing->listing_status == 6 ? 'Revisão' : ($listing->listing_status == 1 ? 'Publicado' : 'Despublicado') }}
                    </span>
                    </div>
                    <div class="col-12 mt-15">
                    {{ Form::hidden('status', $listing->listing_status == 6 ? 1 : ($listing->listing_status == 1 ? 6 : 1) ) }}
                    <button class="btn btn-primary">{{$listing->listing_status == 6 ? 'Publicar' : ($listing->listing_status == 1 ? 'Revisar' : 'Publicar') }}</button>
                    {{ Form::close() }}
                    </div>
                </div>
            </div>

            <hr>
            
            <div class="modal fade" tabindex="-1" role="dialog" id="delete">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Deletar todas imagens</h4>
                        </div>
                        <div class="modal-body">
                            <p>Tem certeza que deseja deletar todas as imagens deste imóvel?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-danger">Deletar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <ul class="nav nav-tabs">
                    <li role="images" class="active"><a href="#images" data-toggle="tab">Imagens</a></li>
                    <li role="floorplans"><a href="#floorplans" data-toggle="tab">Plantas</a></li>
                    <li role="tags"><a href="#tags" data-toggle="tab">Ordenação</a></li>
                    <li role="orulo">
                        <a href="#orulo" data-toggle="tab" style="position: relative; width: 77px; height: 45px;">
                            <img style="width: 45px; position: absolute; top: 0" src="{{ asset('images/orulo-logo.png')}}">
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    
                    <div class="tab-pane fade active in" id="images">
                        <div class="row">
                            <div class="col-sm-12 upload">
                                <input type="file" class="image-upload" name="image"/>
                                <div class="form-group">
                                    <label >Upload por URLs <small>(Coloque uma url por linha)</small></label>
                                    <textarea class="url-upload form-control" rows="5" cols="33"></textarea>
                                </div>
                                <div class="pull-right">
                                    <button class="btn btn-primary">Carregar imagens das URLs</button>
                                </div>
                            </div>
                        </div>
                        
                        <hr>

                        <div class="pull-right">
                            {{-- {{ Form::open(['name' => 'delete_all', 'url' => 'image/all', 'method' => 'DELETE']) }}--}}
                            {{-- {{ Form::hidden('listing_id', $listing->listing_id) }}--}}
                            <button class="btn btn-danger delete-all" data-model="image">Deletar todas imagens</button>
                            {{-- {{ Form::close() }} --}}
                        </div>
                        
                        <table class="table table-bordered table-fit">
                            <thead>
                                <tr>
                                    <th>Imagem de destaque</th>
                                    <th>Imagem</th>
                                    <th>Tag</th>
                                    <th>Deletar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr style="display: none;">
                                    <td>
                                        <div class="can-toggle can-toggle--size-large">
                                            <input name="wallpaper" id="" type="radio" value="">
                                            <label for=""><div class="can-toggle__switch" data-checked="Destaque" data-unchecked="Normal"></div></label>
                                        </div>
                                    </td>
                                    <td class="listing-img"><img src=""></td>
                                    <td>{{ Form::select('tag', $tags, null, ['class' => 'form-control input-sm tag-select', 'data-image-id' => '']) }}</td>
                                    <input name="listing_id" type="hidden" value="">
                                    <td><button class="btn btn-danger delete-image" data-image-id="" data-model="image"><i class="fa fa-trash"></i></button></td>
                                </tr>
                                @foreach($images_high as $image_high)
                                    @php
                                        $is_wallpaper = $image_high->image_id == $wallpaper->image_id;
                                        $tag = $image_high->tags()->first();
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="can-toggle can-toggle--size-large">
                                                <input name="wallpaper" id="w{{$image_high->image_id}}" type="radio" value="{{$image_high->image_id}}" {{$is_wallpaper ? 'checked' : ''}}>
                                                <label for="w{{$image_high->image_id}}">
                                                    <div class="can-toggle__switch" data-checked="Destaque" data-unchecked="Normal"></div>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="listing-img"><img src="{{env('CDN_URL')."/images/".$image_high->image_myListings}}"></td>
                                        <td>{{ Form::select('tag', $tags, $tag['tag_id'], ['class' => 'form-control input-sm tag-select', 'data-image-id' => $image_high->image_id]) }}</td>
                                        {{ Form::hidden('listing_id', $listing->listing_id) }}
                                        <td><button class="btn btn-danger delete-image" data-image-id="{{$image_high->image_id}}" data-model="image"><i class="fa fa-trash"></i></button></td>
                                    </tr>
                                @endforeach
                                @foreach($logos as $logo)
                                    <tr>
                                        <td>Logo</td>
                                        @if(!empty($logo->image_myListings))
                                        <td class="listing-img logo"><img src="{{env('CDN_URL')."/images/".$logo->image_myListings}}"></td>
                                        @else
                                            <td class="listing-img logo"><img src="{{env('CDN_URL')."/images/".$logo->image_path}}"></td>
                                        @endif
                                        <td></td>
                                        {{ Form::hidden('listing_id', $listing->listing_id) }}
                                        <td><button class="btn btn-danger delete-image" data-image-id="{{$logo->image_id}}" data-model="image"><i class="fa fa-trash"></i></button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="tab-pane fade" id="floorplans">
                        <div class="row">
                            <div class="col-sm-12 upload">
                                <input type="file" class="image-upload" name="image"/>
                                <div class="form-group">
                                    <label >Upload por URLs <small>(Coloque uma url por linha)</small></label>
                                    <textarea class="url-upload form-control" rows="5" cols="33"></textarea>
                                </div>
                                <div class="pull-right">
                                    <button class="btn btn-primary">Carregar imagens das URLs</button>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="pull-right">
                            <button class="btn btn-danger delete-all" data-model="floorplans">Deletar todas imagens</button>

                        </div>
                        
                        <table class="table table-bordered table-fit">
                            <thead>
                                <tr>
                                    <th>Imagem</th>
                                    <th>Legenda</th>
                                    <th>Deletar</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listing->floorplan as $floorplan)
                                    <tr>
                                        <td class="listing-img"><img src="{{env('CDN_URL')."/images/".$floorplan->image_myListings}}"></td>
                                        <td>
                                            {{ Form::text('caption', $floorplan->image_title, ["class" => "form-control"])  }}
                                            <button class="btn btn-primary caption-edit" data-image-id="{{ $floorplan->image_id }}"><i class="fa fa-edit"></i></button>
                                        </td>
                                        <td>
                                            <button class="btn btn-danger delete-image" data-image-id="{{ $floorplan->image_id }}" data-model="floorplans"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach                               
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="tags">
                        <div class="row">
                            <div class="col-sm-12 select-tag">
                                <h5><br>Selecione a ordem de exibição das imagens deste empreendimento utilizando tags<br><br></h5>
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

                    <div class="tab-pane fade" id="orulo">
                        <div class="row">
                            <div class="col-sm-12 select-tag">
                                {{ Form::open(['novalidate', 'id' => 'orulo-form']) }}
                                <div class="row">
                                    <div class="col-sm-12" id="orulo-view">
                                        <h5><br>URL da ficha do empreendimento</h5>
                                        <div id="orulo-content-url">
                                            <p id="orulo-url-final">
                                                @if(!empty($orulo_url))
                                                    <a target="_blank" href="{{$orulo_url}}">{{$orulo_url}}</a>
                                                @else
                                                    <strong>Nenhuma URL registrada</strong>
                                                @endif
                                            </p>
                                        </div>
                                        <div id="orulo-content-form">
                                            {{ Form::text('orulo-url', $orulo_url, [
                                                'class' => 'form-control input-sm',
                                                'id' => 'orulo-url'
                                            ]) }}
                                        </div>
                                        <div id="orulo-content-buttons">
                                            <button class="btn btn-light" id="orulo-remove" data-edit="0">Remover</button>
                                            <button class="btn btn-primary" id="orulo-edit" data-edit="0">Editar</button>
                                        </div>
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>

                    </div>
                    
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    LISTING_ID = '{{$listing->listing_id}}'
</script>
<script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.js"></script>
<script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-crop/dist/filepond-plugin-image-crop.js"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-validate-size/dist/filepond-plugin-image-validate-size.js"></script>
<script src="https://unpkg.com/filepond/dist/filepond.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.1/cropper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.5.1/js/iziModal.js"></script>
<script src="{{ asset('/js/axios.min.js') }}"></script>
<script src="{{ asset('/js/file-upload.js') }}"></script>
<script>
    $(function(){
        // Delete images
        // $(document).on('click', '.delete-image, .delete-all', (e) = >)
        $(document).on('click', '.delete-image, .delete-all', (e) => {
            e.preventDefault();
            
            let target = $(e.currentTarget);
            
            if(target.hasClass('delete-all')) {
                $('#delete').find('.modal-title').text('Deletar todas imagens');
                $('#delete').find('.modal-body > p').text('Tem certeza que deseja deletar todas as imagens deste imóvel?');
                $('#delete').modal('show');
            } else if(target.hasClass('delete-image')){
                deleteImgs(target)
            }
            currentSend = target;
        });

        $(document).on('click', '#delete .btn-danger', () => {
            deleteImgs(currentSend)
        })
    });

    function deleteImgs(element){

        let type = $(element).hasClass('delete-all') ? 'delete-all' : 'delete-image';

        axios.delete(`${BASE_URL}/image/${LISTING_ID}`, { data: { type: type, image_id: element.data('image-id'), model: element.data('model') } })
            .then(() => {
                $('#delete').modal('hide');

                if(type === 'delete-all') {
                    $(element).parents('.tab-pane').find('td').fadeOut(500);
                    $('html, body').animate({ scrollTop: 0 }, 'slow');
                } else {
                    $(element).parent().parent().fadeOut(500);
                }
                notify('Sucesso!', 'Imagens deletadas', 'success');
            })
            .catch((error) => {
                notify('Erro!', error.responseText, 'error');
            });
    }

    // Wallpaper
    $(document).on('click', 'input:radio[name=wallpaper]', (e) => {
        let image_id = $(e.currentTarget).val();
        axios.post(`${BASE_URL}/image/wallpaper`, { image_id: image_id, listing_id: LISTING_ID })
            .then((response) => {
                if(response.status === 200){
                    notify('Sucesso!', response.data, 'success');
                }
                if(response.status === 204){
                    // $(`:input[value="${image_id}"]`).prop("checked", false);
                    // $(`:input[value="{{ $wallpaper->image_id }}"]`).prop("checked", true);
                    notify('Imagem selecionada, mas atenção!', 'Imagem com baixa resolução. Considere selecionar uma imagem com resolução alta, de pelo menos 1280px"', 'warning');
                }
            }).catch((error) => {
                notify('Erro!', error.responseText, 'error');
            });
    });

    // Tag order
    let tag_order = [];
    $(document).on('change', '[id*="tag-order"]', (e) =>{

        $('[id*="tag-order"]').each(function(){
            tag_order.push($(this).val().toString());
        });

        axios.post(`${BASE_URL}/image/setTagOrder`, {tag_order: tag_order, listing_id: LISTING_ID})
            .then((response)=>{
                if(response.status === 200){
                    notify('Sucesso!', response.data, 'success');
                }
            }).catch((error) => {
            notify('Erro!', error.responseText, 'error');
        });
        tag_order = [];
    });

    // Image tagging
    $(document).on('change', '.tag-select', (e) =>{
        let tag_id = $(e.currentTarget).val();
        let image_id = $(e.currentTarget).data('image-id');
        axios.post(`${BASE_URL}/image/setTag`, {tag_id: tag_id, image_id: image_id, listing_id: LISTING_ID})
            .then((response)=>{
                if(response.status === 200){
                    notify('Sucesso!', response.data, 'success');
                }
            }).catch((error) => {
            notify('Erro!', error.responseText, 'error');
        });

    });

    // Floorplan caption
    $(document).on('input',
        '#floorplans > table > tbody > tr > td > input[name=caption]',
        function(){
            $(this).addClass('changed');
        });
    $(document).on('click',
        '#floorplans > table > tbody > tr > td > button.caption-edit',
        function(e){
            e.preventDefault();
            let imageId = $(this).data('image-id');
            let changedInput = $(this).parent().find('input[name=caption]');
            if(changedInput.hasClass('changed')){
                axios.post(`${BASE_URL}/floorplans/setCaption/${imageId}`, {new_caption: changedInput.val(), listing_id: LISTING_ID})
                    .then((response) => {
                        if(response.status === 200){
                            notify('Sucesso!', response.data, 'success');
                        }
                    }).catch((error) => {
                    notify('Erro!', error.responseText, 'error');
                });
                changedInput.removeClass('changed');
            }
        }
    );

    // Listing
    $('form[name=listing_status]').on('submit', (e) => {
        e.preventDefault();
        let target = $(e.currentTarget);
        let status = target.find('input[name=status]').val()
        notify('Aguarde', 'Aguarde, ficha sendo '+(status == 1 ? 'publicada' : 'transferida para revisão')+'...', 'info')

        target.find('button').attr({
            'disabled': 'disabled',
            'block': 'block'
        })

        axios.put(target.attr('action') , target.serialize())
        .then((response) => {
            target.find('button').removeAttr('disabled', 'block')
            if(status == 6) {
                target.find('span').text('Revisão')
                target.find('span').removeClass('label-success')
                target.find('span').addClass('label-warning')
                target.find('button').text('Publicar')
                target.find('input[name=status]').val('1')

                notify('Sucesso', 'Ficha transferida para revisão', 'success')
            }else if (status == 1) {
                target.find('span').text('Publicado')
                target.find('span').removeClass('label-warning')
                target.find('span').addClass('label-success')
                target.find('button').text('Revisar')
                target.find('input[name=status]').val('6')

                notify('Sucesso', 'Ficha publicada', 'success')
            }
        })
        .catch((error) => {
            console.log(error)
        })
    })

    /* ORULO */

    let orulo_url;
    let orulo_url_original = '';
    let lock = false;

    // Update url variable on keypress
    $(document).on('input', '#orulo-url', function(e){
        orulo_url = e.target.value;
    });

    // Edit button
    $(document).on('click', '#orulo-edit', function(e){

        e.preventDefault();

        // "lock" variable to prevent multiple clicks
        if(!lock){

            lock = true;

            orulo_url = orulo_url_original = $('#orulo-url').val();

            // View mode:
            // 0 -> preview
            // 1 -> edit
            let viewMode = $(e.target).attr('data-edit');

            // Enter edit mode
            if(viewMode === '0'){
                $('#orulo-remove, #orulo-edit').attr('data-edit', '1');
                // Show input field
                $('#orulo-content-url').fadeOut(function(){
                    $('#orulo-content-form').fadeIn();
                    // Update button text
                    $('#orulo-remove').html('Cancelar');
                    $('#orulo-edit').html('Salvar');
                    lock = false;
                });
            }
            // Save and return to preview mode
            else {
                saveOrulo(LISTING_ID, orulo_url);
            }
        }
    });

    // Remove or Cancel
    $(document).on('click', '#orulo-remove', function(e){
        e.preventDefault();
        let viewMode = $(e.target).attr('data-edit');

        // "Remove" button
        if(viewMode === '0'){
            if(confirm('Deseja remover a URL?')){
                axios.put('/v2/orulo/set', {'listing_id': LISTING_ID, 'remove_orulo_url': 1})
                    .then(function(){
                        // Reset values
                        $('#orulo-url-final').html('<strong>Nenhuma URL registrada</strong>');
                        $('#orulo-url').val('');

                        // Hide form and show url
                        $('#orulo-content-form').fadeOut(function(){
                            $('#orulo-content-url').fadeIn();
                            // Update button text
                            $('#orulo-remove').html('Remover');
                            $('#orulo-edit').html('Editar');
                            lock = false;
                        });
                    });
            }
        }
        // "Cancel" button
        else {
            // Return to preview mode
            $('#orulo-remove, #orulo-edit').attr('data-edit', '0');

            $('#orulo-content-form').fadeOut(function(){
                // Reset values
                if(orulo_url_original === ''){
                    $('#orulo-url-final').html('<strong>Nenhuma URL registrada</strong>');
                } else {
                    $('#orulo-url-final').html(`<a href='${orulo_url_original}'>${orulo_url_original}</a>`);
                }
                $('#orulo-url').val(orulo_url_original);
                // Show url
                $('#orulo-content-url').fadeIn();
                $('#orulo-remove').html('Remover');
                $('#orulo-edit').html('Editar');
            });
        }

    });

    // Disable regular form submit
    $('#orulo-form').submit(function(e){
        e.preventDefault();
    });

    // Save form with "Enter" key (13)
    $('#orulo-url').on('keydown', (k) => {
        if(k.which === 13){
            k.preventDefault();
            saveOrulo(LISTING_ID, orulo_url);
        }
    });

    const saveOrulo = (listing_id, url) => {
        // Return to preview mode
        $('#orulo-remove, #orulo-edit').attr('data-edit', '0');

        axios.put('/v2/orulo/set', {'listing_id': listing_id, 'orulo_url': url})
            .then(function(response){
                // Build new link with updated url
                if(response.data === ''){
                    $('#orulo-url-final').html('<strong>Nenhuma URL registrada</strong>');
                } else {
                    $('#orulo-url-final').html(`<a href='${response.data}'>${response.data}</a>`);
                }

                // Hide form and show url
                $('#orulo-content-form').fadeOut(function(){
                    $('#orulo-url').val(response.data);
                    $('#orulo-content-url').fadeIn();
                    // Update button text
                    $('#orulo-remove').html('Remover');
                    $('#orulo-edit').html('Editar');
                    lock = false;
                });
            });
    }

    // MCMV
    $('input[name=listing_mcmv]').on('change', (e) => {
        if ($(e.target).is(':checked')) {
            $(e.target).attr('value', '1');
        } else {
            $(e.target).attr('value', '0');
        }

        axios.put('/v2/listings/'+LISTING_ID, {'listing_mcmv': $(e.target).val()})
            .then((r)=>{
                console.log(r)
            })
    })
</script>
@endpush
