
export class Navigation 
{

    constructor(params)
    {
        this.buttonSelector = params.buttonSelector;
        this.bodySelector = params.bodySelector;
        this.isVisible = false;
    }

    load() 
    {
        this.button = document.querySelector(this.buttonSelector);
        if(!this.button) return;
        this.body = document.querySelector(this.bodySelector);
        this.links = Array.from(this.body.querySelectorAll('li a'));
        this.setBindings();
        this.setEvents();
        this.hide();
    }

    setEvents()
    {
        this.button.addEventListener('click', this.buttonClickHandlder, false);
        this.links.map(function(link){
            link.addEventListener('click', this.linkClickHandler, false);
        }.bind(this));
    }

    setBindings()
    {
        this.buttonClickHandlder = this.buttonClickHandlder.bind(this);
        this.show = this.show.bind(this);
        this.hide = this.hide.bind(this);
        this.linkClickHandler = this.linkClickHandler.bind(this);
    }

    buttonClickHandlder()
    {
        if(this.isVisible === false) {
            this.show();
            this.isVisible = true;
        }
        else {
            this.hide();
            this.isVisible = false;
        }
    }

    linkClickHandler()
    {
        if(this.isVisible) {
            this.hide();
        }
    }

    show()
    {
        this.body.classList.add('show');
        this.body.classList.remove('hide');
        this.button.classList.add('show');
        this.button.classList.remove('hide');
    }

    hide()
    {
        this.body.classList.add('hide');
        this.body.classList.remove('show');
        this.button.classList.remove('show');
        this.button.classList.add('hide');

    }

}