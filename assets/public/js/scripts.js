
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

        // fetch listings
        FetchListings: function ( number = 0, paged = 0 ) {
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
                        $( '#listings-wrap .loader-wrap' ).show();
                    },
                    success: function (data) {
                        if(data.success){
                            let listings = '';
                            data.listings.map(item=> {
                                listings += `
                                    <div class="cell">
                                        <h2>ID: ${item.id}</h2>
                                        <h5>Title: ${item.title}</h5>
                                        <span>${item.content}</span>
                                        <span>Status: ${item.listing_status}</span>
                                        <span>Author: ${item.author}</span>
                                        <span>Created at: ${item.created_at}</span>
                                        <span>Image Url: ${item.preview_image}</span>
                                    </div>
                                `
                            })

                            // inject fetch data to wrapper el
                            $( "#listings-wrap .wrapper" ).html( listings );

                            // hide loader after successfull fetch
                            $( '#listings-wrap .loader-wrap' ).hide();

                            // pagination hide on initial load
                            if( paged == 0 ){
                                $( '.listings-pagination button' ).removeClass('hidden');
                            }

                            // prev button show/hide based on conditon
                            $( '.listings-pagination .prev-btn' ).val(data.prev);
                            if(paged < 1){
                                $( '.listings-pagination .prev-btn' ).addClass('hidden');
                            }else{
                                $( '.listings-pagination .prev-btn' ).removeClass('hidden');
                            }

                            // next button show/hide based on conditon
                            $( '.listings-pagination .next-btn' ).val(data.next);
                            if(paged >= number){
                                $( '.listings-pagination .next-btn' ).addClass('hidden');
                            }else{
                                $( '.listings-pagination .next-btn' ).removeClass('hidden');
                            }
                        }else{
                            console.log('Somethign went wrong.');
                            $( '#listings-wrap .loader-wrap' ).hide();
                        }                        
                    },
                    error: function (err) {
                        console.log(err);
                    }
                }
            );
        }

    }

    // Listing modal toggle
    DirectoryPlugin.ToggleModal();

    // Fetch listings initialpage load
    var number = $('#number').val();
    DirectoryPlugin.FetchListings( number, 0 );

    // listings load by pagination
    $('.listings-pagination button').on('click', function (e) {
        var number = $('#number').val();
        var paged = $(this).val();
        DirectoryPlugin.FetchListings( number, paged );
    });

});