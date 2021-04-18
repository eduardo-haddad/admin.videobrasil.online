$(document).ready( () => {
    

    $(document).on('click', '.add', (e) => {
        var elem = $('.add').parents('div.form-group').first()
        var clone = elem.clone()
        clone.find('select, input').val('')

        $(e.target).parents('div.form-group').after(clone)
    })
})