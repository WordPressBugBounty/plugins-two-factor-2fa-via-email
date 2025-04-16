let SS88_2FAVE = {

    init: ()=>{

        return SS88_2FAVE.initDismissButtons();
        
    },
    initDismissButtons: ()=>{

        document.querySelectorAll('.SS88_2FAVE.notice .notice-dismiss').forEach((button) => {

            button.addEventListener('click', (e) => {

                e.preventDefault();
                    
                fetch(ss88.ajax_url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams(requestData = { action: 'SS88_2FAVE_DISMISS_NOTICE', type: e.target.parentElement.dataset.type}),
                }).then(function(response) {
                    return response.json();
                }).then(function(response) {
                    console.log(response);
                }).catch( err => { console.log(err); } );

            });

        });

        return true;

    }

}

window.addEventListener('load', (event) => {

	if(document.querySelector('.SS88_2FAVE.notice')) {
        
        SS88_2FAVE.init();

    }

});