
export class OrderManager {

    constructor(params) {

        this.selectsSelector = params['selectsSelector'];
        this.fetchUrl = params['fetchUrl'];
        this.load();
    }

    load() {
        if(document.querySelectorAll(this.selectsSelector) === null) return;
        this.selects = Array.from(document.querySelectorAll(this.selectsSelector));
        this.setBidings();
        this.setEvents();        
    }

    setEvents() {
        this.selects.map(function(select) {
            select.addEventListener('change', this.selectChangeHandler, false);
        }.bind(this));
    }

    setBidings() {
        this.selectChangeHandler = this.selectChangeHandler.bind(this);
    }

    selectChangeHandler(event) {
        let select = event.target;
        this.currentId = select.getAttribute('data-id');        
        this.currentOrder = select.getAttribute('data-current-order');
        this.newOrder = select.value;
        this.send();
    }

    send() {
        let formData = new FormData();
        formData.append('currentOrder', this.currentOrder);
        formData.append('newOrder', this.newOrder);
        if(this.currentId !== null) {
            formData.append('currentId', this.currentId);
        }        
        fetch(this.fetchUrl, {
            method: 'POST',
            body: formData
        })
        .then(function(response) {
            return response.json()
        })
        .then(function(json) {
            if (json.result === 'success') {
                window.location.reload();
            }
        });
    }
}