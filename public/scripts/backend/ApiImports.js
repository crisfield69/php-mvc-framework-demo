
export class ApiImports 
{
    constructor()
    {       
        let apiButtons = document.querySelectorAll('[data-type="api-button"]');
        if(!apiButtons) return;
        this.apiButtons = Array.from(apiButtons);
        this.timer = document.querySelector('.layout-timer');
        this.messages = Array.from(document.querySelectorAll('.layout-message'));
        this.setBidings();
        this.setEvents();
    }

    setEvents()
    {
        this.apiButtons.forEach(function(button){
            button.addEventListener('click', this.apiButtonsEventHandler, false);
        }.bind(this));
    }

    setBidings()
    {
       this.apiButtonsEventHandler = this.apiButtonsEventHandler.bind(this);
       this.setMessage = this.setMessage.bind(this);
       this.getMessage = this.getMessage.bind(this);
       this.setTimer = this.setTimer.bind(this);
       this.unsetTimer = this.unsetTimer.bind(this);
       this.debug = this.debug.bind(this);
    }

    apiButtonsEventHandler(event)
    {
        let button = event.target;
        let action = button.getAttribute('data-action');
        let formData = new FormData();
        formData.append('action', action);
        this.setTimer();
        fetch( SITE_URL + 'api/dispatchActions.php', {
            method 	: 'POST',
            body 	: formData
        })
        .then( function( response ){ 
            this.debug(response);
            return response.json(); 
        }.bind(this))
        .then( function( json ){
            if(json.result === 'error') {
                this.unsetTimer();
                this.setMessage(json, 'error');
            }
            if(json.result === 'success'){
                this.unsetTimer();
                this.setMessage(json, 'success');
            }

        }.bind(this));
    }
    
    debug(response) 
    {
        console.log(response.body);
    }

    setTimer()
    {
        this.timer.style.display = 'block';
    }

    unsetTimer()
    {
        this.timer.style.display = 'none';
    }

    setMessage(json, type)
    {
        let message = this.getMessage(json.action)[0];
        message.classList.add(type);
        message.innerHTML = json.message;
    }

    getMessage(action)
    {
        return this.messages.filter(function(message){
            return (message.getAttribute('data-action') === action);
        });
    }


}