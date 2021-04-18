$(function(){

    let cropper

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
    // FilePond.registerPlugin(FilepondPluginImageSizeMetadata)

    FilePond.setOptions({
        server: {
            process: {
                url: `${BASE_URL}/image/upload`,
                method: 'POST',
                withCredentials: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'listing-id': LISTING_ID
                },
                timeout: 7000,
                onload: null,
                onerror: (response) => console.log(response),
            },
            revert: (image_id) => {
                $.ajax({
                    url: `${BASE_URL}/image/${image_id}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                });
            },
            fetch: null,
            remove: null,
            load: null
        },
        instantUpload: true
    });

    let imgEdit, tab;
    let upload = [];

    $.each($('.image-upload'), (i, e) => {

        let parentTab = $(e).parents('.tab-pane').attr('id')

        upload[parentTab] =
            FilePond.create(e, {
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
                imageResizeTargetWidth: 1280,
                imageEditEditor: editor,
                onaddfile: (error, file) => {
                    const image = document.createElement('img');
                    let reader = new FileReader();
                    reader.readAsDataURL(file.source);
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
                    }
                }

            })

    })

    document.querySelectorAll('.filepond--root').forEach((element) => {
        element.addEventListener('FilePond:addfile', e => {
            $('.filepond--action-edit-item').on('click', function(e) {
                tab = $(e.currentTarget).parents('.tab-pane').attr('id')
                imgEdit = $(e.currentTarget).parents('.filepond--item').attr('id')
                imgEdit = imgEdit.split("filepond--item-").pop()
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
        onClosed: function(){
            cropper.getCroppedCanvas().toBlob((blob) => {
                upload[tab].removeFile(imgEdit)
                upload[tab].addFile(blob)

                cropper.destroy()
                $('#modal').find('img').remove()
            })
        },
        afterRender: function(){}
    });

});