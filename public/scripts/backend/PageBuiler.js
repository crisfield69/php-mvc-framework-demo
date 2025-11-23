
export class PageBuilder {


    constructor()
    {        
        this.addButtons = Array.from(document.querySelectorAll('[data-type="add-button"]'));
        if(!this.addButtons.length) return;       
        this.deleteButtons = Array.from(document.querySelectorAll('[data-type="delete-button"]')); 
        this.buildArea = document.querySelector('.layout-build-area fieldset');
        this.submitButton = document.querySelector('button[data-type="submit-button"]');
        this.widgetSelectors = [
            'textarea[data-type="text"]', 
            'select[data-type="gallery"]',
            'input[type="file"][data-type="file"]',
            'input[type="file"][data-type="image"]',
            'textarea[data-type="code"]', 
        ];        
        this.setBindings();
        this.setEvents();
    }
    
    
    setEvents() 
    {
        this.addButtons.map(function(button){
            button.addEventListener('click', this.addButtonClickHandler, false);
        }.bind(this));

        this.deleteButtons.map(function(button){
            button.addEventListener('click', this.deleteButtonClickHandler, false);
        }.bind(this));        
        
        this.getWidgets().map(function(widget){
            widget.addEventListener('change', this.widgetChangeHandler, false);
        }.bind(this));

        this.submitButton.addEventListener('click', this.submitButtonClickHandler, false);
    }


    setBindings() 
    {
        this.getNumBloc = this.getNumBloc.bind(this);
        this.addButtonClickHandler = this.addButtonClickHandler.bind(this);
        this.deleteButtonClickHandler = this.deleteButtonClickHandler.bind(this);
        this.getBuildElement = this.getBuildElement.bind(this);
        this.submitButtonClickHandler = this.submitButtonClickHandler.bind(this);
        this.getWidgets = this.getWidgets.bind(this);
        this.widgetChangeHandler = this.widgetChangeHandler.bind(this);
        this.getParentByAttributeValue = this.getParentByAttributeValue.bind(this);
    }


    widgetChangeHandler(event)
    {
        let widget = event.target;
        let bloc = this.getParentByAttributeValue(widget, 'class', 'build-bloc');
        bloc.setAttribute('data-change', 'true');
    }


    getParentByAttributeValue(element, attribute, attributeValue)
    {        
        while ((element = element.parentElement) && !element.getAttribute(attribute, attributeValue));
        return element;        
    }


    addButtonClickHandler(event)
    {
        let button = event.target;
        let num = this.getNumBloc();
        switch(button.getAttribute('data-action')) 
        {
            case 'add-text':
                this.getBuildElement('text', num);
            break;

            case 'add-image':
                this.getBuildElement('image', num);
            break;

            case 'add-file':
                this.getBuildElement('file', num);
            break;   
            
            case 'add-gallery':
                this.getBuildElement('gallery', num);
            break;

            case 'add-code':
                this.getBuildElement('code', num);
            break;
        }
    }


    deleteButtonClickHandler(event)
    {   
        let button = event.target;
        let num = button.getAttribute('data-num');    
        let bloc = document.querySelector('.build-bloc[data-num="'+num+'"]');
        this.buildArea.removeChild(bloc);
        let blocs = Array.from(document.querySelectorAll('.build-bloc'));
        blocs.map(function(bloc, index){
            let num = index + 1;
            bloc.setAttribute('data-num', num);
            bloc.querySelector('button').setAttribute('data-num', num);
            let widget = bloc.querySelector('input') || bloc.querySelector('textarea') || bloc.querySelector('select');
            let type = widget.getAttribute('data-type');
            widget.setAttribute('name', type + '_' + num);
        });
    }


     submitButtonClickHandler()
    {
        let form = document.querySelector('form');
        let widgets = [];
        Array.from(document.querySelectorAll('.build-bloc')).map(function(bloc) {
            let widget = this.getWidget(bloc);
            let hiddenWidget = bloc.querySelector('input[type="hidden"]');
            switch(bloc.getAttribute('data-type'))
            {
                case 'file' :  
                    if(widget.value === '' && hiddenWidget.value === '') {
                        this.buildArea.removeChild(bloc);
                    }
                break;

                case 'image' :
                    if(widget.value === '' && hiddenWidget.value === '') {
                        this.buildArea.removeChild(bloc);
                    }
                break;

                case 'text' :
                    if(CKEDITOR.instances[widget.getAttribute('name')].getData() === '') {
                        this.buildArea.removeChild(bloc);
                    }
                break;

                case 'gallery' :
                    if(widget.value === '' || widget.value === null) {
                        this.buildArea.removeChild(bloc);
                    }
                break;

                case 'code' :
                    if(widget.value === '' || widget.value === null) {
                        this.buildArea.removeChild(bloc);
                    }
                break;
            }
        }.bind(this));

        let num = 1;
        Array.from(document.querySelectorAll('.build-bloc')).map(function(bloc){
            let widget = this.getWidget(bloc);
            let hiddenWidget = bloc.querySelector('input[type="hidden"]');
            switch(bloc.getAttribute('data-type'))
            {
                case 'file' :  
                    widget.name = 'file_' + num;
                    hiddenWidget.name = 'file_' + num;
                break;

                case 'image' :
                    widget.name = 'image_' + num;
                    hiddenWidget.name = 'image_' + num;
                break;

                case 'text' :
                    widget.name = 'text_' + num;
                break;

                case 'gallery' :
                    widget.name = 'gallery_' + num;
                break;

                case 'code' :
                    widget.name = 'code_' + num;
                break;

            }
            num++;
        }.bind(this));

        form.submit();
    }


    getWidgets()
    {
        let widgets = [];  
        Array.from(document.querySelectorAll('.build-bloc')).map(function(bloc){
            this.widgetSelectors.map(function(selector) {
                let widget = bloc.querySelector(selector);
                if(widget !== null) {
                    widgets.push(widget);
                }
            });
        }.bind(this));
        return widgets;
    }


    getWidget(bloc) 
    {
        let widget = null;
        this.widgetSelectors.filter(function(selector){
            let node = bloc.querySelector(selector);
            if(node !== null) {
                widget = node;
            }
        });
        return widget;
    }
    
    
    getBuildElement(type, num)
    {
        let formData = new FormData();
        formData.append('num', num);
        formData.append('type', type); 
        formData.append('content', '');
        fetch( SITE_URL + 'admin/pages/add-bloc', {
            method 	: 'POST',
            body 	: formData
        })
        .then( function( response ){ return response.text() })
        .then( function( html ){
            let wrapper = document.createElement('div');
            wrapper.innerHTML = html;            
            let buildElement = wrapper.querySelector('.build-bloc');
            let deleteButton = buildElement.querySelector('button[data-type="delete-button"]');
            deleteButton.addEventListener('click', this.deleteButtonClickHandler, false);
            this.buildArea.appendChild(buildElement);
            if(type === 'text') {
                CKEDITOR.replace( 'text_' + num, {
                    filebrowserUploadUrl: SITE_URL + 'admin/pages/upload',
                    filebrowserUploadMethod: 'form',
                    clipboard_handleImages : false
                }); 
            }
        }.bind(this));
    }


    getNumBloc()
    {
        let nb_blocs = Array.from(document.querySelectorAll('.build-bloc')).length;
        return nb_blocs + 1;
    }

}

function d(data)
{
    console.log(data);
}