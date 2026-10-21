let SS88_2FAVE = {

    // Every SS88 plugin's Unicorn Panel notice. Older versions of this plugin had no shared class, so keep the fallback selector
    unicornSelector: '.ss88-unicornpanel-notice, .SS88_2FAVE.notice[data-type="unicornpanel"]',
    storageKey: 'ss88_unicornpanel_dismissed',

    init: ()=>{

        if(typeof ss88 === 'undefined') return false;

        if(document.querySelector(SS88_2FAVE.unicornSelector)) {

            if(SS88_2FAVE.isDismissedLocally()) {

                // Dismissed in this browser but the server didn't save it (blocked request, expired nonce, cache). Hide it and try again
                document.querySelectorAll(SS88_2FAVE.unicornSelector).forEach((notice) => notice.remove());
                SS88_2FAVE.save('unicornpanel');

            }
            else {

                SS88_2FAVE.removeDuplicates();

            }

        }

        // Listen on the document straight away: core adds the X button on DOM ready, so there's no gap where a click isn't saved
        document.addEventListener('click', SS88_2FAVE.onClick, true);

        return true;

    },
    removeDuplicates: ()=>{

        // Other SS88 plugins show the same Unicorn Panel notice, keep only the first one
        document.querySelectorAll(SS88_2FAVE.unicornSelector).forEach((notice, index) => {

            if(index > 0) notice.remove();

        });

    },
    onClick: (e)=>{

        let button = e.target.closest ? e.target.closest('.notice-dismiss') : null;

        if(!button) return;

        // The Unicorn Panel notice, whichever plugin's copy survived removeDuplicates()
        if(button.closest(SS88_2FAVE.unicornSelector)) {

            SS88_2FAVE.rememberDismissed();
            SS88_2FAVE.save('unicornpanel');
            return;

        }

        // This plugin's own notices, e.g. the SMTP warning
        let notice = button.closest('.SS88_2FAVE.notice');

        if(notice && notice.dataset.type) SS88_2FAVE.save(notice.dataset.type);

    },
    save: (type)=>{

        // Each active SS88 plugin can send the Unicorn Panel request, so only send one per page
        let guard = 'unicornpanel' === type ? 'ss88UnicornpanelSaving' : 'ss882faveSaving_' + type;

        if(document.documentElement.dataset[guard]) return;

        document.documentElement.dataset[guard] = '1';

        fetch(ss88.ajax_url, {
            method: 'POST',
            credentials: 'same-origin',
            keepalive: true, // Finishes even if the user navigates away straight after clicking
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ action: 'SS88_2FAVE_DISMISS_NOTICE', type: type, nonce: ss88.nonce }),
        }).then(function(response) {
            if(!response.ok) throw new Error('Notice dismissal failed: HTTP ' + response.status);
            return response.json();
        }).catch( err => {
            delete document.documentElement.dataset[guard];
            console.log(err);
        } );

    },
    isDismissedLocally: ()=>{

        try { return '1' === window.localStorage.getItem(SS88_2FAVE.storageKey); } catch(e) { return false; }

    },
    rememberDismissed: ()=>{

        try { window.localStorage.setItem(SS88_2FAVE.storageKey, '1'); } catch(e) {}

    }

}

// Script is enqueued in the footer, so the notices are already in the DOM. Run now to avoid a visible flash
SS88_2FAVE.init();
