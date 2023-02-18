
jQuery(document).ready(function($) {

    var DirectoryPlugin = {

        // toggle listing
        ToggleModal: function (e) {
            $('.submit-toggle').on('click', function (event) {
                $(".dp-modal").toggleClass('open');
            });
            $('.close-modal').on('click', function (event) {
                $(".dp-modal").removeClass('open');
            });
		},

        // Form preview image handler
        PreivewImage: function (e) {
            $('#image').on('change', function(e) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('.preview-image img').attr('src', e.target.result).width(120).height('auto');
                    $('.preview-image').removeClass('hidden');
                };
                reader.readAsDataURL($(this)[0].files[0]);
            });
            $('.preview-image span').on('click', function (e) {
                $('.preview-image').addClass('hidden').find('img').attr('src','');
            });
		},

        // trim word similar to wp_trim_words()
        ShortText: function (text) {
            return text.trim().substring(0, 80).split(" ").slice(0, -1).join(" ");
        },

        // fetch listings
        FetchListings: function ( number = 0, paged = 0, pages = 0 ) {
            let data = {};
            if(number){
                data.number = number;
            }
            if(paged){
                data.paged = paged;
            }

            $.ajax(
                {
                    url: DpListings.rest_url,
                    type: "GET",
                    data,
                    beforeSend: function () {
                        // enable loader once user sent request
                        $( '#listings-wrap .loader-wrap' ).removeClass('hidden');
                    },
                    success: function (data) {
                        if(data.success){
                            let listings = '';
                            data.listings.map(item=> {
                                let image_url = item.preview_image ? (DpListings.base_url + item.image_url) : ''
                                listings += `
                                    <div class="cell">
                                        ${(item.preview_image > 0) ? `<img src="${image_url}" alt="${item.id}">` :'' }
                                        <h2>ID: ${item.id}</h2>
                                        <h5>Title: ${item.title}</h5>
                                        <p><b>Content</b>: ${DirectoryPlugin.ShortText(item.content)}...</p>
                                        <p><b>Status</b>: ${item.listing_status}</p>
                                        <p><b>Author</b>: ${item.author}</p>
                                        <p><b>Submitted</b>: ${item.created_at}</p>
                                    </div>
                                `
                            })

                            // inject fetch data to wrapper el
                            $( "#listings-wrap .wrapper" ).html( listings );

                             // hide loader after successfull fetch
                            $( '#listings-wrap .loader-wrap' ).addClass('hidden');

                            // prev button show/hide based on conditon
                            $( '.listings-pagination .prev-btn' ).val(data.prev);
                            if(paged <= 1){
                                $( '.listings-pagination .prev-btn' ).addClass('hidden');
                            }else{
                                $( '.listings-pagination .prev-btn' ).removeClass('hidden');
                            }

                            // page-number active/deactive
                            $( '.listings-pagination .page-number' ).each(function(){
                                if($(this).val() == paged){
                                    $(this).addClass('active');
                                }else{
                                    $(this).removeClass('active');
                                }
                            });

                            // next button show/hide based on conditon
                            $( '.listings-pagination .next-btn' ).val(data.next);
                            if(paged >= pages){
                                $( '.listings-pagination .next-btn' ).addClass('hidden');
                            }else{
                                $( '.listings-pagination .next-btn' ).removeClass('hidden');
                            }
                        }else{
                            console.log('Somethign went wrong.');
                            $( '#listings-wrap .loader-wrap' ).addClass('hidden');
                        }                        
                    },
                    error: function (err) {
                        console.log(err);
                    }
                }
            );
        },

        // upload listing image functionality
        UploadListingImage: function () {
            let image = $('#submit-listing-form #image')[0].files[0];
            let form_data = new FormData();
            form_data.append("file", image);
            form_data.append( 'action', 'upload_listing_image' );
            form_data.append( 'security', DpListings.upload_nonce );
            $.ajax(
                {
                    url: DpListings.ajax_url,
                    type: "POST",
					contentType: false,
					processData: false,
                    data: form_data,
                    beforeSend: function () {
                        $('#submit-listing').prop( "disabled", true );
                        $('.submit-btn .loader-wrap').removeClass( "hidden" );
                    },
                    success: function (data) {
                        if( data.success ) {
                            // Once image is uploaded then insert listing to DB with image id
                            DirectoryPlugin.SubmitListing(data.attachment_id, data.attachment_url);
                        }
                    },
                    error: function (err) {
                        console.log(err);
                    }
                }
            );
        },

        // Listing form submit functionality
        SubmitListing: function ( image_id = 0, image_url = '' ) {
            
            let title   = $('#submit-listing-form #title').val();
            let content = $('#submit-listing-form #content').val();
            let status  = $('#submit-listing-form #status').val();
            let autor   = $('#submit-listing-form #autor').val();
            
            $.ajax(
                {
                    url: DpListings.rest_url,
                    type: "POST",
                    data: {
                        title,
                        content,
                        status,
                        autor,
                        image_id
                    },
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-WP-Nonce', DpListings.rest_nonce);
                        $('#submit-listing').prop( "disabled", true );
                        $('.submit-btn .loader-wrap').removeClass( "hidden" );
                    },
                    success: function (data) {
                        image_url = image_id ? $('.preview-image img').attr('src') : '';
                        if(data.success){
                            let listings = `
                                <div class="cell">
                                    ${image_url && `<img src="${image_url}" alt="${data?.data?.id}">`}
                                    <h2>ID: ${data?.data?.id}</h2>
                                    <h5>Title: ${title}</h5>
                                    <p><b>Content</b>: ${content ? `${content.substring(0, 90)}...` : ''}</p>
                                    <p><b>Status</b>: ${status}</p>
                                    <p><b>Author</b>: ${autor}</p>
                                    <p><b>Submitted</b>: ${data?.data?.created_at}</p>
                                </div>
                            `
                            // inject fetch data to wrapper el
                            $( "#listings-wrap .wrapper" ).prepend( listings );

                            let number = $('#number').val();
                            let total = $('#listings-wrap .wrapper .cell').size();
                            if(total > number ){ // remove element if total items more than 'per_page'
                                let remove = total - number;
                                $('#listings-wrap .wrapper .cell').slice(-remove).remove();
                            }

                            $('#submit-listing').prop( "disabled", false );
                            $('.submit-btn .loader-wrap').addClass( "hidden" );
                            $('.submit-btn .success-msg').removeClass( "hidden" );
                            DirectoryPlugin.CleanUp();
                        }else{
                            DirectoryPlugin.ErrorActions('Something went wrong!');
                        }
                    },
                    error: function (err) {
                        let error = JSON.parse(err.responseText);
                        let msg = error?.code + '. ' + error?.message;
                        DirectoryPlugin.ErrorActions(msg);
                    }
                }
            );

        },

        // Perform once error occured
        ErrorActions: function(msg, status = true) {
            $('#submit-listing').prop( "disabled", false );
            $('.submit-btn .loader-wrap').addClass( "hidden" );
            $('.submit-btn .error-msg').removeClass( "hidden" ).text(msg);
            DirectoryPlugin.CleanUp(3000);
        },

        CleanUp: function (timeout = 1000) {
            setTimeout(() => {
                $('.dp-modal').removeClass( "open" );
                $('.submit-btn .success-msg').addClass( "hidden" );
                $( '#submit-listing-form' )[0].reset();
                $('.preview-image').addClass('hidden');
                $('.preview-image img').attr('src', '');
                $('.submit-btn .message').addClass( "hidden" ).text('');
            }, timeout);
        }

    }

    // Listing modal toggle
    DirectoryPlugin.ToggleModal();

    // Upload form preview image
    DirectoryPlugin.PreivewImage();

    // Fetch listings initialpage load
    // var number = $('#number').val();
    // DirectoryPlugin.FetchListings( number, 0 );

    // listings load by pagination
    $('.listings-pagination button').on('click', function (e) {
        var pages = $('#pages').val();
        var number = $('#number').val();
        var paged = $(this).val();
        DirectoryPlugin.FetchListings( number, paged, pages );
    });

    // Submit lisiting form
    $('#submit-listing-form').submit( function (e) {
        e.preventDefault();

        // Prevent Form submission if field value is empty START
        let title = true;
        let titleEl = $('#submit-listing-form #title')

        $('#submit-listing-form .error').each(function (e) {
            $(this).addClass('hidden');
        })
        if(!titleEl.val()){
            titleEl.next().removeClass('hidden');
            title = false
        }
        if ( title === false ) {
            return false;
        }
        // Prevent Form submission if field value is empty END

        let image = $('#submit-listing-form #image')[0].files[0];
        if(image != undefined ){
            DirectoryPlugin.UploadListingImage(); // submitting form with image
        }else{
            DirectoryPlugin.SubmitListing(); // submitting form without image
        }
    });

});