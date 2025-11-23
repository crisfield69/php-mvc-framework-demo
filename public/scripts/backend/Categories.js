
export class Categories {
    
    constructor()
    {
        let selectElements = Array.from(document.querySelectorAll('select[data-type="ordre"]'));
        if(!selectElements.length) return;        
        this.selectChangeHandler = this.selectChangeHandler.bind(this);
        selectElements.map(function(select){
            select.addEventListener('change', this.selectChangeHandler, false);
        }.bind(this));

    }

    selectChangeHandler (event)
    {
        let selectElement = event.target;
        let parentId = selectElement.parentNode.parentNode.getAttribute('data-parent');
        let currentOrder = selectElement.getAttribute('data-ordre');
        let currentId = selectElement.getAttribute('data-id');
        let newOrder = selectElement.options[selectElement.selectedIndex].value;
        
        let formData = new FormData();    
        formData.append('parentId', parentId);
        formData.append('currentOrder', currentOrder);
        formData.append('currentId', currentId);
        formData.append('newOrder', newOrder);    
        
        fetch( SITE_URL + 'admin/categories/update-order', {
            method 	: 'POST',
            body 	: formData
        })
        .then( function( response ){ return response.json() })
        .then( function( json ) {
            if(json.result === 'success') {
                window.location.reload();
            }
        });
    }

}