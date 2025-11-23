
export class Wishlist
{

    constructor(params)
    {
        this.addButtonSelector = params.addButtonSelector;
        this.removeButtonSelector = params.removeButtonSelector;
        this.printButtonSelector = params.printButtonSelector;
        this.addButton = null;
        this.removeButtons = null;
        this.printButton = null;
    }

    load()
    {
        let addButton = document.querySelector(this.addButtonSelector);
        let removeButtons = Array.from(document.querySelectorAll(this.removeButtonSelector));
        let printButton = document.querySelector(this.printButtonSelector);

        if(addButton !== null) {
            this.addButton = addButton;
            this.addType = this.addButton.getAttribute('data-type');
            this.addSlug = this.addButton.getAttribute('data-slug');
        }

        if(removeButtons.length) {
            this.removeButtons = removeButtons;            
        }

        if(printButton) {
            this.printButton = printButton;
        }

        this.setBidings();
        this.setEvents();
    }

    setBidings()
    {        
        this.addButtonClickHandler = this.addButtonClickHandler.bind(this);
        this.removeButtonClickHandler = this.removeButtonClickHandler.bind(this);
        this.printButtonClickHandler = this.printButtonClickHandler.bind(this);
    }


    setEvents()
    {
        if(this.addButton !== null)
            this.addButton.addEventListener('click', this.addButtonClickHandler, false);
        if(this.removeButtons !== null && this.removeButtons.length) {
            this.removeButtons.map(function(removeButton){
                removeButton.addEventListener('click', this.removeButtonClickHandler, false);
            }.bind(this));
        }
        if(this.printButton !== null)
            this.printButton.addEventListener('click', this.printButtonClickHandler, false);
    }


    printButtonClickHandler()
    {
        fetch(SITE_URL + 'wishlist/print', {
            method 	: 'POST',
        })
        .then( function( response ){ return response.json()})
        .then( function( json ) {
            if(json.result === 'success') {
                window.open(SITE_URL + 'print/mes-favoris.pdf');
            }
        });
    }


    addButtonClickHandler()
    {   
        let formData = new FormData();
        formData.append('type', this.addType);
        formData.append('slug', this.addSlug);

        fetch(SITE_URL + 'wishlist/add', {
            method 	: 'POST',
            body 	: formData
        })
        .then( function( response ){ return response.json() })
        .then( function( json ){
            if(json.result === 'success') {
                window.location.reload();
            }
            
        });
    }


    removeButtonClickHandler(event)
    {
        let removeButton = event.currentTarget;
        let removeSlug = removeButton.getAttribute('data-slug');

        let formData = new FormData();
        formData.append('slug', removeSlug);

        fetch(SITE_URL + 'wishlist/remove', {
            method 	: 'POST',
            body 	: formData
        })
        .then( function( response ){ return response.json() })
        .then( function( json ){
            if(json.result === 'success') {
                window.location.reload();
            }
            
        });
    }




}