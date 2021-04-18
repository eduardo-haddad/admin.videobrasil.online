$(function(){
    
    let cropper,
    imgEdit,
    tab,
    upload = [],
    queue = [],
    activeTab = 'images',
    imageIdArray = [],
    pondIdArray = [],
    croppedImageId

    const editor = {
        
        open: (file, instructions) => {
            var img = new Image();
            
            if(file.type.match('image.*')) {
                var reader = new FileReader();
                
                reader.readAsDataURL(file);
                reader.onload = function(evt){
                    if( evt.target.readyState == FileReader.DONE) {
                        img.src = evt.target.result;
                        $('#modal').append(img)
                        $("#modal").iziModal('open');
                        cropper = new Cropper(img, {
                            viewMode: 2,
                        })
                    }
                }
                
            }
        },
        onconfirm: (output) => {
            console.log(output)
        },
        oncancel: () => {},
        onclose: () => {}
    }
    
    FilePond.registerPlugin(FilePondPluginImagePreview)
    FilePond.registerPlugin(FilePondPluginImageTransform)
    FilePond.registerPlugin(FilePondPluginImageEdit)
    FilePond.registerPlugin(FilePondPluginImageCrop)
    FilePond.registerPlugin(FilePondPluginFileValidateSize)
    FilePond.registerPlugin(FilePondPluginImageValidateSize)
    FilePond.registerPlugin(FilePondPluginImageResize);
    // FilePond.registerPlugin(FilepondPluginImageSizeMetadata)
    
    FilePond.setOptions({
        server: {
            revert: (image_id) => {
                $.ajax({
                    url: `${BASE_URL}/image/${image_id}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                });
            },
            remove: null,
            load: null
        },
        instantUpload: true,
        // Max file size (kb)
        maxFileSize: '10MB',
        labelMaxFileSizeExceeded: 'O arquivo é grande demais',
        labelMaxFileSize: 'O tamanho máximo do arquivo é {filesize}MB',
        // Min width (px)
        imageValidateSizeMinWidth: '400',
        imageValidateSizeLabelImageSizeTooSmall: 'A imagem é muito pequena',
        imageValidateSizeLabelExpectedMinSize: 'A largura mínima {minWidth}px',
    });
    
    $.each($('.image-upload'), (i, e) => {
        
        let parentTab = $(e).parents('.tab-pane').attr('id');
        let model = (parentTab == 'images') ? 'Image' : 'Floorplan'
        let file_response = null;
        
        upload[parentTab] =
        FilePond.create(e, {
            server: {
                process: {
                    url: `${BASE_URL}/image/upload`,
                    method: 'POST',
                    withCredentials: false,
                    headers: {
                        'Access-Control-Allow-Origin': '*',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    ondata: (formData) => {
                        formData.append('model', model)
                        formData.append('listing_id', LISTING_ID)
                        return formData
                    },
                    timeout: 70000,
                    onload: (response) => {
                        imageIdArray.push(response);
                    },
                    onerror: (response) => {
                        console.log(response);
                    },
                },
                fetch: BASE_URL+'/image/getFile?listing_id='+LISTING_ID+'&model='+model+'&url=',
            },
            status: 0,
            allowMultiple: true,
            allowPaste: true,
            allowRevert: true,
            dropOnPage: true,
            checkValidity: true,
            labelDecimalSeparator: false,
            labelThousandsSeparator: false,
            labelIdle: 'Arraste as imagens/URLs or <span class="filepond--label-action"> Procure no PC </span>',
            labelFileLoading: 'Carregando...',
            labelTapToCancel: 'Clique p/ Cancelar',
            labelTapToRetry: 'Clique p/ tentar novamente',
            labelTapToUndo: 'Clique p/ reverter',
            labelButtonRemoveItem: 'Remover',
            labelButtonAbortItemLoad: 'Abortar',
            allowImageCrop: true,
            allowImageResize: true,
            imageResizeMode: 'contain',
            imageResizeTargetWidth: 1280,
            maxParallelUploads: 1,
            imageEditEditor: editor,
            onaddfilestart: (file) => {
                upload[activeTab].status = 1
            },
            onaddfile: (error, file) => {
                pondIdArray.push(file.id);
                const image = new Image();
                let reader = new FileReader();
                if(file.source == File) {
                    reader.readAsDataURL(file.source)
                }else{
                    reader.readAsDataURL(file.file)
                }
                reader.onload = function(evt){
                    if( evt.target.readyState == FileReader.DONE) {
                        image.src = evt.target.result;
                        image.onload = () => {
                            const infoElement = document.createElement('span')
                            infoElement.appendChild(document.createTextNode('Resolução: '+image.width+'x'+image.height))
                            $(infoElement).addClass('filepond--file-info-sub')
                            $('#filepond--item-'+file.id).find('.filepond--file-info').append(infoElement)
                        }
                    }

                    if (queue[0]){
                        upload[activeTab].addFile(queue[0])
                        queue.shift()
                        upload[activeTab].status = 0
                    }
                    
                }
            },
            onprocessfile: (error, file) => {
                if(error) {return false}

                var request = newXMLHttpRequest({type: 'GET', url: BASE_URL+'/listings?listing_id='+LISTING_ID});

                request.addEventListener('load', function(e){
                    if(this.status == 200){
                        let listing = JSON.parse(this.responseText)
                        
                        //short by date
                        listing.images.sort((a, b) => {
                            let d1 = new Date(a.image_date)
                                d2 = new Date(b.image_date)

                            if(d1 < d2) return 1;
                            if(d1 > d2) return -1;

                            return 0
                        })

                        let lastPageImg = $('#images').find('td:not(.logo) > img').last().attr('src')
                        let lastDbImg = listing.images[0]
                        let row = $('#images').find('tbody > tr:first').clone();

                        if (lastPageImg !== lastDbImg.image_myListings) {
                            $(row).find('input[name=wallpaper]').removeAttr('checked')
                            $(row).find('input[name=wallpaper]').val(lastDbImg.image_id)
                            $(row).find('label').attr('for', 'w'+lastDbImg.image_id)
                            $(row).find('img').attr('src', CDN+'/images/'+lastDbImg.image_myListings)
                            $(row).find('select').attr('data-image-id', lastDbImg.image_id)
                            $(row).find('option:selected').removeAttr('selected')
                            $(row).find('button.delete-image').attr('data-image-id', lastDbImg.image_id)
                            $(row).css('display', '');

                            $('#images').find('tbody').prepend(row)
                        }
                    } else if(this.status == 403){
                        console.log(e)
                    }
                }, false);

                request.send();
            }
        })
        
    })

    document.querySelectorAll('.filepond--root').forEach((element) => {
        element.addEventListener('FilePond:addfile', e => {
            $('.filepond--action-edit-item').on('click', function(e) {
                tab = $(e.currentTarget).parents('.tab-pane').attr('id')
                imgEdit = $(e.currentTarget).parents('.filepond--item').attr('id')
                imgEdit = imgEdit.split("filepond--item-").pop()
                let index = pondIdArray.indexOf(imgEdit);
                croppedImageId = imageIdArray[index];
            })
        });
    })

    $("#modal").iziModal({
        title: 'Cortar imagem',
        headerColor: '#2A3F54',
        background: null,
        icon: null,
        iconText: null,
        iconColor: '',
        rtl: false,
        width: 800,
        top: 50,
        borderBottom: true,
        padding: 0,
        radius: 3,
        zindex: 999,
        focusInput: true,
        group: '',
        loop: false,
        arrowKeys: true,
        navigateCaption: true,
        navigateArrows: true, // Boolean, 'closeToModal', 'closeScreenEdge'
        history: false,
        restoreDefaultContent: false,
        autoOpen: 0, // Boolean, Number
        bodyOverflow: false,
        fullscreen: true,
        openFullscreen: true,
        closeOnEscape: true,
        closeButton: true,
        appendTo: 'body', // or false
        appendToOverlay: 'body', // or false
        overlay: true,
        overlayClose: true,
        overlayColor: 'rgba(0, 0, 0, 0.4)',
        timeout: false,
        timeoutProgressbar: false,
        pauseOnHover: false,
        timeoutProgressbarColor: 'rgba(255,255,255,0.5)',
        transitionIn: 'comingIn',
        transitionOut: 'comingOut',
        transitionInOverlay: 'fadeIn',
        transitionOutOverlay: 'fadeOut',
        onFullscreen: function(){},
        onResize: function(){},
        onOpening: function(){},
        onOpened: function(){},
        onClosing: function(){},
        onClosed: function(e){
            let currentModel = activeTab === "images" ? "image" : "floorplans";
            axios.delete(`${BASE_URL}/image/${LISTING_ID}`, { data: { type: '', image_id: croppedImageId, model: currentModel } }).then(function(){
                cropper.getCroppedCanvas().toBlob((blob) => {
                    upload[tab].removeFile(imgEdit)
                    upload[tab].addFile(blob)
                    cropper.destroy()
                    $('#modal').find('img').remove()
                });
            });

        },
        afterRender: function(){}
    });
    
    $('ul.nav-tabs').on('click', 'a', (e) => {
        activeTab = $(e.currentTarget).parent().attr('role')
    })
    
    $('div.upload').on('click', 'button', (e) => {
        let urls = $(e.delegateTarget).find('textarea').val().split('\n')
        $.each(urls, (key, value) => {
            let ext = value.substr(value.lastIndexOf('.') + 1);
            if(ext === "html" || value === "") return true;
            if(upload[activeTab].status == 0) {
                upload[activeTab].addFile(value);
            } else {
                queue.push(value)
                console.log(queue)
            }
        });
    })
    
});