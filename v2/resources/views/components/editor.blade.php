{{ $slot }}

@push('scripts')

  <script src="https://cdn.tiny.cloud/1/2i441v83i5sqjowuv715g52bx7nbuj32bv4e6n23pjyf7a7z/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

  <script>
    tinymce.init({
      selector: '.editor',
      branding: false,
      language_url: BASE_URL + '/lang/pt/tinymce.js',
      plugins: 'lists link image imagetools wordcount code emoticons',
      menu: {
        file: {title: 'File', items: 'newdocument'},
        edit: {title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall'},
        format: {title: 'Format', items: 'bold italic underline strikethrough superscript subscript | formats | removeformat'}
      },
      toolbar: 'emoticons| undo redo | fontsizeselect styleselect | bold italic blockquote | alignleft aligncenter alignright alignjustify | bullist numlist | link unlink image | code',
      convert_urls: false,
      automatic_uploads: true,
      emoticons_append: {
        custom_mind_explode: {
          keywords: ['brain', 'mind', 'explode', 'blown'],
          char: '🤯'
        }
      },
      content_css: [
        '//www.tiny.cloud/css/codepen.min.css'
      ],
      images_reuse_filename: true,
      image_list: '{{ route('files.index') }}',
      images_upload_handler: function (blobInfo, success, failure) {
        var request = new XMLHttpRequest();
        request.open('POST', '{{ route('files.store') }}');
        request.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        request.setRequestHeader('Accept', 'application/json');

        request.onload = function() {
          if (request.status != 200) {
            failure('HTTP Error: ' + request.status);
            return;
          }

          var json = JSON.parse(request.responseText);

          if (!json || typeof json.location != 'string') {
            failure('Invalid JSON: ' + request.responseText);
            return;
          }

          success(json.location);
        };

        var formData = new FormData();
        formData.append('image', blobInfo.blob(), blobInfo.filename());
        request.send(formData);
      }
    });
  </script>

@endpush
