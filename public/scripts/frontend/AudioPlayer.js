
export class AudioPlayer {


    constructor(params)
    {
        this.containerSelector = params.containerSelector;
        this.viewerSelector = params.viewerSelector;
        this.previousAudio = null;
        this.currentAudio = null;
        this.player = null;
        this.playerCursor = null;
        this.viewer = null;
        this.photoClickHandlerCallback = params.photoClickHandlerCallback;
    }


    load()
    {
        this.containers = Array.from(document.querySelectorAll(this.containerSelector));
        this.viewerContainer = document.querySelector(this.viewerSelector);
        if(!this.containers.length) return;
        this.audioWidgets = [];
        this.containers.map(function(container, index){
            let title = container.getAttribute('data-title');
            title += (container.getAttribute('data-subtitle') !== '')? ' | ' + container.getAttribute('data-subtitle') : '';
            let audio = container.querySelector('audio');
            let url = audio.getAttribute('src');
            let audioWidget = new AudioWidget(audio, title, url, index);
            audioWidget.load();
            this.audioWidgets.push(audioWidget);
        }.bind(this));
        this.setBindings();
        this.setPlayer();
        this.setEvents();
    }


    setBindings() 
    {
        this.setPlayer = this.setPlayer.bind(this);
        this.photoClickHandler = this.photoClickHandler.bind(this);
        this.play = this.play.bind(this);    
        this.getAudioWidgetByName = this.getAudioWidgetByName.bind(this);
        this.showPlayer = this.showPlayer.bind(this);
        this.hidePlayer = this.hidePlayer.bind(this);
        this.getElement = this.getElement.bind(this); 
        this.playerButtonClickHandler = this.playerButtonClickHandler.bind(this);
        this.switchButton = this.switchButton.bind(this);
        this.playerCursorMouseDownHandler = this.playerCursorMouseDownHandler.bind(this);
        this.windowMouseMoveHandler = this.windowMouseMoveHandler.bind(this);
        this.windowMouseUpHandler = this.windowMouseUpHandler.bind(this);
        this.displayViewer = this.displayViewer.bind(this);
        this.removeViewer = this.removeViewer.bind(this);
    }


    setPlayer()
    {
        let player = this.getElement('div', 'layout-player');
        let playerCursor = this.getElement('p', 'cursor');
        let playerRange = this.getElement('div', 'range');
        let playerButton = this.getElement('p', 'btn-play-pause');
        let playerTime = this.getElement('p', 'time');
        let playerTitle = this.getElement('div', 'title');

        player.appendChild(playerRange);
        playerRange.appendChild(playerCursor);
        player.appendChild(playerRange);
        player.appendChild(playerButton);
        player.appendChild(playerTime);
        player.appendChild(playerTitle);

        this.player = player;
        this.playerCursor = playerCursor;
        this.playerRange = playerRange;
        this.playerButton = playerButton;
        this.playerTime = playerTime;
        this.playerTitle = playerTitle;
        
        document.body.appendChild(this.player);
        this.hidePlayer();
    }


    setEvents()
    {
        this.containers.map(function(container){
            let photo = container.querySelector('.layout-photo');
            photo.addEventListener('click', this.photoClickHandler, false);
        }.bind(this), false);
        this.audioWidgets.map(function(audio){
            audio.addPlayEventListener(this.play);
        }.bind(this), false);
        this.playerButton.addEventListener('click', this.playerButtonClickHandler, false);
        this.playerCursor.addEventListener('mousedown', this.playerCursorMouseDownHandler, false);
    }


    photoClickHandler(event)
    {   
        let photo = event.currentTarget;
        let container = photo.parentNode;

        let audioName = container.querySelector('audio').getAttribute('data-name');
        this.currentAudio = this.getAudioWidgetByName(audioName);        

        if(this.previousAudio !== null && this.previousAudio === this.currentAudio) {
            if(this.previousAudio.isPLaying() === false) {
                this.currentAudio.play();
                this.setTitle();
                this.showPlayer();
                this.displayViewer(container);
            }
            else {
                this.previousAudio.stop();
                this.currentAudio.stop();
                this.hidePlayer();
                this.removeViewer();
            }
        }
        else{
            if(this.previousAudio !== null) {
                this.previousAudio.stop();
                this.removeViewer();
            }
            this.currentAudio.play();
            this.setTitle();
            this.showPlayer();
            this.displayViewer(container);
        }
        this.previousAudio = this.currentAudio;
    }


    windowMouseUpHandler(event)
    {
        window.removeEventListener('mousemove', this.windowMouseMoveHandler, false);
        window.removeEventListener('mouseup', this.windowMouseUpHandler, false);
        this.currentAudio.play();
    }


    playerCursorMouseDownHandler(event)
    {
        this.currentAudio.stop();
        this.cursorX0 = this.playerCursor.offsetLeft;
        this.mouseX0 = event.clientX;
        window.addEventListener('mousemove', this.windowMouseMoveHandler, false);
        window.addEventListener('mouseup', this.windowMouseUpHandler, false);
    }


    windowMouseMoveHandler(event)
    {
        let mouseX =  event.clientX;
        let delta = mouseX - this.mouseX0;
        let left = (this.cursorX0 + delta);
        let ratio = left / this.playerRange.offsetWidth;
        let currentTime = this.currentAudio.duration * ratio;
        if(currentTime>=this.currentAudio.duration) {
            this.currentAudio.stop();
            this.windowMouseUpHandler();
            return;
        }
        this.playerCursor.style.left = (ratio * 100) + '%';
        this.currentAudio.setCurrentTitme(currentTime);
        this.playerTime.innerHTML = this.getTimeFormat(currentTime) + ' / ' + this.getTimeFormat(this.currentAudio.duration);
    }


    playerButtonClickHandler(event)
    {
        if(this.currentAudio !== null) {
            if(this.currentAudio.isPLaying())
            {
                this.switchButton();
                this.currentAudio.stop();
            }
            else{
                this.switchButton();
                this.currentAudio.play();
            }
        }
    }


    switchButton()
    {
        if(this.currentAudio.isPLaying()) {
            this.playerButton.classList.add('pause');
        }
        else {
            this.playerButton.classList.remove('pause');
        }
    }


    play(event) 
    {
        let audioName = event.currentTarget.getAttribute('data-name');
        let audio = this.getAudioWidgetByName(audioName);
        let currentTime = audio.getCurrentTime();
        if(currentTime>=audio.duration) return;
        this.playerTime.innerHTML = this.getTimeFormat(currentTime) + ' / ' + this.getTimeFormat(audio.duration);
        let percentage = currentTime  / audio.duration * 100;
        this.playerCursor.style.left = percentage + '%';
    }


    getTimeFormat(seconds) {
        seconds = Number(seconds);
        var h = Math.floor(seconds / 3600);
        var m = Math.floor(seconds % 3600 / 60);
        var s = Math.floor(seconds % 3600 % 60);
        if(h<10) h = '0' + h;
        if(m<10) m = '0' + m;
        if(s<10) s = '0' + s;
        return h + ':' + m + ':' + s;
    }


    setTitle()
    {
        this.playerTitle.innerHTML = '<p>' + this.currentAudio.title + '</p>';
        this.playerTitle.querySelector('p').style.width = this.playerTitle.querySelector('p').offsetWidth + 'px';
    }


    getAudioWidgetByName(name)
    {
        let audioWidget = null;
        this.audioWidgets.map(function(audio){
            if(audio.name === name) {
                audioWidget = audio;
            }
        });
        return audioWidget;
    }


    getElement(tag, className)
    {
        let element = document.createElement(tag);
        element.classList.add(className);
        return element;
    }
    
    
    showPlayer()
    {
        this.player.classList.add('show');
    }


    hidePlayer()
    {
        this.player.classList.remove('show');
    }    

    displayViewer(container)
    {
        /*
        if(this.photoClickHandlerCallback !== undefined) {
            this.photoClickHandlerCallback('show');
        }
        this.removeViewer();
        this.viewer = container.cloneNode(true);
        this.viewer.classList.add('viewer');
        this.viewerContainer.appendChild(this.viewer);       
        */
    }

    removeViewer()
    {
        /*
        if(this.viewer) {
            if(this.photoClickHandlerCallback !== undefined) {
                this.photoClickHandlerCallback('hide');
            }
            this.viewerContainer.removeChild(this.viewer);
            this.viewer = null;            
        }
        */
    }


}


class AudioWidget
{

    constructor(widget, title, url, index)
    {
        this.widget = widget;
        this.name = 'audio-widget-' + index;
        this.title = title;
        this.url = url;
        this.widget.setAttribute('data-name', this.name);
        this.setBindings();
    }

    setBindings()
    {
        this.play = this.play.bind(this);
        this.stop = this.stop.bind(this);
        this.addPlayEventListener = this.addPlayEventListener.bind(this);        
        this.getCurrentTime = this.getCurrentTime.bind(this);        
        this.isPLaying = this.isPLaying.bind(this);
        this.duration = this.widget.duration;
    }
    
    load()
    {
        this.widget.load();
    }

    play()
    {
        var playPromise = this.widget.play();
        if (playPromise !== undefined) {
            playPromise.then(_ => {              
            })
            .catch(error => {              
            });
          }
        /*
        try {
            this.widget.play();
        } catch (error) {}
        */
    }

    stop()
    {

        var playPromise = this.widget.play();
        if (playPromise !== undefined) {
            playPromise.then(_ => {   
                this.widget.pause();           
            })
            .catch(error => {              
            });
          }
        /*
        try {
            this.widget.pause();
        } catch (error) {}
        */
    }

    addPlayEventListener(callback)
    {
        this.widget.addEventListener('timeupdate', callback, false);
    }    
    
    getCurrentTime()
    {
        return this.widget.currentTime;
    }    
    
    isPLaying()
    {
        if(!this.widget.paused) {
            return true;
        }
        return false;
    }

    setCurrentTitme(currentTime)
    {
        this.widget.currentTime = currentTime;
    }
    
}


function d(data)
{
    console.log(data);
}