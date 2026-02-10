/* library-scripts.js */
(function($){
    // Admin: media uploader for cover
    $(document).on('click', '#library_set_cover', function(e){
        e.preventDefault();
        var frame = wp.media({
            title: 'Select Cover',
            multiple: false,
            library: { type: 'image' }
        });
        frame.on('select', function(){
            var attachment = frame.state().get('selection').first().toJSON();
            $('#_book_cover_id').val(attachment.id);
            $('#_book_cover_preview').attr('src', attachment.url);
        });
        frame.open();
    });
    $(document).on('click', '#library_remove_cover', function(e){
        e.preventDefault();
        $('#_book_cover_id').val('');
        $('#_book_cover_preview').attr('src','');
    });

    // Frontend: toggle favorite
    $(document).on('click', '.library-fav-button', function(e){
        e.preventDefault();
        var btn = $(this);
        var book_id = btn.data('book-id');
        $.post(LibraryAjax.ajax_url, { action: 'library_toggle_favorite', nonce: LibraryAjax.nonce, book_id: book_id }, function(res){
            if (res.success) {
                if (res.data.action === 'added') {
                    btn.addClass('active').text('Remove from My Library');
                } else {
                    btn.removeClass('active').text('Add to My Library');
                }
            } else {
                if (res.data === 'login_required') {
                    alert('Please log in to use this feature.');
                }
            }
        });
    });

    // Frontend: submit rating
    $(document).on('click', '.library-rate-star', function(e){
        e.preventDefault();
        var star = $(this);
        var book_id = star.data('book-id');
        var rating = star.data('value');
        $.post(LibraryAjax.ajax_url, { action: 'library_rate_book', nonce: LibraryAjax.nonce, book_id: book_id, rating: rating }, function(res){
            if (res.success) {
                var avg = res.data.avg;
                var count = res.data.count;
                star.closest('.library-rating').find('.library-rating-avg').text('Average: ' + avg + ' (' + count + ')');
            } else {
                if (res.data === 'login_required') alert('Please log in to rate.');
            }
        });
    });

})(jQuery);
