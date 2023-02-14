
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

        FetchListings: function ( number = '', paged = '' ) {
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
                        $( '#listings-wrap .loader' ).show();
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
                            $( "#listings-wrap .wrapper" ).html( listings );
                            $( '#listings-wrap .loader' ).hide();
                            $( '.listings-pagination .prev-btn' ).val(data.prev);
                            if(data.prev < 1){
                                $( '.listings-pagination .prev-btn' ).addClass('hidden');
                            }else{
                                $( '.listings-pagination .prev-btn' ).removeClass('hidden');
                            }
                            $( '.listings-pagination .next-btn' ).val(data.next);
                            if(data.next > data.pages){
                                $( '.listings-pagination .next-btn' ).addClass('hidden');
                            }else{
                                $( '.listings-pagination .next-btn' ).removeClass('hidden');
                            }
                        }else{
                            console.log('Somethign went wrong.');
                            $( '#listings-wrap .loader' ).hide();
                        }                        
                    },
                    error: function (err) {
                        console.log(err);
                    }
                }
            );
        }

    }


    DirectoryPlugin.ToggleModal();


    $('.listings-pagination button').on('click', function (e) {
        var pages = $('#pages').val();
        var number = $('#number').val();
        var paged = $(this).val();
        console.log(paged);
        console.log(pages);
        DirectoryPlugin.FetchListings( number, paged );
    });




});