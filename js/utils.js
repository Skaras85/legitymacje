function uniqid (prefix, more_entropy) {
  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +    revised by: Kankrelune (http://www.webfaktory.info/)
  // %        note 1: Uses an internal counter (in php_js global) to avoid collision
  // *     example 1: uniqid();
  // *     returns 1: 'a30285b160c14'
  // *     example 2: uniqid('foo');
  // *     returns 2: 'fooa30285b1cd361'
  // *     example 3: uniqid('bar', true);
  // *     returns 3: 'bara20285b23dfd1.31879087'
  if (typeof prefix === 'undefined') {
    prefix = "";
  }

  var retId;
  var formatSeed = function (seed, reqWidth) {
    seed = parseInt(seed, 10).toString(16); // to hex str
    if (reqWidth < seed.length) { // so long we split
      return seed.slice(seed.length - reqWidth);
    }
    if (reqWidth > seed.length) { // so short we pad
      return Array(1 + (reqWidth - seed.length)).join('0') + seed;
    }
    return seed;
  };

  // BEGIN REDUNDANT
  if (!this.php_js) {
    this.php_js = {};
  }
  // END REDUNDANT
  if (!this.php_js.uniqidSeed) { // init seed with big random int
    this.php_js.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
  }
  this.php_js.uniqidSeed++;

  retId = prefix; // start with prefix, add current milliseconds hex string
  retId += formatSeed(parseInt(new Date().getTime() / 1000, 10), 8);
  retId += formatSeed(this.php_js.uniqidSeed, 5); // add seed hex string
  if (more_entropy) {
    // for more entropy we add a float lower to 10
    retId += (Math.random() * 10).toFixed(8).toString();
  }

  return retId;
}

//zaokraglenie do k miejsc po przecinku
Math.decimal = function(n, k)
{
    if (k < 1)
        return Math.round(n);
    var factor = Math.pow(10, k+1);
    n = Math.round(Math.round(n*factor)/10);
    return n/(factor/10);
}

function numberToPrice(amount)
{
    var i = parseFloat(amount);
    if(isNaN(i)) { i = 0.00; }
    var minus = '';
    if(i < 0) { minus = '-'; }
    i = Math.abs(i);
    i = parseInt((i + .005) * 100);
    i = i / 100;
    s = new String(i);
    if(s.indexOf('.') < 0) { s += ',00'; }
    if(s.indexOf('.') == (s.length - 2)) { s += '0'; s=s.replace('.',','); }
    s = minus + s;
    return s;
}

Date.now = function()
{
    var currentTime = new Date(),
        month = currentTime.getMonth() + 1,
        day = currentTime.getDate(),
        year = currentTime.getFullYear(),
        hours = currentTime.getHours(),
        minutes = currentTime.getMinutes(),
        seconds = currentTime.getSeconds();
        
    if (minutes < 10) minutes = "0" + minutes;
    if (seconds < 10) seconds = "0" + seconds;
    
    return year+'-'+month+'-'+day+' '+hours+':'+minutes+':'+seconds;
    
}

Array.prototype.inArray = function(needle) {
    for(var i in this) 
    {
        if(this[i] == needle) 
            return true;
    }
    return false;
}

$.fn.numberInput = function()
{
    return this.each(function(index,element){
        $(element).keydown(function(event){
            if ( event.ctrlKey || event.altKey 
                    || (47<event.keyCode && event.keyCode<58 && event.shiftKey==false) 
                    || (95<event.keyCode && event.keyCode<106)
                    || (event.keyCode==8) || (event.keyCode==9) 
                    || (event.keyCode>34 && event.keyCode<40) 
                    || (event.keyCode==46) || (event.keyCode==13) )
                return true;
            else
                return false;
        });
    });
}

$.fn.jBlink = function()
{
    return this.each(function(index,element){
        var $this = $(element);
        setInterval(function(){
            if($this.css('opacity')==0)
                $this.css('opacity',1);
            else
                $this.css('opacity',0);
        },1000);
    });
}

$.fn.hint = function(options)
{
    var options =  $.extend({
                        offset: 10,
                        content: ''
                    }, options);
    return this.each(function(index,element){
        var $this = $(element),
            parentOffset = $(this).parent().offset(); 

        $this.mouseover(function(e){
            $('body').append('<div id="tooltipContainer" style="visibility: hidden; top: -9999px; position: absolute; z-index: 9999">'+options.content+'</div>');
            var x = e.pageX+options.offset,
                y = e.pageY+options.offset,
                tooltipContainer = $('#tooltipContainer');

            if(x+tooltipContainer.width()>$('body').width())
                x = e.pageX-options.offset-tooltipContainer.width();
            if(y+tooltipContainer.height()>$('body').height())
                y = e.pageY-options.offset-tooltipContainer.height();

            $('#tooltipContainer').css({top: y+'px', left: x+'px', visibility: 'visible' });
            
        }).mouseout(function(){
            $('#tooltipContainer').remove();
        }).mousemove(function(e){
            var x = e.pageX+options.offset,
                y = e.pageY+options.offset,
                tooltipContainer = $('#tooltipContainer');

            if(x+tooltipContainer.width()>$('body').width())
                x = e.pageX-options.offset-tooltipContainer.width();  
            if(y+tooltipContainer.height()>$('body').height())
                y = e.pageY-options.offset-tooltipContainer.height();
  
            $('#tooltipContainer').css({top: y+'px', left: x+'px'});
        });
    });
}

$.fn.serializeSortable = function(elements,attr,arrayName)
{
    var str='';
    this.find(elements).each(function(index,element){
        str+=arrayName+'['+(index+1)+']='+$(element).attr(attr)+'&';
    });
    return str.substr(0,str.length-1);
}

$.fn.jMarquee = function(options)
    {
        var $this = $(this),
            elementsWidth = 0;

        $this.find('li').each(function(i,e){
            elementsWidth+=$(e).outerWidth();
        });
        
        if(elementsWidth>$this.width())
        {
            $this.find('ul').width(elementsWidth*2);    //*2 zeby miec pewnosc, ze zaden element sie nie wyleje
            var options = options || {},
                img = $this.find('li:first'),
                defaults = {
                    speed: 2500,
                    easing: 'linear'
                };
                
            options = $.extend({},defaults,options);    

            img.animate({marginLeft:-img.outerWidth()-parseInt(img.css('margin-right'))},options.speed,options.easing,function(){
                img.remove();
                img.css({marginLeft: 0});
                $this.find('ul').append(img);
                $this.jMarquee(options);
             });
             
             $this.find('li').hover(function(){
                 img.stop();
             },function(){
                 $this.jMarquee();
             });
         }
    }

$.fn.fadeRemove = function(speed,callback) {
    var speed = speed || 'normal';
    this.each(function(index,element){
        var $element = $(element);
        $element.fadeOut(speed, function(){ 
            $element.remove(); 
            if(callback && typeof callback == 'function')
                callback.call();
            if(speed && typeof speed == 'function')
                speed.call();
        });
    }); 
};

$.fn.reverse = function() {
    return this.pushStack(this.get().reverse(), arguments);
};

$.fn.equalHeight = function(){
    var heightOfTallest = 0;
    this.each(function(index,element){
        var height = $(this).height();
        if(height>heightOfTallest)
            heightOfTallest = height;
    });
    return this.height(heightOfTallest);
}

$.fn.makeSludge = function(text) {
    
    text=text.toLowerCase();     
    text=text.replace(/\./g,'');   
    text=text.replace(/ę/g,'e');
    text=text.replace(/ó/g,'o');
    text=text.replace(/ą/g,'a');
    text=text.replace(/ś/g,'s');
    text=text.replace(/ł/g,'l');
    text=text.replace(/ż/g,'z');
    text=text.replace(/ź/g,'z');
    text=text.replace(/ć/g,'c');
    text=text.replace(/ń/g,'n');
    text=text.replace(/[^a-z0-9\.:_\-\s]/g,'');
    text=text.replace(/\s/g,'-');
    
    return this.each(function(index,element){
        $(element).val(text);
    }); 
}

function makeSludge(text) {
    
    text=text.toLowerCase();     
    text=text.replace(/\./g,'');   
    text=text.replace(/ę/g,'e');
    text=text.replace(/ó/g,'o');
    text=text.replace(/ą/g,'a');
    text=text.replace(/ś/g,'s');
    text=text.replace(/ł/g,'l');
    text=text.replace(/ż/g,'z');
    text=text.replace(/ź/g,'z');
    text=text.replace(/ć/g,'c');
    text=text.replace(/ń/g,'n');
    text=text.replace(/[^a-z0-9\.:_\-\s]/g,'');
    text=text.replace(/\s/g,'-');
    
    return text; 
}

String.prototype.stripTags = function(){
    return this.replace(/(<([^>]+)>)/ig,"");
}

/*---------------------------czy plik istnieje------------------------------*/
    
function urlExists(url)
{
    var http = new XMLHttpRequest();
    http.open('HEAD', url, false);
    http.send();
    return http.status!=404;
}

$.fn.extend({
      center: function (options) {
           var options =  $.extend({ // Default values
                inside:window, // element, center into window
                transition: 0, // millisecond, transition time
                minX:0, // pixel, minimum left element value
                minY:0, // pixel, minimum top element value
                withScrolling:true, // booleen, take care of the scrollbar (scrollTop)
                vertical:true, // booleen, center vertical
                horizontal:true, // booleen, center horizontal
                position: 'fixed'
           }, options);
           return this.each(function() {
               var $this = $(this);
                if (options.transition > 0) 
                    $this.animate({position:options.position}, options.transition);
                else 
                    $this.css({position:options.position});
                    
                if (options.vertical){
                     var top = ($(options.inside).height() - $this.outerHeight()) / 2;
                     if (options.withScrolling) top += $(options.inside).scrollTop() || 0;
                     top = (top > options.minY ? top : options.minY);
                     $this.css({top: top+'px'});
                }
                if (options.horizontal) {
                      var left = ($(options.inside).width() - $this.outerWidth()) / 2;
                      if (options.withScrolling) left += $(options.inside).scrollLeft() || 0;
                      left = (left > options.minX ? left : options.minX);
                      $this.css({left: left+'px'});
                }
                return $this;
           });
      }
 });
 

$.fn.modal = function(options,callback) {
    
    var defaults = {
            width: 'auto',
            height: 'auto',
            top: 0,
            left: 0,
            content: '',
            fadeOutSpeed: 100,
            fadeInSpeed: 100,
            scrollX: false,
            scrollY: false,
            center: true,
            position: 'fixed',
            header: false,
            boardOpacity: 0.5,
            niceScroll: false,
            closeIcon: true,
            closeButton: false,
            closeOnOutsideClick: true,
            closeOnElement: false,
            closeAfterSeconds: 0,
            draggable: true,
            resizable: false,
            ajax: false,
            onClose: function(){},
            beforeClose: function(){},
            beforeShow: function(){}
    };
    
    var options = $.extend({},defaults,options);
    var $collection = this;
    
    if(options.closeIcon)
        closeIcon='<span id="modalClose" class="modalClose" title="Zamknij"></span>';
    else
        closeIcon='';

    if(options.content=='')
    {
        $collection.click(function(e){
            e.preventDefault();
            var $thisClick = $(this);
            run(options,$thisClick);
        }); 
    }
    else
        run(options);
    
    function run(options,$thisClick)
    {
        var $thisClick = $thisClick || false;

        $('body').css('overflow','hidden').append('<div id="board" style="display:none"></div>');
        $('html').css('margin-right','17px');
        
        $('#board').css({width: '100%',
                     height: '100%',
                     backgroundColor: 'rgba(0,0,0,'+options.boardOpacity+')',
                     position: 'fixed',
                     top: 0,
                     left: 0,
                     zIndex: 999});

        var header = '';
        if(options.header)
            header = '<header style="padding: 20px;background:#f5f5f5;border-bottom:1px solid #fff;border-radius: 6px 6px 0 0;">'+options.header+'</header>';

        var string = '<div id="modalWrapper" style="visibility: hidden;">'+closeIcon+header+'<div style="'; string+= options.header ? 'border-top:1px solid #ddd;padding: 20px 20px 20px 0' : 'padding: 10px 20px 10px 0'; string += '"><div id="modal" style="overflow:hidden;padding-left:20px;"></div></div></div>';
        $('body').append(string);
    
        var modal = $('#modal'),
        modalWrapper = $('#modalWrapper'),
        board = $('#board');
        
        if(options.closeButton)
            modalWrapper.append('<button class="modalClose" style="margin: 10px auto 0;display:block">Zamknij</button>');
    
        if(options.closeOnElement)
            $(document).on('click',options.closeOnElement,function(e){e.preventDefault();closeModal(options.fadeOutSpeed);});

        if(options.resizable)
            modalWrapper.resizable({ alsoResize: "#modal *",
                                           aspectRatio: true,
                                           autoHide: true });
    
        modalWrapper.css({position: options.position,
                             background: 'white',
                             color: 'black',
                             zIndex: 1000,
                             borderRadius: '6px',
                             boxShadow: '0 0 10px 10px rgba(0, 0, 0, 0.5)',
                             top: options.top,
                             left: options.left
                           });
    
        modal.css({width: options.width});
        
        $('#modalClose').css({width:'25px',
                            height:'25px',
                            backgroundImage: 'url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABx0RVh0U29mdHdhcmUAQWRvYmUgRmlyZXdvcmtzIENTM5jWRgMAAAAWdEVYdENyZWF0aW9uIFRpbWUAMTIvMDYvMDhuWCE5AAAD80lEQVRIx5VWXUiUWRietFIs/Kkkpz8pliHQXFphdUmpiwTJbbQbl1mICbxQrC6sxUIXFC8EBRE2QREUxMAk6TJbMfyhAq8EUcEbN7VRRxtSccrRcU7PczjH/cb9vpp94WG+78x53+e8P+d9P5vNWo4CJ4GUCBEPHLBFKNq4fWBgwLm6uvp8a2vrn2Aw6BdCBAnIF8j83Nzc04aGhhvJyck/YP8ZIBGIioQgpaur6+ra2torGAuEILu7uyE8CyNC/0oQZH+7XK6r0L0AJH2LSBJMTU3d39nZ8UltGJuenhaNjY0iOztbxMXFSWRlZYmmpiYB4wIHEBR49qm7u/sBbDiAY2ZEkmBsbMyN/dtU8ng8wu12i9jYWIH/LFFZWSk2NzelZ36//1NbW9sfWL9oRnSyp6fnWiAQeM/NPL3dbv+mcSPS0tLE+Pg4iQRs+OH1TeXRXujohX1jY2MIDoQYgoyMjIgJNBwOh9jelkEITU5Ovk1KSrqC9fPAQelFX1/fdVYN41tWVhamXFRUJBITE/9jlGu1tbVha6WlpbIoYGe3pKTkHtYuATEkSfH5fC94gvn5+bAckIDCUBiJ+Mw1Sk1NTRgRI8GQDw0NvcR7joqULQVuehnP+vr6MIWEhIQ9Y5rISMBf7jHqtLe305PQysqKB+8FwHFJwlCRhAncHxYj0cTEhIQVAZGfny9DhpL+jPffgFOahB5aJpXGtHFNZkZAsCp1XvB+GzirSaRY3QmGaD+JWTEQqampkgQp2MH7HeCcJGEvYmXxVpsRWIXLjKi4uFiS4GL6w0gQvw+MV3NzsyWBzoFZMRh1Ojo6ZOKXl5eXwsKF0n1GkpmZGRETE7OnUFhYaJpkI5GxhHEBxezsrCTp7+9/HZb41tbWW2iKAbgZYr8ynoxEZknm2v47UlVVJUP1GVJQUFCHtRu6hDlszi8uLg7TG6/X+7/6lkZubq5YX1+XZTo6OvpO5eMn4IhNTbPTdXV1TuRmgyGgy5mZmRET5OTkiIWFBRk+dA8f3v/EulMNv0O6Cydw4PT29j5C2HgxQ1SqqKiwvA9EfHy8qK6uFhhw0gO0/M3y8vIn+O934EflRbQmOaDasqOlpeUhDuPRQ2tpaUlgGAmn0ykJaTgvL090dnby1HJocS/aiBfN9S9VUT8re4f2z/0oNWguYvNtTMg3mA0B3Q3MwP9w6baHh4ffpaenP4auSxEw2YetxrAm4sD5BZV2d2RkpB8nXuEN5rgnI58/QgYHB0dV/JlkDqoM9TFx+HsfFFHKTSYtHeDw+VWd0q0M3lHPLvXfZbX/iNL97heLzlG0UqDiCXWp2CJSFc6ptRMG49Fm315fAY3g4Pe75SL8AAAAAElFTkSuQmCC")',
                            cursor:'pointer',
                            position: 'absolute',
                            top: '-10px',
                            right: '-10px'
                        })
                        .hover(function(){
                            $(this).css({top:'-9px',right:'-11px'});
                        },function(){
                            $(this).css({top:'-10px',right:'-10px'});
                        });
        
        getContent(options,$thisClick);

        if(options.scrollY)
            modal.css({overflowY: 'scroll',height: options.height,paddingRight:'20px'});
        else
        {
            //jezeli nie ma scrolla to ustawiam minimalna wysokosc
            modal.css({minHeight: options.height});
            resizeContent(options);
        }
        
        if(options.scrollX)
            modal.css({overflowX: 'scroll',width: options.width,paddingBottom:'20px'});
         
        board.click(function(){
            if(options.closeOnOutsideClick)
                closeModal(options.fadeOutSpeed);
        });
        
        if(options.niceScroll)
            modal.niceScroll({cursoropacitymin:1,
                                cursorwidth:8,
                                cursorcolor: '#5DBAF4',
                                cursorborder: 'solid grey 1px'});
        
        $('#modalContentWrapper').css('position','static').appendTo('#modal');
        
        $(document).on('click','.modalClose',function(){closeModal(options.fadeOutSpeed);});

        if(options.draggable)
        {
            modalWrapper.draggable({handle: '#modalContentWrapper'});
            $('#modalContentWrapper').css({ cursor: "crosshair"});
            $('#modal').css({ cursor: "auto" });
        }

        if($thisClick)
        {
            $('.modalCurrentElement').removeClass('modalCurrentElement');
            $thisClick.addClass('modalCurrentElement');
        }
        options.beforeShow.call();

        if(options.center)        
            modalWrapper.center({withScrolling: false,position: options.position});

        if(options.closeAfterSeconds!==0 && typeof options.closeAfterSeconds === 'number')
        {
            setTimeout(function(){
                closeModal(options.fadeOutSpeed);
            },options.closeAfterSeconds);
        }

        modalWrapper.css({display: 'none', visibility: 'visible'}).add(board).fadeIn(options.fadeInSpeed,function(){        
        
            //if(typeof callback === 'function')
            //    callback.call();
                
        });
        
        if(typeof callback === 'function')
            callback.call();
        
        function closeModal(fadeOutSpeed)
        {
            options.beforeClose.call();
            board.add('modalClose').add(options.closeOnElement).unbind('click');
            modal.getNiceScroll().hide();
            modalWrapper.add(board).fadeRemove(fadeOutSpeed,function(){
                modal.getNiceScroll().remove();
                $('body').css('overflow','auto');
                $('html').css('margin',0);
                options.onClose.call();
            });
        }
        
        function nextObject(options,direction)
        {
            var indexOfCurrent = $collection.index($('.modalCurrentElement'));
            var currentObject = $($collection[indexOfCurrent]);
            
            if(direction=='prev')
            {
                var nextObject = $($collection[indexOfCurrent-1]);
                var edgeObject = $($collection[$collection.length-1]);
            }
            else if(direction=='next')
            {
                var nextObject = $($collection[indexOfCurrent+1]);
                var edgeObject = $($collection[0]);
            }
            
            options.beforeClose.call();
            $('#modalContentWrapper').css('visibility','hidden');
            
            if(nextObject.length)
            {
                getContent(options,nextObject);
                currentObject.removeClass('modalCurrentElement');
                nextObject.addClass('modalCurrentElement');
            }
            else
            {
                getContent(options,edgeObject);
                currentObject.removeClass('modalCurrentElement');
                edgeObject.addClass('modalCurrentElement');
            }
  
            resizeContent(options);

            options.beforeShow.call();

            $('#modalContentWrapper').css('visibility','visible');
            
            if(typeof callback === 'function')
                callback.call();
        }
        
        $(window).resize(function(){
            resizeContent(options);
        });
        
        $(document).keydown(function(e){
            if(e.keyCode==27)
               closeModal(options.fadeOutSpeed);
            /*if(e.keyCode==39)
                nextObject(options,'next');
            if(e.keyCode==37)
                nextObject(options,'prev');*/
        });
    }
    
    function getContent(options,$thisClick)
    {
        var $thisClick = $thisClick || '';
        if(options.content!='')
        {
            if($('#modalContentWrapper').length)
            {
                $('#modalContentWrapper').html(options.content);
                
                if(options.header.length)
                	$('#modalWrapper').find('header').html(options.header);
            }
            else
                $('body').append('<div id="modalContentWrapper" style="position: absolute; top: -9999px">'+options.content+'</div>');
        }
        else if(!options.ajax)
        {
            if($('#modalContentWrapper').length)
                $('#modalContentWrapper').html($thisClick.href);
            else
                $('body').append('<div id="modalContentWrapper" style="position: absolute; top: -9999px">'+$thisClick.href+'</div>');
        }
        else
        {
            $.ajax({url: $thisClick.attr('href'),
                    async: false,
                    success: function(content){
                        if($('#modalContentWrapper').length)
                            $('#modalContentWrapper').html(content);
                        else
                            $('body').append('<div id="modalContentWrapper" style="position: absolute; top: -9999px">'+content+'</div>');
                    }
            });
        }  
    }
    
    function resizeContent(options)
    {
        //jezeli obrazki w zawartosci contentu sa wyzsze lub szersze
        // od wysokosci ekranu (z pewnym paddingiem) to je przeskaluj
       $('#modalContentWrapper img').each(function(index,element){
           
           $(element).load(function() {
               var winHeight = $(window).height();
               var winWidth = $(window).width();
               var heightRatio = (winHeight-100)/$(element).height();
               var widthRatio = (winWidth-100)/$(element).width();
               var elHeight = $(element).height();
               var elWidth = $(element).width();
               
               $(element).attr('data-original-width',elWidth);
               $(element).attr('data-original-height',elHeight);

               if(elHeight>winHeight-100)
               {
                    $(element).css('height',elHeight*heightRatio);
                    $(element).css('width',elWidth*heightRatio);
               }
               else if(elWidth>winWidth-100)
               {
                    $(element).css('height',elHeight*widthRatio);
                    $(element).css('width',elWidth*widthRatio);
               }
               
               if(options.center)        
                    $('#modalWrapper').center({withScrolling: false,position: options.position});
           });
       });
    }
};

$(function(){
    
     /*---------------------------crossbrowserowy atrybuty--------------*/

    
  /* if(!Modernizr.input.placeholder){

        $('[placeholder]').focus(function() {
          var input = $(this);
          if (input.val() == input.attr('placeholder')) {
            input.val('');
            input.removeClass('placeholder');
          }
        }).blur(function() {
          var input = $(this);
          if (input.val() == '' || input.val() == input.attr('placeholder')) {
            input.addClass('placeholder');
            input.val(input.attr('placeholder'));
          }
        }).blur();
        $('[placeholder]').parents('form').submit(function() {
          $(this).find('[placeholder]').each(function() {
            var input = $(this);
            if (input.val() == input.attr('placeholder')) {
              input.val('');
            }
          })
        });
      }*/
  
	/*-----------------------jMore---------------------------------*/
	
	$('.jMore').click(function(e){
	    e.preventDefault();
	    
	    var jMore = $(this);
	    
	    if(!jMore.attr('data-jmore-dir') || jMore.attr('data-jmore-dir')=='next')
		  var container = jMore.nextAll(".hidden:first");
		else if(jMore.attr('data-jmore-dir')=='prev')
		  var container = jMore.prevAll(".hidden:first");
		
		if(jMore.attr('data-jmore-class'))
		  jMore.toggleClass(jMore.attr('data-jmore-class'));
		
		var txt1 = jMore.attr('data-jmore-txt');
		var txt2 = jMore.html();

		if(container.is(':hidden'))
			container.show();
		else
			container.hide();
			
		jMore.html( txt1 ).attr('data-jmore-txt', txt2);
	});
	
	/*--------------------------------jClear--------------------------------------*/
	
	$('.jClear').focus(function(){
	    var $this = $(this);
        if(typeof $this.data('placeholder')==='undefined')
        {
            $this.data('placeholder',$this.val());
            $this.val('');
        }
        else
        {
            if($this.val()==$this.data('placeholder'))
                $this.val('');
        }
    }).blur(function(){
        if($this.val()=='')
            $this.val($this.data('placeholder'));
    });
    
    $('.numeric').keypress(function(event) {
      // Backspace, tab, enter, end, home, left, right
      // We don't support the del key in Opera because del == . == 46.
      var controlKeys = [8, 9, 13, 35, 36, 37, 39];
      // IE doesn't support indexOf
      var isControlKey = controlKeys.join(",").match(new RegExp(event.which));
      // Some browsers just don't raise events for control keys. Easy.
      // e.g. Safari backspace.
      if (!event.which || // Control keys in most browsers. e.g. Firefox tab is 0
          (49 <= event.which && event.which <= 57) || // Always 1 through 9
          (48 == event.which && $(this).attr("value")) || // No 0 first digit
          isControlKey) { // Opera assigns values for control keys.
        return;
      } else {
        event.preventDefault();
      }
    });
    
    $.fn.checkAll = function()
	{
	    return this.each(function(index,element){
	        $(element).click(function(){
	            id = $(this).attr('id');
	            var checkboxes = $('.checkAllTarget[data-check-id~="'+id+'"]');
	
	            if(!checkboxes.length)
	                checkboxes = $('.checkAllTarget');
	            
	            checkboxes.filter(':not(:disabled)').prop('checked',$(this).prop('checked'));
	        });
	    });
	}

	/*--------------------------file selector------------------------------*/
    /*
    $('input[type="file"]').css('opacity',0)
                           .wrap('<div class="file_selector"><div class="input_container"></div></div>')
                           .parent().prepend('<p></p>');
                           
    $('input[type="file"]').change(function(){
        var url=$(this).val().split("\\");
        $(this).prev().html( url[url.length-1].substr( url[url.length-1].length-18,url[url.length-1].length  ) );
    });*/
   

}) 