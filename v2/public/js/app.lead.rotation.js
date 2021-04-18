$(document).ready(() => {
    $('input[type="range"]').on('change', (e) => {
        $('form[name=lead_rotation]').submit()
    })

    $('form[name=lead_rotation]').on('submit', (e) => {
        e.preventDefault();

        $.ajax({
            type: "PATCH",
            url: $(e.target).attr('action'),
            data: $(e.target).serialize(),
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
              'Access-Control-Allow-Methods': 'PATCH'
            },
            success: (success) => {
              console.log(success)
              $('#contact-modal').modal('toggle');
              notify('Sucesso!', success, 'success');
            },
            error: (error) => {
              console.log(error)
              notify('Erro!', error.responseText, 'error');
            }
          });
    })
})