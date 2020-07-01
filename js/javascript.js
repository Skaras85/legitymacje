function getContent(action,changeContent)
{
    var changeContent = changeContent || false,
        result = null;
    
    $.ajax({url: action,
            type: 'get',
            dataType: 'html',
            async: false,
            success: function(dane){
            
                var scripts = [];
                var m;
                var re = /<main[^>]*>([\s\S]*?)<\/main>/ig;
                while (m = re.exec(dane)) {
                    scripts.push(m[1]);
                }
                
                if(changeContent)
                    $('main').html(scripts[0]);
                result = scripts[0];
            }
    });
    return result;
}

$(function(){
	
    var base_url = $('body').attr('data-base-url');
    var js = $('body').data('js');
    var lang = $('html').attr('lang');
    var ww = document.body.clientWidth;
    
    //if($(window).height()>$('body').height())
    //    $('footer').css('position', 'absolute');
    
    $('input.sludgeSource').on('change keypress paste focus textInput input',function(){
        $('input'+$(this).attr('data-sludge')).makeSludge($(this).val());
    });  
    
    $('.copySource').on('change keypress paste focus textInput input',function(){
        
        var a_dest = $(this).attr('data-copy-to').split(' ');
        for(var i in a_dest)
            $(a_dest[i]).val($(this).val());

    });
    
    $('body').click(function(e){
        if(!$(e.target).is('.dropdownButton') && !$('.dropdownMenu').hasClass('hidden'))
        {
            $('.dropdownMenu').addClass('hidden');
        }
        
        if(!$(e.target).closest('.multiSelect').length)
            resetMultiSelect($('.multiSelect'));
        
    });
    
    $(document).on('click','.multiSelect',function(e){
        $(this).addClass('active');
    })
    
    function resetMultiSelect($this)
    {
        $this.removeClass('active');
        $this.scrollTop(0);
    }
    
    $(document).on('click','.print',function(){console.log(34);
        if($('#modal').length)
        {
            var $print = $('#modalContentWrapper')
                .clone()
                .addClass('printIt')
                //.width(740)
                .prependTo('body');
        
            $('#modalWrapper,#board').addClass('hidden');
            $('body').css('overflow','auto');
            window.print();
            $('#modalWrapper,#board').removeClass('hidden');
            $print.remove();
            $('body').css('overflow','hidden');
        }
        else
            window.print();
    });
    
   // $.get(base_url+'panel/get_setting/ajax/true/key/is_cookie_comm',function(result){
        if(!$.cookie('cookie_accept'))
        {
            $('body').append('<div id="cookieAccept">Nasz serwis korzysta z plików cookie. <span>AKCEPTUJĘ</span></div>');
            
            $('#cookieAccept span').click(function(){
                $.cookie('cookie_accept',1,{ expires: 3650, path: '/' });
                $('#cookieAccept').fadeRemove();
            });
        }
    //});
    
    $.fn.respMenu = function(options) {
        
        var defaults = {
            breakpoint: 1200,
            menuLabel : ''
        };
    
        var options = $.extend({},defaults,options),
            ww = document.body.clientWidth,
            menu = this,
            firstDegreeMenu = menu.children('li'),
            respMenu = $('#respMenu');
        
        if(ww<=options.breakpoint && !respMenu.length)
        {
            menu.before('<div id="respMenu"><span class="respMenu-handler"><span></span><span></span><span></span>'+options.menuLabel+'</span>');
            firstDegreeMenu.unbind('mouseenter mouseleave').hide();
            
            $('#respMenu').click(function(){
                if(firstDegreeMenu.is(':hidden'))
                    firstDegreeMenu.show();
                else
                    firstDegreeMenu.hide();
                    
                $('.respMenu-handler span').toggleClass('active');
            });
            
            firstDegreeMenu.find('> a').click(function(e){
                var $this = $(this),
                    submenu = $this.next('ul');
                if(submenu.length)
                {
                    //if($this.attr('href')!=submenu.find('li:first a').attr('href'))
                    //    submenu.prepend('<li><a href="'+$this.attr('href')+'">'+$this.html()+'</a></li>');

                    e.preventDefault();
                    submenu.toggle();
                }
            });
        }
        else if(ww>options.breakpoint)
        {
            $('#respMenu').remove();
            firstDegreeMenu.show()
            
            firstDegreeMenu.hover(function(){
                $(this).find('ul').show();
            },function(){
                $(this).find('ul').hide();
            }).find('> a').unbind('click');
        }
        
        //$.get(base_url+'panel/get_setting/ajax/true/key/is_sticky_header',function(result){
            if($('body').data('is_sticky_header')=='tak')
            {
                if(ww<=500)
                {
                     $('#mainHeader').removeClass('sticky-header');
                     //$('#sticky-header-prop').height(0);
                }
                else
                {
                    if($(window).scrollTop()>66)
                    {
                        $('#mainHeader').addClass('sticky-header');
                    }
                    else
                    {
                        $('#mainHeader').removeClass('sticky-header');
                    }
                }
            }
        //});
        
        return menu;
    };
    
    $('#menu').respMenu();
    
     $(window).resize(function() {
         $('#menu').respMenu();
     });

    if($('.colorpicker').length)
        $('.colorpicker').minicolors();

    $(document).on('click','a.modal',function(e){
        e.preventDefault();
        make_modal($(this).attr('href'),$(this).data('width'));
    });

    function make_modal(url,width=$(window).width()/2)
    {
    	var content = getContent(url),
            header = content.match(/<h1[^>]*>([\s\S]*?)<\/h1>/);
        $(this).modal({content: content.replace(/<h1>.+<\/h1>/,''),
                       header: header!=null ? header[0] : false,
                         fadeOutSpeed: 0,
                         fadeInSpeed: 0,
                         draggable: false,
                         niceScroll: true,
                         scrollY: true,
                         width: width,
                         height: $(window).height()-200}, function(){
                         	datepicker();
                         	icheck();
                         	okres_zatrudnienia_czas();
                         	zdjecie_i_podpis_zlozone();
                         	$('.jValidate').jValidate();
                         	makeHint('.pokazZdjecie');
                         	$('#modal').getNiceScroll().resize();
                         	$('#modal').css('overflow-y','hidden');
                         	if($('.kod_karty').length)
                         	  $('.kod_karty').focus();
                         	
                         });
    }
    
    $(".widget_social.side").hover(function() {
        $(".widget_social.side").stop(true, false).animate({right: "0"}, "medium");
    }, function() {
        $(".widget_social.side").stop(true, false).animate({right: "-205"}, "medium");
    }, 500);
    
    $('table:not(.calendar) tr:not(:has(th))').hover(function(){
        $(this).toggleClass('trHover')
    });
    
    $('button:not(:parent(a))').click(function(e){
        e.preventDefault();
    });
    
    $(document).on('click', '.submitButton', function(e){
        e.preventDefault();
        
        if(!$('.err_input').length)
            $(this).closest('form').submit();
    })
    
    function loader()
    {
        $('body').append('<div class="loader"></div>').find('.loader').center();
    }
    
    $(window).scroll(function() {
        if($(window).scrollTop()>0 && ww>500)
            $('.goToTop').fadeIn();
        else
            $('.goToTop').fadeOut();
    });
    
    $('.goToTop').click(function(e){
        e.preventDefault();
        $('html, body').animate({ scrollTop: 0}, 300);
    });
    
    if($('.tagsInput').length)
        $('.tagsInput').tagsInput({
            height: 'auto',
            width:'100%',
            defaultText:'',
            autocomplete_url: base_url+'strony/get_tags/'
        });
        
    $('.checkAll').checkAll();
    
    function komunikat_floating(result,komunikat)
    {
        var klasa = result==true ? 'communicat_ok' : 'communicat_error',
            floatingLast = $('.floating:last'),
            top = 0;
        
        $('.floating').each(function(i,e){
            top += $(e).outerHeight();
        });
        
        var  html = $('<p class="'+klasa+' floating printHide" style="top:'+top+'px">'+komunikat+'<i title="Zamknij"></i></p>');

        $('#siteWrapper').prepend(html);

        setTimeout(function(){
            html.fadeRemove(2000,function(){
                $('.floating').each(function(i,e){
                    $(e).css('top',($(e).css('top').replace('px','')-$(e).outerHeight())+'px');
                });
            });
        },5000);
        
    }

    $('table.trHover').on('mouseover','tr',function(){
        $(this).css('background-color','#BAE0ED');
    }).on('mouseout','tr',function(){
        $(this).css('background-color','#F9FAFC');
    });
    
    if($('.fancyboxxx').length)
        $('.fancyboxxx').fancybox({helpers : {
                                            title: {
                                                type: 'inside',
                                                position: 'top'
                                            }
                                        },
                                        swipe: true
                                        });

	function icheck()
	{
	    $('.iCheck').iCheck({
	        checkboxClass: 'icheckbox_square-blue',
	        radioClass: 'iradio_square-blue'
	    });
	    
	    if($('.radio_do_zdjecia').length)
	    {
	    	var name = $('.radio_do_zdjecia').attr('name');
	    	
	    	$('[name="'+name+'"]').each(function(i,el){
	    		$(el).addClass('radio_do_zdjecia_group');
	    	});
	    	
	    	$('.legitymacje_osoby').on('ifClicked','.radio_do_zdjecia',function(e){
				$('.hidden_do_zdjecia').removeClass('hidden');
			});
			
			$('.legitymacje_osoby').on('ifClicked','.radio_do_zdjecia_group:not(.radio_do_zdjecia)',function(e){
				$('.hidden_do_zdjecia').addClass('hidden');
			});
			
			$('#modal').getNiceScroll().resize();
	    }
    }
    
    icheck();
    
    /*
    $('.tooltip').smallipop({
        preferredPosition: 'right',
        popupOffset: 0,
        theme: 'orange'
    });
    */
    if($('.niceScroll').length)
        $('.niceScroll').niceScroll({cursoropacitymin:1,
                                    cursorwidth:8,
                                    cursorcolor: '#5DBAF4',
                                    cursorborder: 'solid grey 1px'});

    /***************************komentarze*********************************/
    
    $('#form_comments input[type="submit"]').click(function(e){
        e.preventDefault();
        
        var text = $('[name="a_comment[text]"]').val();
        
        $.ajax({url: base_url+'comments/add',
                method: 'POST',
                data: $(this).closest('#form_comments').serialize(),
                dataType: 'json',
                success: function(a_dane){
                    if(a_dane['result']==true)
                    {
                        $('#commentsWrapper').prepend(a_dane.comments_string);
                        //$("time.timeago").timeago();
                        
                        $('.numberOfComments').html(parseInt($('.numberOfComments').html())+1);
                        $('.communicat_error,communict_ok').remove();
                        $('#form_comments').prepend('<p class="communicat_ok" style="margin-left:10px;margin-right:10px">'+a_dane['comm']+'</p>');
                        $('.communicat_ok').fadeOut(12000,function(){
                            $('.communicat_ok').remove();
                        });
                        
                        $('#form_comments #comment_text').val('');
                        $('html, body').animate({ scrollTop: $('#comment_'+a_dane['id_comments']).position().top}, 200);
                    }
                    else
                        $('#form_comments').prepend('<p class="communicat_error">'+a_dane['comm']+'</p>');
                }
        });
    });
    
    $('#commentsWrapper').on('click','a.czyUsunacKomentarz',function(e){
        e.preventDefault();
        var content = "<p class='communicat_question'>";
        content+= lang=='PL' ? 'Czy chcesz usunąć ten komentarz?' : 'Do you want to delete this comment?';
        content+="</p>";
        content+='<button class="usunKomentarz modalClose">';
        content+= lang=='PL' ? 'Tak' : 'Yes';
        content+='</button><button class="modalClose">';
        content+= lang=='PL' ? 'Nie' : 'No';
        content+='</button>';
        $('body').modal({content: content,height: 0});
        var comment=$(this).closest('article');
        var comment_id=$(this).attr('data-comment-id');
        
        $.get(base_url+'main/set_csrf_uniqkey',function(uniq_key){
            $('.usunKomentarz').click(function(){
                $.ajax({url: base_url+'comments/usun_komentarz',
                    method: 'POST',
                    data: {comment_id:comment_id,uniq_key: uniq_key},
                    success: function(a_dane){
                        $('.numberOfComments').html(parseInt($('.numberOfComments').html())-1);
                        comment.fadeRemove();
                    }
                });
            });
        })
    });
    
    $('#commentsWrapper').on('click','a.cytujKomentarz',function(e){
        var $this = $(this),
            textarea = $('#comment_text'),
            commentsWrapper = $this.closest('article'),
            author = commentsWrapper.find('.commentAuthor').html(),
            content = commentsWrapper.find('.commentContent').html(),
            html = '[quote="'+author+'"]'+content+'[/quote]';
        
        if(textarea.val().length>0)
            textarea.val(textarea.val()+"\r\n"+html);
        else
            textarea.val(textarea.val()+html);
        
        $('html, body').animate({ scrollTop: $(textarea).position().top-30}, 200);
    });

    $('#commentsWrapper').on('click','a.edytujKomentarz',function(e){
        e.preventDefault();
         var $editCommentButton = $(this);
       
        $.get(base_url+'main/set_csrf_uniqkey',function(uniq_key){
           
            var commentID = $editCommentButton.attr('data-comment-id'),
                p = $editCommentButton.closest('article').find('.commentContent');
                
            $.post(base_url+'comments/get_comment_ajax',{mode:'html',id:commentID},function(a_comment){
                p.replaceWith('<textarea class="editTextarea">'+a_comment.text+'</textarea>');
                
                $editCommentButton.hide().after('<a href="#" class="saveEditComment" data-comment-id="'+commentID+'">Zapisz</a> <a href="#" class="cancelEditComment">Anuluj</a>');
            
                $('.cancelEditComment').click(function(e){
                    e.preventDefault();
                    var $cancelButton = $(this),
                        textarea = $cancelButton.closest('article').find('.editTextarea');
                    
                    $.post(base_url+'comments/get_comment_ajax',{mode:'bbcode',id:commentID},function(a_comment){
                        textarea.replaceWith('<div class="commentContent">'+a_comment.text+'</div>');
                        $editCommentButton.show().next().remove()
                        $editCommentButton.next().remove();
                    },'json');    
                });
            
                $('.saveEditComment').click(function(e){
                    e.preventDefault();
                    var $saveButton = $(this),
                        commentID = $saveButton.attr('data-comment-id'),
                        textarea = $saveButton.closest('article').find('.editTextarea'),
                        textareaText = textarea.val();
                    
                    $.ajax({url: base_url+'comments/edytuj_komentarz',
                            data: {comment_id: commentID, text: textareaText, uniq_key: uniq_key},
                            type: 'post',
                            success: function(result){
                                $.post(base_url+'comments/get_comment_ajax',{mode:'bbcode',id:commentID},function(a_comment){
                                    textarea.replaceWith('<div class="commentContent">'+a_comment.text+'</div>');
                                    $editCommentButton.show().next().remove()
                                $editCommentButton.next().remove();
                                },'json');  
                          }
                    });
                    
                });
            
            },'json');

        });
    });
   
    
    //if($('time.timeago').length)
    //    $('time.timeago').timeago();
    
    if($('.jRating').length)
    {
        $('.jRating').jRating({
            onClick: function(rate){
                var id = $(this).attr('data-id');
                var type = $(this).attr('data-type');

                $.get(base_url+'main/set_csrf_uniqkey',function(uniq_key){
                    $.post(base_url+'ratings/add',{id: id,type: type, rate: rate, uniq_key: uniq_key},function(a_result){
                        if(a_result['result']==false)
                            $(this).modal({content: '<p class="communicat_error">'+a_result['comm']+'</p>'});
                        else
                        {
                            $('.rateIt').html('Twoja ocena:');
                            var rateAverage = parseFloat($('.rateAverage').html());
                            var rateCount = parseFloat($('.rateCount').html());
                            
                            var newRate = (rateAverage*rateCount+parseFloat(rate))/(rateCount+1);

                            $('.rateAverage').html(Math.decimal(newRate,1));
                            $('.bigRate').html(Math.decimal(newRate,1));
                            $('.rateCount').html(rateCount+1);
                        }
                    },'json');
                });
            }
        });
     }
     
     $('input[name="numer-strony"]').keypress(function(e){
         if(e.keyCode==13)
         {
             e.preventDefault();
             var pageNumber = window.location.href.match(/numer-strony\/(\d+)/)[1];
             var newHref = window.location.href.replace('numer-strony/'+pageNumber,'numer-strony/'+$(this).val());
             location.replace(newHref);
         }
     });
     
     $('.closeBanner').click(function(){
         $(this).parent().fadeRemove();
     });
     
     $.each($('.floatingBanner'), function(index,element){
         var $this = $(this),
             time = $this.attr('data-remove-after')*1000;
             
         setTimeout(function(){
             $this.fadeRemove();
         },time);
     });

     $('.calendar-hour').click(function(e){
        e.preventDefault();
        var $this = $(this),
            hour = $this.html(),
            hours = $this.closest('.hours'),
            dataStorage = hours.prev(),
            day = dataStorage.html(),
            month = dataStorage.data('month'),
            year = dataStorage.data('year'),
            content = getContent(base_url+'rezerwacja/formularz/day/'+day+'/hour/'+hour+'/month/'+month+'/year/'+year+'/room/'+$('[name="room"]:checked').data('room')),
            header = content.match(/<h1[^>]*>([\s\S]*?)<\/h1>/);
        $(this).modal({content: content.replace(/<h1>.+<\/h1>/,''),
                       header: header ? header[0] : '',
                         draggable: false,
                         niceScroll: true,
                         scrollY: true,
                         width: 340,
                         height: $(window).height() < 400 ? $(window).height()-100 : 400
                         },
                         function(){
                             $.get(base_url+'users/get_user_type',function(result){
                                if(result!=1)
                                    $('.jValidate').jValidate();   
                             })
                             
                         });
    });
    
    function datepicker()
    {
    	if($('.datepicker').length)
    	{
	    	var dateToday = new Date();
	    	$.datepicker.setDefaults( $.datepicker.regional[ "pl" ] );
	        $('.datepicker').datepicker( {
	            dateFormat: 'yy-mm-dd',
	            minDate: $('.datepicker').data('id-logged') ? false : dateToday,
	            defaultDate: $('.datepicker').data('date'),
	            onSelect: function(data){
	            	if($('#lunch_data').length)
	            		location.href = base_url+'users/profil/id/'+$('#id_users').val()+'/data/'+data;
	        		else if($('#data_lista').length)
	        			$('#data').val(data);
	            },
                    onClose: function() {
                        $(this).trigger('change');
                    }
	        } );
       }
    }
    
    datepicker();
    
    if($('.timepicker').length)
    {
    	$('.timepicker').timepicker({ 'timeFormat': 'H:i' });
    }

	/***************mapka******************************/  
    
 

	$('#form_typ_konta').change(function(){
		
		var val = $(this).val();
		
		if(val=='agencja')
			$('#dane_agencji').removeClass('hidden').find(':text').addClass('jRequired');
		else
		{
			$('#dane_agencji').addClass('hidden').find(':text').removeClass('jRequired err_input');
			$('.err_container').remove();
		}
		
	});
	
	$('#form_typ_dokumentu').change(function(){
		
		var val = $(this).val();
		
		if(val!='paragon')
		{
			$('#nabywcaWrapper,#odbiorcaWrapper').removeClass('hidden').find(':text').addClass('jRequired');
			$('.kopiuj_dane_nabywcy').removeClass('hidden');
			
		    $.post(base_url+'placowki/get_dokument_sprzedazy',{id: val}, function(a_dane){

		        $('#form_nazwa_nabywcy').val(a_dane['nabywca_nazwa']);
		        $('#form_adres_nabywcy').val(a_dane['nabywca_adres']);
		        $('#form_kod_pocztowy_nabywcy').val(a_dane['nabywca_kod_pocztowy']);
		        $('#form_poczta_nabywcy').val(a_dane['nabywca_poczta']);
		        $('#form_nip_nabywcy').val(a_dane['nabywca_nip']);
		        
		        $('#form_nazwa_platnika').val(a_dane['platnik_nazwa']);
                $('#form_adres_platnika').val(a_dane['platnik_adres']);
                $('#form_kod_pocztowy_platnika').val(a_dane['platnik_kod_pocztowy']);
                $('#form_poczta_planika').val(a_dane['platnik_poczta']);
                
                if($('#id_dokumenty_sprzedazy').length)
                    $('#id_dokumenty_sprzedazy').val(a_dane['id_dokumenty_sprzedazy']);
                else
                    $('#form_typ_dokumentu').after('<input type="hidden" name="a_dokument[id_dokumenty_sprzedazy]" value="'+a_dane['id_dokumenty_sprzedazy']+'" id="id_dokumenty_sprzedazy">');

		    },'json')
		}
		else
		{
			$('#nabywcaWrapper,#odbiorcaWrapper').addClass('hidden').find(':text').removeClass('jRequired').val('');
			$('.kopiuj_dane_nabywcy').addClass('hidden');
		}
		
	});
	
	$('.kopiuj_dane_placowki').click(function(e){
		e.preventDefault();
		
		$('#form_nazwa_platnika').val($('#form_nazwa').val());
		$('#form_adres_platnika').val($('#form_adres').val());
		$('#form_kod_pocztowy_platnika').val($('#form_kod_pocztowy').val());
		$('#form_poczta_platnika').val($('#form_poczta').val());
	});
	
	$('.kopiuj_dane_placowki_dokument_sprzedazy').click(function(e){
        e.preventDefault();
        
        $('#form_nazwa_nabywcy').val($('#form_nazwa').val());
        $('#form_adres_nabywcy').val($('#form_adres').val());
        $('#form_kod_pocztowy_nabywcy').val($('#form_kod_pocztowy').val());
        $('#form_poczta_nabywcy').val($('#form_poczta').val());
    });
	
	$('.kopiuj_dane_nabywcy').click(function(e){
		e.preventDefault();
		
		$('#form_nazwa_platnika').val($('#form_nazwa_nabywcy').val());
		$('#form_adres_platnika').val($('#form_adres_nabywcy').val());
		$('#form_kod_pocztowy_platnika').val($('#form_kod_pocztowy_nabywcy').val());
		$('#form_poczta_platnika').val($('#form_poczta_nabywcy').val());
	});
	
	$('.kopiuj_dane_nabywcy_wysylka').click(function(e){
        e.preventDefault();
        
        $('#form_nazwa_wysylki').val($('#form_nazwa_nabywcy').val());
        $('#form_adres_wysylka').val($('#form_adres_nabywcy').val());
        $('#form_kod_pocztowy_wysylka').val($('#form_kod_pocztowy_nabywcy').val());
        //
        $('#form_poczta_wysylka').val($('#form_poczta_nabywcy').val());
    });
	
	$('.kopiuj_dane_placowki_do_wysylki').click(function(e){
		e.preventDefault();
		
		$('#form_nazwa_wysylki').val($('#form_nazwa').val());
		$('#form_adres_wysylka').val($('#form_adres').val());
		$('#form_kod_pocztowy_wysylka').val($('#form_kod_pocztowy').val());
		$('#form_poczta_wysylka').val($('#form_poczta').val());
	});
	
	$('#szukaj_placowek').keyup(function(e){
	
		$('li','.lista_placowek').hide();
		var val = $(this).val();

        $('.lista_placowek').find('li').each(function(i,el){
            if($(el).data('nazwa').toLowerCase().indexOf(val.toLowerCase())!==-1)
                $(el).show();
        });
	
	});
	
	$('#pola_karty').on('click','.usun_pole_karty',function(e){
		e.preventDefault();
		$(this).closest('tr').remove();
	})
	
	$('#pola_karty').on('click','.dodaj_pole_karty',function(e){
		e.preventDefault();
		var tr = $(this).closest('tr'),
			kopia = tr.clone(true,true),
			pola_karty = $('#pola_karty'),
			number = pola_karty.find('tr').length+1;
			
		pola_karty.append(kopia);
		
		tr.find('td:last').html('<a href="#" class="button usun_pole_karty">usuń</a>');
		
		$('#pola_karty').find('tr:last').find('input,select').each(function(i,el){
			$(el).attr('name',$(el).attr('name').replace(/a_pola\[(\d)+\]/g,'a_pola['+number+']')).val('');
		});
	})
	
	$(document).on('keyup','.legitymacja_pole_txt',function(e){
		var $this = $(this),
			tr = $this.closest('tr'),
			liczba_znakow = $(this).val().length,
			liczba_znakow_wrapper = tr.find('.legitymacja_liczba_znakow');
		
		liczba_znakow_wrapper.find('span').html(liczba_znakow);
		
		if(liczba_znakow>liczba_znakow_wrapper.data('dopuszczalna-liczba-znakow'))
			liczba_znakow_wrapper.addClass('error');
		else
			liczba_znakow_wrapper.removeClass('error');
			
		$this.val($this.val().toUpperCase());
		
	});
	
	$(document).on('blur','.numer_legitymacji_input',function(e){
	    var $this = $(this);
	    $.get(base_url+'legitymacje/sprawdz_numer_legitymacji/numer/'+$this.val().replace('/','---')+'/id_karty/'+$this.closest('form').find('[name="id_karty"]').val()+'/id_legitymacje/'+$this.closest('form').find('[name="id_legitymacje"]').val(), function(a_result){
	        if(a_result['result']==false && !$this.next('p').length)
	           $this.after('<p class="error">Taki numer legitymacji już istnieje</p>');
	        else
	           $this.next('p').remove(); 
	    }, 'json')
	});
	
	
	$(document).on('change keypress paste focus textInput input keyup','.strtoupper',function(e){
		$(this).val($(this).val().toUpperCase());
	});
	
	$(document).on('click','.legitymacja_save',function(e){
		e.preventDefault();
		const tr = $(this).closest('tr'),
		      val = tr.find('select').length ? tr.find('select').val() : tr.find(':text').val(),
		      id_pola = $(this).data('id-pola');
		
		$.get(base_url+'legitymacje/zapamietaj_wartosc/wartosc/'+val+'/id_pola/'+id_pola);
	});

	function okres_zatrudnienia_czas()
	{
		$('.okres_zatrudnienia_czas').on('ifClicked', function(event){
			var val = $(this).val();
			
			if(val=='na_czas_okreslony')
				$('.okres_zatrudnienia_czas_data').removeClass('hidden').find(':text').addClass('jRequired jDate');
			else
			{
				$('.okres_zatrudnienia_czas_data').addClass('hidden').find(':text').removeClass('jRequired jDate err_input ').val('');
				$('.okres_zatrudnienia_czas_data').find('.err_container').remove();
			}
		});
	}
	
	function zdjecie_i_podpis_zlozone()
	{
		$('.zdjecie_i_podpis_zlozone').on('ifClicked', function(event){
			var val = $(this).val();
			
			if(val=='dodam_teraz')
				$('.zdjecie_i_podpis_wrapper').removeClass('hidden').find('input').addClass('jRequired');
			else
				$('.zdjecie_i_podpis_wrapper').addClass('hidden').find('input').removeClass('jRequired');
		});
	}
	
	$(document).on('click','.zapisz_dane_legitymacji',function(e){
		zapisz_dane_legitymacji(e);
	});
	
	function zapisz_dane_legitymacji(e,callback)
	{
		e.preventDefault();
		var button = $('.zapisz_dane_legitymacji'),
		    form = button.closest('form');
		
		if(form.find('.error').length && !button.next('p').length)
		{
			button.after('<p class="error">Przekroczono długość napisu</p>');
			return false;
		}
		else
			button.next('p').remove();
			
		if(button.hasClass('dodawanie_zdjec') || !form.find('.err_container').length && !form.find('.error').length)
		{
			$.post(base_url+'legitymacje/zapisz_dane_legitymacji',{a_dane: form.serialize()}, function(a_result){

				if(!button.hasClass('dodawanie_zdjec'))
					window.location = base_url+'legitymacje/lista-osob-legitymacji/id_karty/'+$('[name="id_karty"]').val();
				
			},'json')
		}	
	};
	/****************************************************************************/
	
	var jcrop_api;
    /*
    $(document).on('change','#formatKadrowania,#orientacjaTla',function(){
        var ratio = $(this).val();
        
        jcrop_api.setOptions({
            aspectRatio: ratio
        });
        
        $('#miniaturePreview').css('height',200/ratio);
        updateCoords(jcrop_api.tellSelect());
    });
           */       
           
    $(document).on('click','.file_upload',function(){
        loadImageUpload($(this).data('typ'),$(this).data('zrodlo'),$(this).data('id-legitymacji'))
    });       
                      
    function loadImageUpload(type,zrodlo,id_legitymacji)
    {
    	if(!$('#modal').find('.error').length)
    	{
	        var base_url = $('body').data('base-url'),
	        	js = $('body').data('js'),
	            uniqid = 1,
	            id_legitymacji = id_legitymacji || false,
				ratio = type=='zdjecie' ? 0.73 : 4.1,
	            saveUrl = 'images/users/temp/',
	            url = base_url+js+'libs/file_upload/server/php/index.php?saveUrl='+saveUrl+'&noThumb=1&uniqid='+uniqid;
	
	            'use strict';
	
	            $('.file_upload').fileupload({
	            	add: function(e, data) {
					        var uploadErrors = [];
					        var acceptFileTypes = /^(image|application)\/(gif|jpe?g|png|pdf)$/i;
					        if(data.originalFiles[0]['type'].length && !acceptFileTypes.test(data.originalFiles[0]['type'])) {
					            uploadErrors.push('Not an accepted file type');
					        }
					        if(data.originalFiles[0]['size'] > 10000000) {
					            uploadErrors.push('Rozmiar pliku musi być mniejszy niż 10MB');
					        }
					        if(uploadErrors.length > 0) {
					            alert(uploadErrors.join("\n"));
					        } else {
					            data.submit();
					        }
					},
	                maxFileSize: 50000,
	                loadImageMaxFileSize: 50000,
	                url: url,
	                fail: function (e,data) {
	                    alert('Błąd przesyłu zdjęcia');
	                },
	                start: function(){
	                    loader();
	                },
	                done: function (e,data) {
	                    if(data.jqXHR.responseText.match(/error":"([A-Za-z\s]+)"/)[1]=='abort')
	                    {
	                        $('.loader').remove();
	                                      
	                        $.each(data.files, function (index, file) {
	
	                            var ext = file.name.split('.').pop();
	                            var name = data.result.match(/name":"([^"]+)/)[1];
	
	                            if(ext=='pdf')
	                                name+='.png';
	
	                            var html = '<div id="uploadedPhoto">';
	                            
	                            //html += '<div class="right">';
	                            html += '<form method="POST" action="'+base_url+'" class="jValidate">';
	                            
	                            html += '<input type="hidden" name="module" value="legitymacje">';
	                            html += '<input type="hidden" name="action" value="zapisz_zdjecie">';
	                            html += '<input type="hidden" name="id_legitymacje" value="'+id_legitymacji+'">';
	                            html += '<input type="hidden" name="img" id="img">';
	                            html += '<input type="hidden" id="x" name="x">';
	                            html += '<input type="hidden" id="y" name="y">';
	                            html += '<input type="hidden" id="w" name="w">';
	                            html += '<input type="hidden" id="h" name="h">';
	                            html += '<input type="hidden" name="rodzaj" value="'+type+'">';
	                            
	                            if(file.size<15000)
	                                html += '<p class="communicat_info uploadZdjeciaKomunikat">Zdjęcie może mieć zbyt niską jakość</p>';    
								html += '<p class="h2">Wybierz obszar kadrowania</p>';
	                            html += '<input type="submit" value="Zapisz" id="tworzZdjecie" disabled data-zrodlo="'+zrodlo+'" data-id-legitymacji="'+id_legitymacji+'">';
	                            html += '<div class="marginTop10"></div>';
	                            html += '<img id="photoToCrop" src="'+base_url+saveUrl+name+'">';
	
	                            //html += '<div id="miniaturePreview"><img src="'+base_url+saveUrl+name+'"></div>';
	                            html += '</div></form>';
	
								$('.zapisz_dane_legitymacji').addClass('dodawanie_zdjec');
								
								if($('#modal').length)
	                               zapisz_dane_legitymacji(e);
                                formularzKadrowania(html, type, ext, ratio, saveUrl, name)
	                        });
	                    }
	                    else
	                    {
	                        $('body').modal({content: '<p class="communicat_error">Plik pdf może mieć tylko jedną stronę</p>'});
	                        $('.loader').remove();
	                    }
	                }//done   
	            });//fileupload
	        }//if
    }//function
    
    function formularzKadrowania(html, type, ext, ratio, saveUrl, name)
    {
        var header = '<h1>Formularz legitymacji - zaznacz obszar ';
        
        header += type=='zdjecie' ? 'zdjęcia</h1>' : 'podpisu</h1>';
        
        $('body').modal({content: html,
                         draggable:false,
                         height: window.innerHeight-200,
                         width: window.innerWidth-400,
                         header:  header,
                         scrollY: false},function(){
            $('#photoToCrop').load(function(){
                
                //trzeba raz jeszcze pobrać oryginalne wymiary zdjęcia, gdyż jCropp
                //klonuje zdjęcie już przeskalowane i przez to modal obu przypisuje
                //przeskalowane wymiary
                $("<img/>").attr("src", $('#photoToCrop').attr("src")).load(function() {
                    var pic_real_width = this.width;
                    var pic_real_height = this.height;
                    $('#photoToCrop').attr('data-original-width',pic_real_width);
                    $('#photoToCrop').attr('data-original-height',pic_real_height);

                    $('#img').val(base_url+saveUrl+name);
            
                    if(ext=='png' || ext=='gif')
                        var bgColor = 'transparent';
                    else
                        var bgColor = 'black';

                    //$('#miniaturePreview').css('height',200/ratio);
                    $('#modalWrapper').center({vertical: false});

                    $('#photoToCrop').Jcrop({
                          aspectRatio: ratio,
                          onSelect: updateCoords,
                          bgColor: bgColor,
                          //setSelect: [0,0,$('#photoToCrop').width(),$('#photoToCrop').height()],
                          onChange: function(){
                              //$('#tworzZdjecie').removeAttr('disabled');
                              updateCoords;
                          }
                        },function(){
                            jcrop_api = this;
                            $("#modal").getNiceScroll().resize()
                        }); 
                    });//load
                });//load
        }); //modal
    }
    
    function updateCoords(c)
    {
    	$('#tworzZdjecie').removeAttr('disabled');
        var winHeight = window.innerHeight,
            winWidth = window.innerWidth,
            imgHeight = $('#photoToCrop').data('original-height'),
            imgWidth = $('#photoToCrop').data('original-width'),
            heightRatio = (winHeight-100)/imgHeight,
            widthRatio = (winWidth-400)/imgWidth;

        var imgRatio = 1,
            imgHeightRatio = 1,
            imgWidthRatio = 1;
            
        if(imgHeight>winHeight-100)
           var imgHeightRatio = 1/heightRatio;
            
        if(imgWidth>winWidth-400)
           imgWidthRatio = 1/widthRatio;
        
        imgRatio = imgHeightRatio>imgWidthRatio ? imgHeightRatio : imgWidthRatio;
        $('#x').val(c.x*imgRatio);
        $('#y').val(c.y*imgRatio);
        $('#w').val(c.w*imgRatio);
        $('#h').val(c.h*imgRatio);
        
        //showPreview(c);
    }
    
    function showPreview(coords)
    {
        var minPreview = $('#miniaturePreview'),
            photoToCrop = $('#photoToCrop'),
            rx = minPreview.width() / coords.w,
            ry = parseInt(minPreview.css('height')) / coords.h;
            
        $('#miniaturePreview img').css({
            width: Math.round(rx * photoToCrop.width()) + 'px',
            height: Math.round(ry * photoToCrop.height()) + 'px',
            marginLeft: '-' + Math.round(rx * coords.x) + 'px',
            marginTop: '-' + Math.round(ry * coords.y) + 'px'
        });
    }
    
    $(document).on('click','#tworzZdjecie',function(e){
        e.preventDefault();
        var zrodlo = $(this).data('zrodlo'),
            id_legitymacji = $(this).data('id-legitymacji');
        
        if(!$(this).is(':disabled'))
        {
	        loader();
	        var $this = $(this);
	        $.post(base_url+'legitymacje/zapisz_zdjecie',$this.closest('form').serialize(),function(a_data){
	            
	            $('.loader').remove();
	            
	            if(zrodlo=='formularz')
	            {
    	            var url = base_url+'legitymacje/formularz-osoby/id_legitymacje/'+a_data['id_legitymacje'];
    	            make_modal(url);
	            }
	            else
	            {
	                $('.modalClose').click();
	                $('.file_upload[data-id-legitymacji="'+id_legitymacji+'"]').closest('td').find('span').html('zdjęcia są obecnie poddawane obróbce');
	            }
	            
	            
	        },'json');
        }
        else
        {
        	$(this).after('<p class="err">Musisz wykadrować obszar</p>');
        }
    });
	
	if($('.dataTables').length)
    {
        var options = {
           pageLength: 100,
           lengthMenu: [[10, 25, 35, 50, 100, 200, 500, 1000, 2000], [10, 25, 35, 50, 100, 200, 500, 1000, 2000]],
           oSearch: {"bSmart": false},
           tableTools: {
                "sSwfPath": base_url+js+"libs/DataTables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
            },
           language: {
                lengthMenu: 'Pokaż'+" _MENU_ "+'rekordów',
                search: "szukaj",
                info: 'Strona'+" _PAGE_ "+'z'+" _PAGES_",
                infoEmpty: 'Brak wyników',
                emptyTable: 'Brak danych',
                infoFiltered: "(przefiltrowane z"+" _MAX_ "+'rekordów)',
                zeroRecords: 'Brak wyników szukania',
                paginate: {
                    first: 'pierwsza',
                    last: 'ostatnia',
                    next: 'następna',
                    previous: 'poprzednia',
                }
           }
        };
		
        var DataTable = $('.dataTables').DataTable(options);
        
        $('.checkAll').click(function(){
            
            var rows = DataTable.rows({ 'search': 'applied' }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
            
        });

        $('#tabela_dane_karty_filter').find('label').append(' <span>Pasujących rekordów: '+DataTable.rows().data().length+'</span>');
        
        $('#tabela_dane_karty').on( 'search.dt', function (settings, data, dataIndex) {
            var rows = 'Pasujących rekordów: '+data.aiDisplay.length,
                span = $('#tabela_dane_karty_filter').find('span');
            
            if(span.length)
                span.html(rows);
            else
                $('#tabela_dane_karty_filter').find('label').append(' <span>'+rows+'</span>');

        } );
    }
    
    if($('.data_maska').length)
    {
    	$('.data_maska').mask();
    }
    
    $(document).on('click','.pokazHistorieZamowienLegitymacji',function(){
        $.post(base_url+'zamowienia/zwroc_historie_zamowien_legitymacji',{id_legitymacji:$(this).closest('tr').data('id-osoby')},function(a_dane){

           var html = '<table><tr><th>Data zamówienia</th><th>Status</th></tr>';

           $.each(a_dane['a_zamowienia'],function(i,el){
               html += '<tr>';
               
               html += '<td>'+el['data_zlozenia']+'</td>';
               html += '<td>'+el['status']+'</td>';
               html += '</tr>';
           });
           
           html += '</table>';
           
           $('body').modal({content: html,header:'<h1>Historia zamówień</h1>'});
           
       },'json');
    });
    
    $('#pracodawcy').on('click','a.czy_usunac_pracodawce',function(e){
        e.preventDefault();
        var content = "<p class='communicat_question'>Czy chcesz usunąć ten rekord?</p>";
        content+='<a href="#" class="usunPracodawce modalClose green left button buttonIcon takButton">Tak</a><a href="#" class="modalClose red right button buttonIcon anulujButton">Nie</a>';
        $('body').modal({content: content,height: 0});
        var tr=$(this).closest('tr');
        var id=tr.data('id');
        
        $('.usunPracodawce').click(function(){
            $.ajax({url: base_url+'placowki/usun_pracodawce',
                method: 'POST',
                data: {id:id},
                success: function(){
                    tr.remove();
                }
            });
        });
        
    });
    
    $('.zamow_legitymacje').click(function(e){
    	e.preventDefault();
    	
    	if(!$('.checkAllTarget:checked').length)
    		$('body').modal({content: "<p class='communicat_error'>Musisz wybrać legitymacje, które chcesz dodać do koszyka</p>",height: 0});
    	else
    	{
    		$.post(base_url+'koszyk/dodaj_do_koszyka',{a_dane: $('.listaOsob').closest('form').serialize()},function(a_result){
    			
    			if(a_result['result']==true)
    				$('.liczba_kart').html(a_result['liczba_kart']);
    				
				//komunikat_floating(a_result['result'],a_result['comm']);
    			$('body').modal({content: "<p class='communicat_info'>Dodano legitymacje do koszyka</p><a href='"+base_url+"koszyk/koszyk' class='button left autoWidth green buttonIcon takButton'>Przejdź do koszyka</a><a href='#' class='button right modalClose autoWidth green buttonIcon dalejButton'>Kontynuuj</a>"});
    		},'json');
    	}
    });
    
    $('.dodaj_produkt_do_koszyka').click(function(e){
        e.preventDefault();
        var $this = $(this),
            form = $this.closest('form');
        
        if(!form.find('.err_container').length)
        {
            $.post(base_url+'koszyk/dodaj_do_koszyka',{a_dane: form.serialize()},function(a_result){
                
                if(a_result['result']==true)
                    $('.liczba_kart').html(a_result['liczba_kart']);
                    
                //komunikat_floating(a_result['result'],a_result['comm']);
                $('body').modal({content: "<p class='communicat_info'>Dodano do koszyka</p><a href='"+base_url+"koszyk/koszyk' class='button left autoWidth green buttonIcon takButton'>Przejdź do koszyka</a><a href='#' class='button right modalClose autoWidth green buttonIcon dalejButton'>Kontynuuj zakupy</a>"});
            },'json');
        }
    });

   $('#cenniki').on('click','a.czy_usunac_cennik',function(e){
        e.preventDefault();
        var content = "<p class='communicat_question'>Czy chcesz usunąć ten cennik?</p>";
        content+='<button class="usunCennik modalClose">Tak</button><button class="modalClose">Nie</button>';
        $('body').modal({content: content,height: 0});
        var tr=$(this).closest('tr');
        var id=tr.data('id');
        
        $('.usunCennik').click(function(){
            $.ajax({url: base_url+'cenniki/usun_cennik',
                method: 'POST',
                data: {id:id},
                success: function(){
                    tr.remove();
                }
            });
        });
    });
    
    $(document).on('click','.dodaj_przedzia_cenowy',function(e){
    	e.preventDefault();
    	var $this = $(this),
    		tr = $this.closest('tr'),
    		klon = tr.clone();
    		
		klon.find(':text').val('').each(function(i,el){console.log()
			$(el).attr('name',$(el).attr('name').replace(/(a_przedzialy)\[\d+\](.+)/g,'$1['+($('#modal').find('tr').length-1)+']$2'));
		});
    		
		tr.after(klon);
		$this.replaceWith('<a href="#" class="button usun_przedzia_cenowy">Usuń</a>');
    });
    
    $(document).on('click','.usun_przedzia_cenowy',function(e){
    	e.preventDefault();
    	$(this).closest('tr').remove();
    });
    
    $('.usun_z_koszyka').click(function(){
    	
    	if(!$('.checkAllTarget:checked').length)
    		$('body').modal({content: "<p class='communicat_error'>Musisz wybrać pozyccje, które chcesz usunąć do koszyka</p>",height: 0});
    	else
    	{
    		$.post(base_url+'koszyk/usun_z_koszyka',{a_dane: $('.checkAllTarget').serialize()},function(a_result){
    			
    			
    			location.reload();
    			//$('.liczba_kart').html(a_result['liczba_kart']);
				//komunikat_floating(a_result['result'],a_result['comm']);
				
    			
    		},'json');
    	}
    	
    });
    
    $('#typ_dokumentu').change(function(){
    	
    	if($(this).val()=='paragon')
    	{
    		$('#dokument_sprzedazy_wrapper,#platnik_wrapper').addClass('hidden').find(':text').removeClass('jRequired');
    		$('.err_container').remove();
    		$('.err_input').removeClass('err_input');
    		$('.przelewy_dni').addClass('hidden').find(':radio').removeProp('checked');
    		$('.przelewy_dni').find('.iradio_square-blue').removeClass('checked');
    		$('#sposob_platnosci_2').prop('checked','checked').closest('.iradio_square-blue').addClass('checked');
    		
    	}
		else
		{
			$('#dokument_sprzedazy_wrapper,#platnik_wrapper').removeClass('hidden').find(':text').addClass('jRequired');
			$('.przelewy_dni').removeClass('hidden');
		}
    	
    });
    
    $('.sposob_platnosci').on('ifClicked', function(event){
    	var val = $(this).val();
    	
    	$('.sposob_wysylki').removeProp('checked').closest('div').removeClass('checked').addClass('hidden');
		$('.sposob_wysylki_label').addClass('hidden');
		$('#wysylka_pobranie').addClass('hidden');

    	if(val=='4')
    	{
    		$('#sposob_wysylki_4').prop('checked','checked').closest('div').addClass('checked').removeClass('hidden');
    		$('label[for="sposob_wysylki_4"]').removeClass('hidden');
    	}
    	else if(val=='3')
    	{
    		$('#sposob_wysylki_5').prop('checked','checked').closest('div').addClass('checked').removeClass('hidden');
    		$('label[for="sposob_wysylki_5"]').removeClass('hidden');
    		$('#wysylka_pobranie').removeClass('hidden');
    	}
    	else
    	{
    		$('.sposob_wysylki').closest('div').removeClass('hidden');
    		$('.sposob_wysylki_label').removeClass('hidden');
    		
    		if(val=='1' || val=='2' || val>=5)
    		{
    			$('#sposob_wysylki_5').removeProp('checked').closest('div').removeClass('checked');
    			$('#wysylka_pobranie').addClass('hidden');
    		}
    		else
    			$('#wysylka_pobranie').removeClass('hidden');

			$('#sposob_wysylki_1').prop('checked','checked').closest('div').addClass('checked');
    	}
    });
    
    $('.sposob_wysylki').on('ifClicked', function(event){
    	
    	var val = $(this).val();
    	
    	if(val=='5')
    	{
    		$('.sposob_platnosci').removeProp('checked').closest('div').removeClass('checked').addClass('hidden');
			$('.sposob_platnosci_label').addClass('hidden');
			$('#sposob_platnosci_3').prop('checked','checked').closest('div').addClass('checked').removeClass('hidden');
    		$('label[for="sposob_platnosci_3"]').removeClass('hidden');
    	}
    	else
    	{
    		$('.sposob_platnosci').closest('div').removeClass('hidden');
    		$('.sposob_platnosci_label').removeClass('hidden');
    		$('#sposob_platnosci_przelew').prop('checked','checked').closest('div').addClass('checked');
    	}
    	
    });
    
    $(document).on('click','.przypisz_pracownika_realizujacego_zamowienia',function(e){
    	e.preventDefault();
    	if(!$('.checkAllTarget.zamowienia:checked').length)
    		$('#modalContentWrapper').html('<p class="communicat_error">Nie wybrano żadnego zamówienia</p>');
    	else
    	{
    		$.post(base_url+'zamowienia/przypisz_pracownika_do_zamowien',{id_pracownika: $('#id_pracownika').val(), zamowienia: $('.checkAllTarget.zamowienia:checked').serialize()}, function(a_result){
    			//komunikat_floating(a_result['result'],a_result['comm']);
    			//$('.modalClose').click();
    			location.reload();
    		},'json');
    	}
    })
    
    $(document).on('click','.dodaj_do_druku',function(e){
    	e.preventDefault();
    	if(!$('.checkAllTarget.zamowienia:checked').length)
    		$('body').modal({content: '<p class="communicat_error">Nie wybrano żadnego zamówienia</p>'});
    	else
    	{
    		$.post(base_url+'zamowienia/dodaj_do_druku',{zamowienia: $('.checkAllTarget.zamowienia:checked').serialize()}, function(a_result){
    			//komunikat_floating(a_result['result'],a_result['comm']);
    			location.reload();
    		},'json');
    	}
    })
    
    $(document).on('click','.pobierz_do_druku',function(e){
        e.preventDefault();
        if(!$('.checkAllTarget.zamowienia:checked').length)
            $('body').modal({content: '<p class="communicat_error">Nie wybrano żadnego zamówienia</p>'});
        else
        {
            $.post(base_url+'zamowienia/pobierz_do_druku',{zamowienia: $('.checkAllTarget.zamowienia:checked').serialize()}, function(a_result){
                //komunikat_floating(a_result['result'],a_result['comm']);
                location.reload();
            },'json');
        }
    })
    
    $(document).on('click','.dodaj_do_wydrukowanych',function(e){
        e.preventDefault();
        if(!$('.checkAllTarget.zamowienia:checked').length)
            $('body').modal({content: '<p class="communicat_error">Nie wybrano żadnego zamówienia</p>'});
        else
        {
            $.post(base_url+'zamowienia/zmien_status',{zamowienia: $('.checkAllTarget.zamowienia:checked').serialize(),status: 'wydrukowane'}, function(a_result){
                //komunikat_floating(a_result['result'],a_result['comm']);
                location.reload();
            },'json');
        }
    })
    
    $('.pobierz_oplate').click(function(e){
    	e.preventDefault();
    	if(!$('.checkAllTarget.zamowienia:checked').length)
    		$('#modalContentWrapper').html('<p class="communicat_error">Nie wybrano żadnego zamówienia</p>');
    	else
    	{
    		$.get(base_url+'rozliczenia/get_zapamietana_data_wplaty', function(data){
	    		var content = '<form  class="jValidate"><input type="submit" class="button pobierz_wplaty" value="Pobierz"><table class="noBorder marginTop20" id="tabela_pobieranie_oplat">';
	    		$('.checkAllTarget.zamowienia:checked').each(function(i,el){
	    			var id_zamowienia = $(el).closest('tr').data('id');
		    		content += '<tr><td><input type="text" value="'+data+'"';
		    		content += i==0 ? 'autofocus' : '';
		    		content += ' placeholder="Data wpłaty" class="inline data_wplaty autoWidth jDate" name="a_wplata['+id_zamowienia+'][data_wplaty]"></td>';
		    		content += '<td><input type="text" value="'+$(el).closest('tr').find('.wartosc_brutto').html()+'" placeholder="Kwota wpłaty" name="a_wplata['+id_zamowienia+'][kwota_wplaty]" class="inline kwota_wplaty autoWidth jPrice"></td>';
		    		content += '<td><select  name="a_wplata['+id_zamowienia+'][sposob_wplaty]" class="autoWidth">';
		    		content += '<option>przelew</option>';
		    		content += '<option>gotówka</option>';
		    		content += '<option>przelewy24</option>';
		    		content += '</select></td>';
	    		});
	    		content += '</table></form>';
	    		$('body').modal({content: content,header: '<h1>Pobierz wpłaty</h1>'}, function(){
	    			$('.jValidate').jValidate();
	    		});
    		});
    	}
    });
    
    $(document).on('click','.pobierz_wplaty',function(e){
   		e.preventDefault();
   		$.post(base_url+'rozliczenia/pobierz_wplaty',{a_wplaty: $('#modal').find('form').serialize()},function(a_result){
   			location.reload();
   		})
    });
    
    $(document).on('keyup','.pracodawca_form :text',function(e){
    	
    	var $this = $(this),
    		label = $this.prev('label'),
    		limit = $('#czy_szkoly').length ? 45 : 30;
    		
    	label.find('span').html($this.val().length);
    	
    	if($this.val().length>limit)
    		label.addClass('error');
		else
			label.removeClass('error');
    	
    });
    
	if($('#importowanie_zdjec_button').length)
		var uploader = new ss.SimpleUpload({
	                  button: 'importowanie_zdjec_button',
	                  url: base_url+'legitymacje/importowanie_zdjec',
	                  name: 'filename',
	                  multiple: true,
	                  responseType: 'json',
	                  allowedExtensions: ['jpg','png','gif','jpeg'],
	                  onExtError: function( filename, extension ){
	                    $('#importowanie_zdjec_wrapper').append('<p class="communicat_error">Niewłaściwy typ pliku, dopuszczalne: jpg, jpeg, png, gif</p>');
	                  },
	                  onChange: function( filename, extension, uploadBtn ){
	
	                  },
	                  startXHR: function(filename, size) {                   
	                      loader();
	                  },
	                  endXHR: function(filename) {
	                      $('.loader').remove();
	                      $('#lista_uploadowanych_plikow').prepend('<li>'+filename+' zapisano</li>');
	                  },
	                  startNonXHR: function(filename) {
	                      loader();
	                  },
	                  endNonXHR: function(filename) {
	                      $('.loader').remove();
	                  }
	            });
            
    $(document).on('click','.nextStep,.prevStep',function(e){
        e.preventDefault();
        if(!$('.err_container').length)
        {
            $('.communicat_error').remove();

            var $this = $(this),
                form = $this.closest('form'),
                steps = form.find('.step'),
                chosenStep = form.find('.chosenStep'),
                nextStep = chosenStep.next('.step'),
                prevStep = chosenStep.prev('.step');
            
            if(($this.is('.nextStep:not(:disabled)') && nextStep.length) || $this.is('.prevStep:not(:disabled)') && prevStep.length)
            {
                steps.addClass('hidden')
                chosenStep.removeClass('chosenStep');
            }
            
            if($this.is('.nextStep:not(:disabled)') && nextStep.length)
                nextStep.addClass('chosenStep').removeClass('hidden');
            if($this.is('.prevStep:not(:disabled)') && prevStep.length)
                prevStep.addClass('chosenStep').removeClass('hidden');
                
            $('html,body').scrollTop(0);
        }
    });
    
    $(document).on('click','.migracja_usun_pracodawce',function(e){
    	
    	if($('.migracja_pracodawca').length==1)
    		$('body').modal({content: '<p class="communicat_info">Musisz posiadać przynajmniej jednego pracodawcę</p>'});
    	else
    		$(this).closest('.migracja_pracodawca').remove();
    });
    
    $(document).on('blur','.check_email',function(e){
        var $this = $(this);
        $.ajax({url: base_url+'users/sprawdz_email/val/'+$this.val(),
            method: 'GET',
            async: true,
            dataType: 'json',
            success: function(result){
               if(!result['result'])
                    $this.after('<p class="communicat_error">'+result['comm']+'</p>');
                else
                    $this.nextAll('.communicat_error').remove(); 
            }
        })
    });
    
    $(document).on('blur','#form_regon',function(e){
    	var $this = $(this);
	    $.ajax({url: base_url+'placowki/sprawdz_regon/val/'+$this.val()+'/id_placowki/'+$('#id_placowki').val(),
            method: 'GET',
			async: true,
			dataType: 'json',
            success: function(result){
            	
            	if($('.step').length)
            	{
	            	var nextStep = $this.closest('.step').find('.nextStep');
	            	if(!result['result'])
	            	{
	            		$('body').modal({content: '<p class="communicat_error">'+result['comm']+'</p>'});
	                	nextStep.prop('disabled','disabled');
	                }
	                else
	                {
	                	nextStep.removeProp('disabled');
	                }
                }
                else
                {
                	if(!result['result'])
                		$this.after('<p class="communicat_error">'+result['comm']+'</p>');
                	else
                		$this.nextAll('.communicat_error').remove();
                }
                
            }
        });
   });
   
   $(document).on('blur','.migracja_email',function(e){
    	var $this = $(this);
	    $.ajax({url: base_url+'users/sprawdz_email/val/'+$this.val(),
            method: 'GET',
			async: true,
			dataType: 'json',
            success: function(result){
            	
            	if($('.step').length)
            	{
	            	var nextStep = $this.closest('.step').find('.nextStep');
	            	if(!result['result'])
	            	{
	            		$('body').modal({content: '<p class="communicat_error">'+result['comm']+'</p>'});
	                	nextStep.prop('disabled','disabled');
	                }
	                else
	                {
	                	nextStep.removeProp('disabled');
	                }
                }
            }
        });
   });

	$(document).on('click','.show_password',function(e){
		var $this = $(this),
			input = $this.closest('div').find('input');
		
		if(input.is(':password'))
		{
			input.attr('type','text');
			$this.attr('src',$this.attr('src').replace('eye_icon.png','eye_icon_x.png'));
		}
		else
		{
			input.attr('type','password');
			$this.attr('src',$this.attr('src').replace('eye_icon_x.png','eye_icon.png'));
		}
	});
	
	$('#form_haslo').keyup(function() {
		$(this).closest('div').find('#pass_strength').html(checkStrength($(this).val()))
	})
	
	function checkStrength(password) {
		var strength = 0,
			pass_strength = $('#pass_strength').find('div');
		if (password.length > 8) strength += 1
		// If password contains both lower and uppercase characters, increase strength value.
		if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) strength += 1
		// If it has numbers and characters, increase strength value.
		if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) strength += 1
		// If it has one special character, increase strength value.
		if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1
		// If it has two special characters, increase strength value.
		if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1
		// Calculated strength value, we can return messages
		// If value is less than 2
		if (strength < 2) {
			pass_strength.removeClass().addClass('pass_weak').html('Słabe');
		} else if (strength == 2) {
			pass_strength.removeClass().addClass('pass_good').html('Średnie');
		} else {
			pass_strength.removeClass().addClass('pass_strong').html('Silne');
		}
	}
	
	if($('#import_osob_plik').length)
	 var uploader = new ss.SimpleUpload({
                      button: 'import_osob_plik',
                      url: base_url+'legitymacje/upload_danych_z_pliku',
                      data: {json: 'true'},
                      name: 'uploadfile',
                      responseType: 'json',
                      allowedExtensions: ['csv'],
                      onExtError: function( filename, extension ){
                        $('#import_osob_plik').after('<p class="communicat_error">Nieprawidłowy typ pliku, dopuszczalne: csv</p>');
						$('.loader').remove();
                      },
                      onChange: function( filename, extension, uploadBtn ){
                          $('#import_osob_plik').next('.communicat_error').remove();
                          loader();
                      },
                      onComplete: function( filename, response, uploadBtn ){
                          $('.loader').remove();
                          $('#id_pracodawcy,[for="id_pracodawcy"]').removeClass('hidden').addClass('autoWidth');
                          
                          //var html = '<p class="communicat_info">Pierwszy wiersz (nagłówkowy) pliku CSV jest pomijany.</p>';

                          var html = '<table id="tabela_import" class="marginTop20"><tr>';
                          
                          $.get(base_url+'karty/get_pola_karty/id_karty/'+$('#id_karty').val()+'/bez-zdjecia/1',function(a_wynik_obiekty){
    
                          	for(var i=0; i<response[0].length; i++)
                            {
                              html+='<th><select class="import_danych_wybor_kolumny autoWidth" name="a_kolumny['+i+']" data-chosen="0">';
                              html+='<option value="0"></option>';

                              $.each(a_wynik_obiekty['a_pola'], function(index, a_obiekt){
                                  if(a_obiekt['typ']!='zdjęcie i podpis (złożony)' && a_obiekt['typ']!='szkoła')
                                  {
                                        html+='<option value="'+a_obiekt['id_karty_pola']+'" data-liczba_znakow="'+a_obiekt['liczba_znakow']+'">'+a_obiekt['nazwa']+'</option>';
                                  }
                              });
                              
                              html+='</select></th>';
                            }
                                  
                                  html+='<th><input type="checkbox" class="checkAll" id="zaznaczDane" checked> <label for="zaznaczDane">Zaznacz</label></th>';
    
                                  html+='</tr>';
                                  
                                  $.each(response,function(i,row){
                                      html+='<tr class="';
                                      
                                      if(i>0)
                                        html+=' active';
                                      
                                      html += '">';
                                      
                                      $.each(row,function(i2,cell){
                                          html+='<td><input type="text" value="'+$.trim(cell).toUpperCase()+'" class="autoWidth"></td>';
                                      });
                                      
                                      html+='<td class="center"><input type="checkbox" class="checkAllTarget importDanychZaznaczWiersz" data-check-id="zaznaczDane"';
                                      
                                      if(i>0)
                                        html+=' checked';
                                      
                                      html+='></td>';
                                      
                                      html+='</tr>';
                                  });
                                  html+='</table>';
                                  $('#import_danych_dane').html(html);
                                  $('.checkAll').checkAll();
                                  $('.jValidate').jValidate({autoTrigger: 'submit'});
                                  $('#importuj_dane_confirm').removeClass('hidden');
                          	
                          },'json');
                     }
                 });
                 
     $(document).on('change','.import_danych_wybor_kolumny',function(e){
            var select = $(this);
                value = select.val(),
                option = select.find(':selected').html(),
                prevsious_chosen = select.data('chosen'),
                thNumber = select.parent().index(),
                liczba_znakow = select.find(':selected').data('liczba_znakow');
                
            $(this).data('chosen',value);    
            
            $('tr:not(:first)','#tabela_import').each(function(i,tr){
                var input = $(tr).find('td').eq(thNumber).find('input'),
                    html = input.html();

                if($(tr).find('.importDanychZaznaczWiersz').is(':checked'))
                {
                    var trNumber = $(tr).index()-1;
                    input.attr('name','a_dane['+value+']');
                    
                    input.removeClass('jRequired jMinLength jMaxLength jPesel');
    
                    if($.inArray(option, ['imię 1','nazwisko 1','imiona'])>-1)
                        input.addClass('jRequired');
                        
                    if($.inArray(option, ['imię 1','imię 2','nazwisko 1','nazwisko 2','imiona','nazwiska','pesel','adres','nr legitymacji','stanowisko','data urodzenia','data wydania','okres zatrudnienia (prosty)']))
                    {
                        input.addClass('jMinLength').attr('data-min-length',3);
                        
                        if(liczba_znakow)
                            input.addClass('jMaxLength').attr('data-max-length',liczba_znakow);
    
                        if(option=='pesel')
                            input.addClass('jPesel');

                    }
                    
                    select.data('chosen',value);
                }

            });
            
            var th = select.closest('th'),
                selects = th.prevAll('th').add(th.nextAll('th')).find('select');

            $(selects).each(function(i,element){
                if(value!=0)
                    $(element).find('option[value="'+value+'"]').hide();
                else
                    $(element).find('option[value="'+value+'"]').show();
                $(element).find('option[value="'+prevsious_chosen+'"]').show();
            });
        });
        
        $(document).on('change','.importDanychZaznaczWiersz',function(e){
            var ckb = $(this),
                tr = ckb.closest('tr'),
                inputs = tr.find('input[type="text"]');
            
            if(ckb.is(':checked'))
            {
                tr.addClass('active');
                tr.find('[type="hidden"]').attr('type','text');
            }
            else
            {
                tr.removeClass('active');
                tr.find('[type="text"]').attr('type','hidden');
            }
        });
                
        $(document).on('change','#zaznaczDane',function(e){
            var $this = $(this),
                trs = $this.closest('table').find('tr:not(:first)');
                
            if($this.is(':checked'))
                trs.addClass('active');
            else
                trs.removeClass('active');
        });
        
        $(document).on('click','#importuj_dane_confirm',function(e){
           e.preventDefault();

            var wiersze = $('#tabela_import').find('tr.active'),
                liczba_wierszy = wiersze.length,
                licznik = 0,
                id_pracodawcy = $('#id_pracodawcy').val(),
                wybranych_kolumn = 0,
                czy_nr_legitymacji = false,
                czy_error_nr_legitymacji = false;
                
                $('.import_danych_wybor_kolumny').each(function(i,el){
                    if($(el).val()!=0)
                        wybranych_kolumn++;
                        
                    if($(el).find(':contains(nr legitymacji):selected').length)
                        czy_nr_legitymacji = $(el).closest('th').index();
                });

                if(wybranych_kolumn<4)
                    $('body').modal({content: '<p class="communicat_error">Musisz wybrać przynajmniej cztery kolumny</p>'});
                else if(!wiersze.length)
                    $('body').modal({content: '<p class="communicat_error">Musisz wybrać przynajmniej jeden wiersz</p>'});
                else
                {
                    if($(this).data('error'))
                        $('body').modal({content: '<p class="communicat_error">W formularzu znajdują się pola zawierające błędy. Popraw je i spróbuj jeszcze raz.</p>'});
                    else
                    {
                        loader();

                        wiersze.each(function(i,tr){
                            
                            var inputs = $(tr).find('input'),
                                url_string = inputs.serialize();
                            
                            if(czy_nr_legitymacji)
                            {
                                var input_nr_legitymacji = inputs.eq(czy_nr_legitymacji);

                                $.ajax({url: base_url+'legitymacje/sprawdz_numer_legitymacji/numer/'+input_nr_legitymacji.val().replace('/','---')+'/id_karty/'+$('#id_karty').val(),
                                       method: 'get',
                                       dataType : 'json',
                                       async: false,
                                       success: function(a_result){
                                          if(a_result['result']==false && !input_nr_legitymacji.next('p').length)
                                            {
                                               input_nr_legitymacji.addClass('err_input').after('<p class="error">Taki numer legitymacji już istnieje</p>');
                                               czy_error_nr_legitymacji = true;
                                               $('body').modal({content: '<p class="communicat_error">W formularzu znajdują się pola zawierające błędy. Popraw je i spróbuj jeszcze raz.</p>'});
                                            }
                                            else
                                               input_nr_legitymacji.removeClass('err_input').next('p').remove(); 
                                       }
                                });

                            }

                            if(!czy_error_nr_legitymacji)
                            {
                                $.post(base_url+'legitymacje/importuj_dane/',{a_dane: url_string, id_karty: $('#id_karty').val(), id_pracodawcy: id_pracodawcy}, function(){
                                   licznik++;
                                   
                                   if(licznik==liczba_wierszy)
                               		location.href = base_url+'legitymacje/lista-osob-legitymacji/id_karty/'+$('#id_karty').val();
                                });
                            }
                        });
                    }
                }
                
        });

        $(document).on('click','.wyslij_formularz_aktywacyjny',function(e){
            e.preventDefault();
            const ID_PLACOWKI = $(this).closest('tr').data('id');
            
            $('body').modal({content: '<p class="communicat_question">Czy na pewno chcesz wysłać formularz?</p><a href="#" class="button green left wyslij_formularz_aktywacyjny_confirm modalClose">Tak</a><a href="#" class="button red right modalClose">Nie</a>'}, function(){
                
                $('.wyslij_formularz_aktywacyjny_confirm').click(function(e){
                    e.preventDefault();
                    loader();
                    $.post(base_url+'users/wyslij_formularz_aktywacyjny',{id_placowki: ID_PLACOWKI}, function(a_data){
                        komunikat_floating(a_data['result'],a_data['comm']);
                        $('.loader').remove();
                    },'json')
                });
                
            });
        });
        
        $(document).on('click','.aktywuj_placowke',function(e){
        	e.preventDefault();
        	const ID_PLACOWKI = $(this).closest('tr').data('id');
        	var html = `<form method="POST" action="${base_url}" class="jValidate" enctype="multipart/form-data">
        		  			<input type="hidden" name="id_placowki" value="${ID_PLACOWKI}">
							<input type="hidden" name="module" value="placowki">
							<input type="hidden" name="action" value="aktywuj_placowke">
        		  			<p class="communicat_question">Czy na pewno chcesz aktywować tą placówkę?</p>
        		  			<label for="dokument">dokument</label>
        		  			<input type="file" name="dokument" id="dokument" class="jRequired jExtension" data-extensions="jpg jpeg gif png pdf"> 
        		  			<div class="clear marginTop20"></div>
        		  			<input type="submit" value="Aktywuj" class="green left">
        		  			<a href="#" class="button red right modalClose">Anuluj</a>
        		  			</form>
        		  		 `;
        	
        	$('body').modal({content: html}, function(){
        		
				$('.jValidate').jValidate();
        		
        	});
        });
        
        $('.szukaj_regon').click(function(e){
        	e.preventDefault();
        	const KOD = $(this).closest('fieldset').find('#form_kod_pocztowy').val();
        	
        	if(KOD!='')
        	   $.post(base_url+'placowki/get_regony',{kod_pocztowy: KOD}, function(a_data){
        	       
        	       var html = `<table>
        	                       <tr>
    	                               <th>Województwo</th>
    	                               <th>Miasto</th>
    	                               <th>Nazwa 1</th>
    	                               <th>Nazwa 2</th>
    	                               <th>Ulica</th>
    	                               <th>NR</th>
    	                               <th>Kod pocztowy</th>
    	                               <th>Poczta</th>
    	                               <th>Telefon</th>
    	                               <th>FAX</th>
    	                               <th>WWW</th>
    	                               <th>Regon</th>
    	                               <th>Wybierz</th>
	                               </tr>`;
                   
                   $.each(a_data['a_regony'], function(i,a_regon){
                       html += `<tr>
                                   <td>${a_regon.wojewodztwo}</td>
                                   <td>${a_regon.miasto}</td>
                                   <td>${a_regon.nazwa1}</td>
                                   <td>${a_regon.nazwa2}</td>
                                   <td>${a_regon.ulica}</td>
                                   <td>${a_regon.nr}</td>
                                   <td>${a_regon.kod_pocztowy}</td>
                                   <td>${a_regon.poczta}</td>
                                   <td>${a_regon.telefon}</td>
                                   <td>${a_regon.fax}</td>
                                   <td>${a_regon.www}</td>
                                   <td>${a_regon.regon}</td>
                                   <td><a href="#" class="wybierz_regon_placowki" data-regon="${a_regon.regon}">wybierz</td>
                               </tr>`;
                   })
                   
                   html += '</table>';
        	       
        	       $('body').modal({content: html, height: $(window).height()-200, scrollY: true}, function(){
        	           
        	           $('.wybierz_regon_placowki').click(function(e){
        	               e.preventDefault();
        	               $('#form_regon').val($(this).data('regon'));
        	               $('.modalClose').click();
        	           });
        	           
        	       });
        	   },'json')
    	    else
    	       alert('Musisz wpisać kod pocztowy');
		});
		
		$('.dodaj_dokument_sprzedazy').click(function(e){
		    e.preventDefault();
		    
		    var dokument_sprzedazy = $('#dokument_sprzedazy').clone(true,true);
		    
		    $('body').modal({content: '&nbsp;', height: $(window).height()-100, scrollY: true}, function(){
		        $('#modalContentWrapper').html(dokument_sprzedazy);
		        $('#modalContentWrapper').find('#form_typ_dokumentu,label[for="form_typ_dokumentu"],.dodaj_dokument_sprzedazy,.kopiuj_dane_placowki_dokument_sprzedazy,.kopiuj_dane_placowki,.kopiuj_dane_nabywcy,#id_dokumenty_sprzedazy').remove();
		        $('#modalContentWrapper').find('#dokument_sprzedazy').append('<input type="submit" value="Dodaj" id="dodaj_dokument_sprzedazy_submit" class="buttonIcon green dodajButton">');
		        $('#modalContentWrapper').find('#dokument_sprzedazy').wrap('<form>');
		        
		        $('#modalContentWrapper').find(':text').each(function(i,text){
		            
		            var new_id = 'text'+i;
		            
		            $(text).val('').attr('id',new_id).prev('label').attr('for',new_id);
		        });
		        
		        $('#modalWrapper').center({withScrolling: false});

		    });
		});
		
		$(document).on('click','#dodaj_dokument_sprzedazy_submit',function(e){
		    e.preventDefault();
		    
		    if(!$('#modal').find('.err_container').length)
		    {
    		    $.post(base_url+'placowki/zapisz_dane_dokumentu_sprzedazy',{a_dane: $('#modal').find('form').serialize()}, function(a_result){
                    
                    komunikat_floating(a_result['result'],a_result['comm']);
                    $('#form_typ_dokumentu').append('<option value="'+a_result['id_dokument_sprzedazy']+'">Faktura - '+$('#text0').val()+'</option>');
                    $('.modalClose').click();
                       
                       
                },'json')
            }
		})
		
		function makeHint(object){
		    $(object).each(function(i,e){
                $(e).hint({content: '<img src="'+$(e).data('hint')+'" width="100">'});
            });
		}

        makeHint('.pokazZdjecie');
        
        $(document).on('click','.deleteImage',function(e){
            var $this = $(this),
                id_osoby = $this.data('id-osoby'),
                type = $this.data('type'),
                txt = type=='zdjecie' ? 'to zdjęcie' : 'ten podpis';
                
            $('body').modal({content: '<p class="communicat_info">Czy na pewno chcesz usunąć '+txt+'?</p><a href="'+base_url+'legitymacje/usun_zdjecie/id/'+id_osoby+'/typ/'+type+'/id_karty/'+$('#id_karty').val()+'" class="button green left buttonIcon takButton">Tak</a><a href="#" class="modalClose button red right buttonIcon anulujButton">Nie</a>'});
        });
        
        if($('#komunikat_popup').length)
            $('#komunikat_popup').modal({content: getContent(base_url+'strony/asd,47'), width: $('body').width()/2});
            
        $('#wyslij_formularz').click(function(){
            var formularz = $('#wybor_formularza').val();
            
            $('body').modal({content: '<p class="communicat_question">Czy chcesz wysłać powiadomienie na adres '+$('#email_zalogowanego').val()+'</p><a href="#" class="button green left wyslij_formularz_confirm modalClose">Tak</a><a href="#" class="button red right modalClose">Nie</a>'}, function(){
                
                $('.wyslij_formularz_confirm').click(function(e){
                    e.preventDefault();
                    loader();
                    $.post(base_url+'users/wyslij_formularz',{formularz: formularz}, function(a_data){
                        komunikat_floating(a_data['result'],a_data['comm']);
                        $('.loader').remove();
                    },'json')
                });
                
            });
        });
        
        $(document).on('click','a.czy_usunac_legitymacje',function(e){
            e.preventDefault();
            const tr = $(this).closest('tr'),
                id = tr.data('id-osoby');
            var content = "<p class='communicat_question'>Czy chcesz trwale usunąć wszystkie dane tej osoby?</p>";
                
            content+='<a href="#" class="usunLegitymacje button modalClose green left buttonIcon takButton">Tak</a><a href="#" class="modalClose button red right buttonIcon nieButton">Nie</a>';

            $('body').modal({content: content, height: 0}, function(){

                $('.usunLegitymacje').click(function(e){
                    e.preventDefault();
                    $.post(base_url+'legitymacje/usun_legitymacje', {id:id}, function(a_result){
                        tr.remove();
                        komunikat_floating(a_result['result'],a_result['comm']);
                    },'json');
                });
            });
        });
        
        $(document).on('click','a.czy_anulowac_zamowienie',function(e){
            e.preventDefault();
            const tr = $(this).closest('tr'),
                  id = tr.data('id'),
                  numer = tr.data('numer');
                  
            var content = `<p class='communicat_question'>Czy na pewno chcesz anulować zamówienie nr ${numer} ?</p>`;
            content+='<input type="checkbox" id="anulowanie_zamowienia_mail"> <label for="anulowanie_zamowienia_mail">wyślij powiadomienie</label>';

            content+='<div class="marginTop20"><a href="#" class="anulujZamowienie button modalClose green left">Tak</a><a href="#" class="modalClose button red right">Nie</a></div>';

            
            $('body').modal({content: content, height: 0}, function(){

                $('.anulujZamowienie').click(function(e){
                    e.preventDefault();
                    $.post(base_url+'zamowienia/anuluj_zamowienie', {id:id, czy_wyslac_powiadomienie: $('#anulowanie_zamowienia_mail').is(':checked')}, function(a_result){
                        location.reload();
                    },'json');
                });
            });
        });
        /*
        $(document).on('click','a.drukuj_karty',function(e){
            e.preventDefault();

            if(!$('.user:checked').length)
                $('body').modal({content: '<p class="communicat_info">Musisz wybrać jakieś wiersze</p>'});
            else
            {
                $.post(base_url+'zamowienia/get_zamowione_legitymacje_druk', {a_dane: $('.checkAllTarget:checked').serialize(), id_karty: $('#id_karty').val()}, function(a_result){
                 
                    $('body').modal({content: a_result['html']});
                    
                }, 'json');
            }
            
        });
        */
       
       $('#form_umowy_typy').change(function(){
           if(!$('#id_umowy').length)
           {
               if($(this).val()==1 || $(this).val()==0)
                  $('#action').val('formularz_umowy_krok2');
               else
                  $('#action').val('formularz_umowy_wybor_elegitymacji');
          }
       });
       
       $('.umowy_kopiuj_dane_placowki').click(function(e){
           e.preventDefault();
           
           $('#umowa_dane_placowki').find(':text').each(function(i, pole){
               $(pole).val($(pole).data('dane'));
           });
           
       });
       
       $('.umowy_kopiuj_dane_nabywcy').click(function(e){
           e.preventDefault();
           
           $('#umowa_dane_nabywcy').find(':text').each(function(i, pole){
               $(pole).val($(pole).data('dane'));
           });
           
       });
       
       $('body').on('click','a.zmien_konto_standardowe_na_wewnetrzne',function(e){
            e.preventDefault();
            var content = "<p class='communicat_question'>Czy chcesz zmień to konto?</p>";
            content+='<a href="#" class="zmien_konto_standardowe_na_wewnetrzne_confirm modalClose green left button">Tak</a><a href="#" class="modalClose red right button">Nie</a>';
            $('body').modal({content: content,height: 0});
            var tr=$(this).closest('tr');
            var id=tr.data('id');
            
            $('.zmien_konto_standardowe_na_wewnetrzne_confirm').click(function(){
                $.post(base_url+'users/zmien_konto_standardowe_na_wewnetrzne',{id:id},function(){
                    tr.find('.typ_konta').html('wewnętrzne');
                },'json');
            });
        
       });
       
       $('body').on('change',':radio.czas_umowy',function(e){
           
           if($(this).val()=='nieokreslony')
                $('.okres_obowiazywania').addClass('hidden');
           else
                $('.okres_obowiazywania').removeClass('hidden');
           
       });
       
   $('.poradnikTitle').click(function(e){
        e.preventDefault();
        
        var $this = $(this).parent().next();
        
        $('.poradnikContent').hide();
        
        if($this.is(':visible'))
            $this.slideUp();
        else
            $this.slideDown();
    });
       
   $('.poradnikZwin').click(function(e){
        e.preventDefault();
        $(this).closest('.poradnikContent').slideUp();
    });
    
    // NEW selector
    jQuery.expr[':'].Contains = function(a, i, m) {
      return jQuery(a).text().toUpperCase()
          .indexOf(m[3].toUpperCase()) >= 0;
    };

    
    $('#poradnik_szukaj').keyup(function(){
        var val = $(this).val();
        $('article').show();
        
        if(val!='')
            $('.poradnikTitle:not(:contains("'+val+'"))').closest('article').hide();
    });
    
    $(document).on('click','.pokazPomoc',function(){
        $.get(base_url+'strony/get_site_by_id/id/'+$(this).data('id'),function(a_result){
            
            var text = '';
            
            if($('#szablon_komunikaty_niepokazuj').length)
                text += '<div class="center"><input type="button" class="button green" value="Nie pokazuj automatycznie" id="szablony_niepokazuj_komunikatow"></div>';
            
            
            text += a_result['text'];
            
            
            $('body').modal({content: text,
                             width: 817,
                             height: window.innerHeight-200,
                             scrollY: true,
                             header: '<h1>'+a_result['title']+'</h1>'});
        },'json');
    })
    
    /*******************wiadomosci**************************/
   
   $('.addNextAtachment').click(function(){
       var file = $('#attachments input:first').clone();
       $('#attachments').append(file).find('li:last input').removeAttr('id');
       file.wrap('<li></li>').after('<span>usuń</span>');
   });
   
   $(document).on('click','#attachments span','click',function(){
       $(this).closest('li').remove();
   });
   
   if($('.formMessage').length)
   {
       setInterval(function(){
           var tresc = CKEDITOR.instances['tresc'].getData();
           if(tresc!='')
           {
               $.ajax({url: base_url+'wiadomosci/zapisz_robocza_ajax',
                       data: {a_wiadomosc: $('[name*="a_wiadomosc"]').serialize(),tresc: tresc},
                       method: 'post',
                       dataType : 'json',
                       success: function(a_dane){
                           if(a_dane['result']==true)
                                $('#id_wiadomosci').val(a_dane['id_wiadomosci']);
                       }
                });
            }
        },10000);
   }
   
   $('.checkAll').change(function(){
       $('.check').prop('checked',$(this).prop('checked'));
   });
   
   $('#deleteMessages').click(function(){
       if(!$('#wiadomosciTabel').find('.check:checked').length)
           $('body').modal({content: '<p class="communicat_error">Nie wybrano żadnych wiadomości.</p>'});
       else
       {
           $(this).modal({content: '<p class="communicat_question">Czy na pewno chcesz usunąć te wiadomości?</p><a href="#" id="confirmDeleting" class="button left green modalClose buttonIcon takButton">Tak</a><a href="#" class="button modalClose right red buttonIcon anulujButton">nie</a>'});
           
           $(document).on('click','#confirmDeleting',function(){
               var ids = '';
               $('.check:checked').each(function(index,element){
                   ids += $(element).data('id')+',';
               });
               
               $.post(base_url+'wiadomosci/usun',{ids: ids},function(){
                   $('.check:checked').closest('tr').remove();
               });
               
               //location.reload();
           });
       }
   });
   
   $('.nowe_wiadomosci_icon').jBlink();
   
   /********************mailing************************************/

   $(document).on('click','.usun_zalacznik_szablonu_mailingi',function(e){
        e.preventDefault();
        $.post(base_url+'mailing/usun_zalacznik',{zalacznik: $(this).data('zalacznik')});
        $(this).closest('li').remove();
    });
    
    
    $('#szablony_mailing_dodaj_kolejny_zalacznik').click(function(e){
        e.preventDefault();
        var $this = $(this);
        
        $this.closest('li').before($('#lista_zalacznikow li:first').clone(true));
    });
    
    $(document).on('click','.mailing_wybor_szablonu',function(e){
        
        if(!$('.placowka:checked').length)
            return false;
         
         $.post(base_url+'mailing/get_szablony',function(a_result){
             var html = '<label for="szablon">Wybierz szablon</label>';
             html += '<select id="szablon" class="marginTop10">';
             
             html += '<option value="0">Wybierz</option>';
             
             $(a_result['a_szablony']).each(function(i,a_szablon){
                html += '<option value="'+a_szablon['id_mailing_szablony']+'">'+a_szablon['temat']+'</option>';
             })
             
             html += '</select>';
             html += '<div id="podglad_szablonu"></div>';
             
             if($('.placowka:checked').length>1)
             {
                 var tr = $('.placowka:checked:eq(1)').closest('tr');
                 html += '<input type="button" class="button marginTop10 hidden" value="Poprzednia placówka" id="mailing_podglad_placowka_prev" data-id-placowki="'+tr.data('id')+'">';
                 html += '<input type="button" class="button marginTop10" value="Następna placówka" id="mailing_podglad_placowka_next" data-id-placowki="'+tr.data('id')+'">';
             }
             
             html += '<div></div>';
             
             html += '<input type="button" class="button marginTop10" value="Wyślij" id="mailing_wyslij_confirm">';
             
             html += '<div class="marginTop10"></div><p class="noMargin">Adresaci:</p>';
             html += '<ul>';
             
             $('.placowka:checked').each(function(i,a_placowka){
                html += '<li data-id-placowki="'+$(a_placowka).closest('tr').data('id')+'">'+$(a_placowka).closest('tr').find('td:eq(1)').html()+'</li>';
             });
             
             html += '</ul>';
             
             $('body').modal({content: html, header: '<h1>Wyślij mailing</h1>', scrollY: true, height: $(window).height()-200});
         },'json');
    });
    
    
    $(document).on('click','#mailing_podglad_placowka_next,#mailing_podglad_placowka_prev',function(e){
        
        var id_placowki = $(this).data('id-placowki');
        
        $.get(base_url+'mailing/get_podglad/id_placowki/'+id_placowki+'/id_mailing_szablony/'+$('#szablon').val(),function(a_result){
            
            $('#podglad_szablonu').html(a_result['a_szablon']['text']);
            
            var li = $('#modal').find('li[data-id-placowki="'+id_placowki+'"]'),
                next = li.next(),
                prev = li.prev();
                
            if(next.length)
                $('#mailing_podglad_placowka_next').removeClass('hidden').data('id-placowki',next.data('id-placowki'));
            else
                $('#mailing_podglad_placowka_next').addClass('hidden');
                
            if(prev.length)
                $('#mailing_podglad_placowka_prev').removeClass('hidden').data('id-placowki',prev.data('id-placowki'));
            else
                $('#mailing_podglad_placowka_prev').addClass('hidden');
            
        },'json');
        
    });
    
    $(document).on('change','select#szablon',function(e){
        
        var val = $(this).val(),
            tr = $('.placowka:checked:first').closest('tr');
        
        if(val!=0)
        {
            $.get(base_url+'mailing/get_podglad/id_placowki/'+tr.data('id')+'/id_mailing_szablony/'+val,function(a_result){
                
                var html = '<h2>Podgląd szablonu</h2>',
                    temat = a_result['a_szablon']['temat'] ? a_result['a_szablon']['temat'] : 'brak',
                    tresc = a_result['a_szablon']['text'] ? a_result['a_szablon']['text'] : 'brak';
                html += '<p><span class="bold">Temat</span>: '+temat+'</p>';
                html += '<p><span class="bold">Treść</span>:<br><div id="tresc_powiadomienia">'+tresc+'</div></p>';
                
                $('#podglad_szablonu').html(html);
                $("#modal").getNiceScroll().resize();
                $('#modalWrapper').center();
            },'json');
        }
        else
        {
            $('#podglad_szablonu').html('');
            //$("#modal").getNiceScroll().resize();
            $('#modalWrapper').center();
        }
    });
    
    $(document).on('click','#mailing_wyslij_confirm',function(e){
        
        if(!$('.placowka:checked').length || $('select#szablon').val()==0)
            return false;
            
        var ids = '';
         $('.placowka:checked').each(function(i,a_osoba){
            ids += $(a_osoba).closest('tr').data('id')+',';
         });
         ids = ids.slice(0, -1);
        
         $.post(base_url+'mailing/zapisz_mailing',{ids: ids, id_mailing_szablony: $('#szablon').val()},function(a_result){
                $('.modalClose').click();
                komunikat_floating(a_result['result'],a_result['comm']);
         },'json');
    });
    
    $('.dropdownButton').click(function(e){
        e.preventDefault();
        
        const dropdownMenu = $(this).closest('li').find('ul');
        
        dropdownMenu.toggleClass('hidden');
    });
    
    $(document).on('click','.usunKonto',function(e){
        e.preventDefault();
        const id_users = $(this).data('id');
        
        $('body').modal({content: '<p class="communicat_question">Usunięcie konta jest trwałe i nieodwracalne. Czy chcesz skasować swoje konto</p><a href="#" class="button green left usunKontoConfirm modalClose">Tak</a><a href="#" class="button red right modalClose">Nie</a>'}, function(){
                
            $('.usunKontoConfirm').click(function(e){
                e.preventDefault();
                loader();
                
                $.post(base_url+'users/wyslij_maila_usuniecie_konta',{id_users: id_users}, function(a_data){
                    komunikat_floating(a_data['result'],a_data['comm']);
                    $('.loader').remove();
                },'json')
            });
            
        });
    });
    
    $('body').keydown(function(e){
        if(e.keyCode==13 && $('.kod_karty').is(':focus'))
            $('.kod_karty').blur();
    });

    $(document).on('blur paste','.kod_karty',function(e){
        var $this = $(this),
            kod = $this.val(),
            id_zamowienia = $this.data('id-zamowienia'),
            id_legitymacji = $this.data('id-legitymacje');
 
            loader();

            $.post(base_url+'zamowienia/zapisz_kod_karty',{kod: kod, id_legitymacji: id_legitymacji, id_zamowienia: id_zamowienia},function(a_result){
                
                komunikat_floating(a_result['result'],a_result['comm']);
                $('.loader').remove();
                
                if(a_result['result']==true && $('.dalejButton').length)
                    $('.dalejButton').click();
                
            }, 'json');
    });
    
    $(document).on('click','.czy_wyslac_link_do_generatora_umow',function(e){
            e.preventDefault();

            $('body').modal({content: '<p class="communicat_question">Czy na pewno chcesz wysłać email z generatorem umów?</p><a href="#" class="button green left czy_wyslac_link_do_generatora_umow_confirm modalClose">Tak</a><a href="#" class="button red right modalClose">Nie</a>'}, function(){
                
                $('.czy_wyslac_link_do_generatora_umow_confirm').click(function(e){
                    e.preventDefault();
                    loader();
                    $.post(base_url+'users/wyslij_link_do_generatora_umow', function(a_data){
                        komunikat_floating(a_data['result'],a_data['comm']);
                        $('.loader').remove();
                    },'json')
                });
                
            });
    });
    
    $('.przenies_osoby_do_karty').click(function(e){
        e.preventDefault();
        
        if(!$('.checkAllTarget:checked').length)
            $('body').modal({content: "<p class='communicat_error'>Musisz wybrać osoby, które chcesz przenieść</p>",height: 0});
        else
        {
            $.post(base_url+'legitymacje/get_dostepne_karty',function(a_result){
                
                if(a_result['result']==true)
                {
                    var html = '<h1>Wybierz legitymację</h1><select id="przenoszenie_osoby_wybor_karty">';
                    
                    $.each(a_result['a_karty'],function(i,el){
                        html += '<option value="'+el['id_karty']+'">'+el['nazwa']+'</option>';
                    });
                    
                    html += '</select><a href="#" class="button green przenies_osoby_do_karty_confirm">Przenieś</a>';
                    $('body').modal({content: html}, function(){
                        
                        $('.przenies_osoby_do_karty_confirm').click(function(e){
                            e.preventDefault();
                            
                            $.post(base_url+'legitymacje/przenies_osoby_do_karty', {a_dane: $('.listaOsob').closest('form').serialize(), id_karty: $('#przenoszenie_osoby_wybor_karty').val()},function(a_result){
                                //komunikat_floating(a_result['result'],a_result['comm']);
                                //$('.modalClose').click();
                                location.reload();
                            },'json');
                        });
                        
                    });
                    //
                }
            },'json');
        }
    });
    
    $('.formularz_umowy_rodzaj_elegitymacji :checkbox').on('click', function(){

        if($('.formularz_umowy_rodzaj_elegitymacji :checkbox:checked').length)
            $('input[type="submit"]').removeAttr('disabled');
        else
            $('input[type="submit"]').attr('disabled', 'disabled');
        
    })
    
    $('.zmien_status_umowy').click(function(e){
        e.preventDefault();
        
        $.post(base_url+'umowy/zmien_status_umowy', {umowy: $('.checkAllTarget.umowy:checked').serialize(), status: $(this).data('status')}, function(a_result){
            
            location.reload();
            
        }, 'json');
    });

}) 