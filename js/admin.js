$(function(){
    
    $('.jTabs').jTabs();
    
    var base_url = $('body').attr('data-base-url');
    
    
    
    var fixHelper = function(e, ui) {
        ui.children().each(function() {
            $(this).width($(this).width());
        });
        return ui;
    };


    /*************************galerie i zdjecia***********************************/
    
    function fixMargins()
    {
        $('.galleryItemsAdmin li').css('margin-right', '30px');
        $('.galleryItemsAdmin').find('li:nth-child(4n)').css('margin-right', 0);
    }
    
    //sortowanie
    $('.galleryItemsAdmin').sortable({
        cursor: "move",
        update: function(e, ui){
            fixMargins();
            var dataStr = $('#galleryItems').serializeSortable('.galleryWrapper','data-id','a_items');
            dataStr+='&type='+$(this).attr('data-type');
            $.ajax({url: base_url+'galerie/zapisz_pozycje_galerii',
                    method: 'POST',
                    data: dataStr
            });
        }
    });
    
    $('.galleryItemsAdmin .galleryWrapper').mouseenter(function(){
        if(!$('#galleryOptions').length)
            $(this).append('<span id="galleryOptions"></span>');
    });
    
    
    $('.galleryItemsAdmin .galleryWrapper').mouseleave(function(){
        $(this).find('#galleryOptions').remove();
    });
    
    //menu kontekstowe
    $(document).on('mouseover','#galleryOptions',function(){
        var $this = $(this).closest('.galleryWrapper');
        if(!$this.find('textarea').length)
        {
            $('#galleryItemNav,.galleryWrapper textarea').remove();
            
            if($('#galleryItems').attr('data-type')=='galleries')
            {
                var typeTxt = 'galerię';
                var editLink = '<li id="galleryItemEditSludge"><a href="'+base_url+'galerie/formularz_galerii/id/'+$this.closest('li').attr('data-id')+'">Edytuj dane</a></li>';
                var editMini = '';
                var setAsMain = '';
            }
            else
            {
                var typeTxt = 'zdjęcie';
                var editLink = '';
                var editMini = '<li id="galleryItemEditMini"><span data-href="'+$this.closest('li').find('a:first').attr('data-src')+'/rodzaj/miniatura">Edytuj miniaturę</span></li>';
                var setAsMain = '<li id="galleryItemSetAsMain">Ustaw jako profilowe</li>';
            }

            if($this.hasClass('galleryInvisible'))
                var showHideTxt = 'Pokaż '+typeTxt;
            else
                var showHideTxt = 'Ukryj '+typeTxt;
            
            var html = '<nav id="galleryItemNav"><ul><li id="galleryItemEditTitle">Edytuj tytuł</li>'+editLink+'<li id="galleryItemShowHide">'+showHideTxt+'</li>'+setAsMain+editMini+'<li id="galleryItemDelete">Usuń '+typeTxt+'</li></ul></nav>';
            $this.append(html);
            
            $('#galleryItemEditMini span').click(function(e){
                e.preventDefault();
                var $this = $(this);
                var galleryWrapper = $this.closest('.galleryWrapper');
                var thumbImg = galleryWrapper.find('img').attr('src');//galleryWrapper.css('background-image').match(/url\('?"?([^"']+)'?"?\)/)[1];
                var img = thumbImg.replace('thumbnail/','');

                 $.ajax({url: $this.attr('data-href'),
                    async: false,
                    success: function(content){
                        $('body').modal({content:content,closeOnElement:'#tworzMiniaturke'},function(){
                
                            $('#cropbox').load(function(){
                                var winHeight = $(window).height();
                                var winWidth = $(window).width();
                                var imgHeight = $('#cropbox').attr('data-original-height');
                                var imgWidth = $('#cropbox').attr('data-original-width');
                                var heightRatio = (winHeight-100)/imgHeight;
                                var widthRatio = (winWidth-100)/imgWidth;
                                
                                ratio = 1;
                                if(imgHeight>winHeight-100)
                                   ratio = 1/heightRatio;
                                if(imgWidth>winWidth-100)
                                   ratio = 1/widthRatio;
                        
                                $('#tworzMiniaturke').click(function(e){
                                    e.preventDefault();
                                    
                                    $.ajax({url: base_url+'galerie/tworz-miniaturke',
                                        method: 'POST',
                                        data: {x:$('#x').val()*ratio, y:$('#y').val()*ratio,w:$('#w').val()*ratio,h:$('#h').val()*ratio,img:$('#img').val()},
                                        success: function(){
                                            galleryWrapper.find('img').attr('src',thumbImg+'?t='+uniqid());
                                        }
                                    });
                                });
                                
                                function updateCoords(c)
                                {
                                      $('#tworzMiniaturke').removeAttr('disabled');
                                      $('#x').val(c.x);
                                      $('#y').val(c.y);
                                      $('#w').val(c.w);
                                      $('#h').val(c.h);
                                }
                    
                                $('#cropbox').Jcrop({
                                  aspectRatio: 1.31,
                                  onSelect: updateCoords
                                });
                            });
                        });
                    }
                });
            });
        }
    });
    
    $(document).on('mouseleave','#galleryItemNav',function(){
        if(!$(this).closest('.galleryWrapper').find('textarea').length)
            $('#galleryItemNav').remove();
    });
    
    //edycja tytulu i linku
    $(document).on('click','#galleryItemEditTitle',function(){
        var $this = $(this);
        
        if($this.is(':not(.galleryItemSave)'))
        {
            var galleryWrapper = $this.closest('.galleryWrapper');
            
            if($this.is('#galleryItemEditTitle'))
                var editableTxt = galleryWrapper.find('p.title').html();

            if(typeof editableTxt === 'undefined')
                editableTxt = '';
                
            galleryWrapper.append('<textarea>'+editableTxt+'</textarea>').find('textarea').focus();

            $this.siblings().hide();
            $this.html('Zapisz').addClass('galleryItemSave').after('<li id="galleryItemCancel">Anuluj</li>');
        }
    });
    
        //zapis nowego tytułu i linku
        $(document).on('click','#galleryItemNav .galleryItemSave',function(){
            
            var $this = $(this);
            var galleryWrapper = $this.closest('.galleryWrapper');
            
            if($this.is('#galleryItemEditTitle'))
                var field = 'title';
            
            if($this.is('#galleryItemEditSludge'))
                var field = 'sludge';
            
            var value = galleryWrapper.find('textarea').val();

            $.ajax({url: base_url+'galerie/edytuj_galerie',
                data: {id: galleryWrapper.attr('data-id'),value: value,type: $('#galleryItems').attr('data-type'),field: field},
                method: 'POST',
                success: function(){
                    if(field=='title')
                    {
                        galleryWrapper.find('p.title').html(value);  
                        galleryWrapper.find('textarea').remove();
                    }
                    else if(field=='sludge')
                    {
                        galleryWrapper.find('p.title').attr('data-sludge',value);  
                        galleryWrapper.find('textarea').remove();
                    }
                    
                    restartContextMenu();
                }
            });
        });
        
        function restartContextMenu()
        {
            $('#galleryItemNav li').show();
            
            if($('#galleryItemEditTitle').html()=='Zapisz')
                $('#galleryItemEditTitle').html('Edytuj tytuł').removeClass('galleryItemSave');
                
            if($('#galleryItemEditSludge').html()=='Zapisz')
                $('#galleryItemEditSludge').html('Edytuj przyjazny link').removeClass('galleryItemSave');
            
            $('#galleryItemCancel').remove();
        }
        
        //anulowanie operacji edycji tytułu i linku
        $(document).on('click','#galleryItemCancel',function(){
            
            $('#galleryItemNav li').show();
            
            var $this = $(this);
            
            var galleryWrapper = $this.closest('.galleryWrapper');
            var textarea = galleryWrapper.find('textarea');
            galleryWrapper.find('p.title').html(textarea.val());
            textarea.remove();
            
            restartContextMenu();
        });

    //ukrywanie galerii
    $(document).on('click','#galleryItemShowHide',function(){
        var $this = $(this);
        var galleryWrapper = $this.closest('.galleryWrapper');
        
        if($('#galleryItems').attr('data-type')=='galleries')
            var typeTxt = 'galerię';
        else
            var typeTxt = 'zdjęcie';
        
        if(galleryWrapper.hasClass('galleryInvisible'))
        {
            galleryWrapper.removeClass('galleryInvisible');
            $this.html('Ukryj '+typeTxt);
            var isVisible=1;
        }
        else
        {
            galleryWrapper.addClass('galleryInvisible');
            $this.html('Pokaż '+typeTxt);
            var isVisible=0;
        }

        $.ajax({url: base_url+'galerie/zmien_widocznosc',
            data: {id: galleryWrapper.attr('data-id'),is_visible: isVisible,type: $('#galleryItems').attr('data-type')},
            method: 'POST'
        });

    });
    
    //usuwanie galerii
    $(document).on('click','[data-type="galleries"] #galleryItemDelete',function(){
        var content = "<p class='communicat_question'>Czy chcesz usunąć tą galerię i wszystkie zdjęcia znajdujące się w niej?</p>";
        
        var galleryWrapper = $('#galleryItemDelete').closest('.galleryWrapper');
        var id_galleries = galleryWrapper.attr('data-id');
        
        content+='<button class="usunGalerie modalClose" data-id="'+id_galleries+'">Tak</button><button class="modalClose">Nie</button>';
        $('body').modal({content: content,height: 0});

    });
    
    $(document).on('click','.usunGalerie',function(){
            var id=$(this).attr('data-id');
            $.ajax({url: base_url+'galerie/usun_galerie',
                method: 'POST',
                data: {id_galleries:id},
                success: function(){
                    $('.galleryWrapper[data-id="'+id+'"]').fadeRemove(function(){$('.galleryItemsAdmin').find('li');fixMargins();});
                }
            });
        });
    
    //usuwanie zdjęcia
    $(document).on('click','[data-type="photos"] #galleryItemDelete',function(){
        var content = "<p class='communicat_question'>Czy chcesz usunąć to zdjęcie?</p>";       
        
        var galleryWrapper = $('#galleryItemDelete').closest('.galleryWrapper');
        
        var id_photos = galleryWrapper.attr('data-id');
        
        content+='<button class="usunZdjecie modalClose" data-id="'+id_photos+'">Tak</button><button class="modalClose">Nie</button>';
        $('body').modal({content: content,height: 0});

    });
    
    $(document).on('click','.usunZdjecie',function(){
            var id=$(this).attr('data-id');
            $.ajax({url: base_url+'galerie/usun_zdjecie',
                method: 'POST',
                data: {id_photos:id},
                success: function(){
                    $('.galleryWrapper[data-id="'+id+'"]').fadeRemove(function(){$('.galleryItemsAdmin').find('li');fixMargins();});
                }
            });
        });
    
    //ustawianie zdjęcia profilowego
    $(document).on('click','#galleryItemSetAsMain',function(){
        var $this = $(this),
            id_photos = $this.closest('.galleryWrapper').attr('data-id'),
            id_galleries = $this.closest('#galleryItems').attr('data-id');
            
        $.ajax({url: base_url+'galerie/ustaw_zdjecie_profilowe',
            method: 'POST',
            data: {id_photos:id_photos,id_galleries:id_galleries},
            success: function(){
                $this.addClass('galleryItemSave');
            }
        });
    });
    

    /*******************************************************************************/

    $("#slides tbody").sortable({
        helper: fixHelper,
        cursor: "move",
        update: function(e, ui){

        var dataStr = $('#slides').serializeSortable('.sortElement','data-slide-id','a_slides');

            $.ajax({url: base_url+'panel/zapisz_pozycje_slidow',
                    method: 'POST',
                    data: dataStr
            });
        }
    }).disableSelection();

    $(document).on('change','input.zmien_widocznosc_slidu',function(){
        $.ajax({url: base_url+'panel/zapisz_widocznosc_slidow',
                method: 'POST',
                data: {slide_id:$(this).closest('tr').attr('data-slide-id'),val: $(this).prop('checked')}
          });
    });
    
    $('#slides').on('click','a.czyUsunacSlide',function(e){
        e.preventDefault();
        var content = "<p class='communicat_question'>Czy chcesz usunąć tego slide'a?</p>";
        content+='<button class="usunSlide modalClose">Tak</button><button class="modalClose">Nie</button>';
        $('body').modal({content: content,height: 0});
        var tr=$(this).closest('tr');
        var slide_id=tr.attr('data-slide-id');
        
        $('.usunSlide').click(function(){
            $.ajax({url: base_url+'panel/usun_slide',
                method: 'POST',
                data: {slide_id:slide_id},
                success: function(){
                    tr.remove();
                }
            });
        });
        
    });
    
    $('#rezerwacje').on('click','a.czy_usunac_rezerwacje',function(e){
        e.preventDefault();
        var content = "<p class='communicat_question'>Czy chcesz usunąć tą rezerwację?</p>";
        content+='<button class="usunRezerwacje modalClose">Tak</button><button class="modalClose">Nie</button>';
        $('body').modal({content: content,height: 0});
        var tr=$(this).closest('tr');
        var id=$(this).attr('data-id');
        
        $('.usunRezerwacje').click(function(){
            $.ajax({url: base_url+'rezerwacja/usun_rezerwacje',
                method: 'POST',
                data: {id:id},
                success: function(){
                    tr.remove();
                }
            });
        });
        
    });
    
    $("#langs tbody").sortable({
        helper: fixHelper,
        cursor: "move",
        update: function(e, ui){

        var dataStr = $('#langs').serializeSortable('.sortElement','data-lang-id','a_langs');

            $.ajax({url: base_url+'panel/zapisz_pozycje_jezykow',
                    method: 'POST',
                    data: dataStr
            });
        }
    }).disableSelection();
    
    $('table.langs').on('click','a.czyUsunacJezyk',function(e){
        e.preventDefault();
        var tr=$(this).closest('tr');
        var lang_id=tr.attr('data-lang-id');
        
        if(lang_id==1)
            var content = "<p class='communicat_error'>Tego języka nie możesz usunąć</p>";
        else
        {
            var content = "<p class='communicat_question'>Czy chcesz usunąć ten język i jego wszystkie tłumaczenia?</p>";
            content+='<button class="usunJezyk modalClose">Tak</button><button class="modalClose">Nie</button>';
        }
        $('body').modal({content: content,height: 0});
        
        
        $('.usunJezyk').click(function(){
            $.ajax({url: base_url+'panel/usun_jezyk',
                method: 'POST',
                data: {lang_id:lang_id},
                success: function(){
                    tr.remove();
                    location.reload();
                }
            });
        });
    });
    
    $(".menus tbody").sortable({
        helper: fixHelper,
        cursor: "move",
        update: function(e, ui){

        var dataStr = $(this).serializeSortable('.sortElement','data-menu-id','a_menu');

            $.ajax({url: base_url+'panel/zapisz_pozycje_menu',
                    method: 'POST',
                    data: dataStr
            });
        }
    }).disableSelection();
    
    $(document).on('change','input.zmien_widocznosc_menu',function(){
        $.ajax({url: base_url+'panel/zapisz_widocznosc_menu',
                method: 'POST',
                data: {menu_id:$(this).closest('tr').attr('data-menu-id'),val: $(this).prop('checked')}
          });
    });
    
    $('table.menus').on('click','a.czyUsunacMenu',function(e){
        e.preventDefault();
        var id_menu=$(this).closest('tr').attr('data-menu-id');
        if(1==1)
        {
            var content = "<p class='communicat_question'>Czy chcesz usunąć tą pozycję w menu?</p>";
            content+='<button class="usunMenu modalClose">Tak</button><button class="modalClose">Nie</button>';
            $('body').modal({content: content,height: 0});
            var tr=$(this).closest('tr');
            var menu_id=tr.attr('data-menu-id');
            
            $('.usunMenu').click(function(){
                $.ajax({url: base_url+'panel/usun_menu',
                    method: 'POST',
                    data: {menu_id:menu_id},
                    success: function(){
                        tr.remove();
                    }
                });
            });
        }
        else
            $('body').modal({closeButton: true,content: '<p class="communicat_error">Tej pozycji nie możesz usunąć, gdyż ma istotny wpływ na działanie całego systemu',height: 0});
    });
    
    $('#wybierz_menu').change(function(){
        var wybierz_podmenu=$('#wybierz_podmenu');
        wybierz_podmenu.find('option:not(:first)').remove()
        
        $.ajax({url: base_url+'panel/get_submenus_of_menu/'+$(this).val(),
                dataType: 'json',
                success: function(a_dane){
                    var html='';
                    $.each(a_dane,function(index,element){
                        html+='<option value="'+element['id_menu']+'">'+element['nazwa']+'</option>';
                    });
                    wybierz_podmenu.append(html);
                }
              });
        
        //$('#wybierz_podmenu option[data-rodzic="'+$(this).val()+'"]').show();
    });
    
    $(".sites tbody").sortable({
        helper: fixHelper,
        cursor: "move",
        update: function(e, ui){

        var dataStr = $('.sites').serializeSortable('.sortElement','data-site-id','a_site');

            $.ajax({url: base_url+'strony/zapisz_pozycje_stron',
                    method: 'POST',
                    data: dataStr
            });
        }
    }).disableSelection();
    
    $('table.sites').on('click','a.czyUsunacStrone',function(e){
        e.preventDefault();
        var content = "<p class='communicat_question'>Czy chcesz usunąć tą stronę?</p>";
        content+='<button class="usunStrone modalClose">Tak</button><button class="modalClose">Nie</button>';
        $('body').modal({content: content,height: 0});
        var tr=$(this).closest('tr');
        var site_id=tr.attr('data-site-id');
        
        $('.usunStrone').click(function(){
            $.ajax({url: base_url+'strony/usun_strone',
                method: 'POST',
                data: {site_id:site_id},
                success: function(){
                    tr.remove();
                }
            });
        });
    });
    
    $(document).on('change','input.zmien_widocznosc_strony',function(){
        $.ajax({url: base_url+'strony/zapisz_widocznosc_stron',
                method: 'POST',
                data: {site_id:$(this).closest('tr').attr('data-site-id'),val: $(this).prop('checked')}
          });
    });
    
    $('#placowki').on('click','a.czy_usunac_placowke',function(e){
        e.preventDefault();
        var content = "<p class='communicat_question'>Czy chcesz usunąć tą placówkę?</p>";
        content+='<button class="usunPlacowke modalClose">Tak</button><button class="modalClose">Nie</button>';
        $('body').modal({content: content,height: 0});
        var tr=$(this).closest('tr');
        var id=tr.attr('data-id');
        
        $('.usunPlacowke').click(function(){
            $.ajax({url: base_url+'placowki/usun_placowke',
                method: 'POST',
                data: {id:id},
                success: function(){
                    tr.remove();
                }
            });
        });
        
    });

    $.datepicker.setDefaults($.datepicker.regional['pl']);
    $( ".datePicker" ).datepicker({dateFormat: 'yy-mm-dd'});
    
    $(".article_categories tbody").sortable({
        helper: fixHelper,
        cursor: "move",
        update: function(e, ui){

        var dataStr = $('.article_categories').serializeSortable('.sortElement','data-category-id','a_kategoria');

            $.ajax({url: base_url+'panel/zapisz_pozycje_kategorii_artykulow',
                    method: 'POST',
                    data: dataStr
            });
        }
    }).disableSelection();

    $(document).on('change','input.zmien_widocznosc_kategorii_artykulu',function(){
        $.ajax({url: base_url+'panel/zapisz_widocznosc_kategorii_artykulow',
                method: 'POST',
                data: {id_kategorii:$(this).closest('tr').attr('data-category-id'),val: $(this).prop('checked')}
          });
    });
    
    $('.article_categories').on('click','a.czyUsunacKategorieArtykulu',function(e){
        e.preventDefault();
        var content = "<p class='communicat_question'>Czy chcesz usunąć tą kategorię artykułu?</p>";
        content+='<button class="usunKategorieArtykulu modalClose">Tak</button><button class="modalClose">Nie</button>';
        $('body').modal({content: content,height: 0});
        var tr=$(this).closest('tr');
        var id_kategorii=tr.attr('data-category-id');
        
        $('.usunKategorieArtykulu').click(function(){
            $.ajax({url: base_url+'panel/usun_kategorie_artykulu',
                method: 'POST',
                data: {id_kategorii:id_kategorii},
                success: function(){
                    tr.remove();
                }
            });
        });
        
    });
    
    /*********************************/
   
   $(".dishes_categories tbody").sortable({
        helper: fixHelper,
        cursor: "move",
        update: function(e, ui){

        var dataStr = $('.dishes_categories').serializeSortable('.sortElement','data-category-id','a_kategoria');

            $.ajax({url: base_url+'panel/zapisz_pozycje_kategorii_dan',
                    method: 'POST',
                    data: dataStr
            });
        }
    }).disableSelection();

    $(document).on('change','input.zmien_widocznosc_kategorii_dan',function(){
        $.ajax({url: base_url+'panel/zapisz_widocznosc_kategorii_dan',
                method: 'POST',
                data: {id_kategorii:$(this).closest('tr').attr('data-category-id'),val: $(this).prop('checked')}
          });
    });
    
    $('.dishes_categories').on('click','a.czyUsunacKategorieDan',function(e){
        e.preventDefault();
        var id_category=$(this).closest('tr').attr('data-category-id');
        if(id_category!=16 && id_category!=17)
        {     
            var content = "<p class='communicat_question'>Czy chcesz usunąć tą kategorię z karty dań?</p>";
            content+='<button class="usunKategorieDan modalClose">Tak</button><button class="modalClose">Nie</button>';
            $('body').modal({content: content,height: 0});
            var tr=$(this).closest('tr');
            var id_kategorii=tr.attr('data-category-id');
            
            $('.usunKategorieDan').click(function(){
                $.ajax({url: base_url+'panel/usun_kategorie_dan',
                    method: 'POST',
                    data: {id_kategorii:id_kategorii},
                    success: function(){
                        tr.remove();
                    }
                });
            });
        }
        else
            $('body').modal({closeButton: true,content: '<p class="communicat_error">Tej pozycji nie możesz usunąć, gdyż ma istotny wpływ na działanie całego systemu',height: 0});
        
    });
    
    
    /***************************/
   
   $(".dishes tbody").sortable({
        helper: fixHelper,
        cursor: "move",
        update: function(e, ui){

        var dataStr = $('.dishes').serializeSortable('.sortElement','data-dish-id','a_dania');

            $.ajax({url: base_url+'panel/zapisz_pozycje_dan',
                    method: 'POST',
                    data: dataStr
            });
        }
    }).disableSelection();

    $(document).on('change','input.zmien_widocznosc_dania',function(){
        $.ajax({url: base_url+'panel/zapisz_widocznosc_dania',
                method: 'POST',
                data: {id_dania:$(this).closest('tr').attr('data-dish-id'),val: $(this).prop('checked')}
          });
    });
    
    $('.dishes').on('click','a.czyUsunacDanie',function(e){
        e.preventDefault();
        var content = "<p class='communicat_question'>Czy chcesz usunąć to danie z karty?</p>";
        content+='<button class="usunDanie modalClose">Tak</button><button class="modalClose">Nie</button>';
        $('body').modal({content: content,height: 0});
        var tr=$(this).closest('tr');
        var id_dania=tr.attr('data-dish-id');
        
        $('.usunDanie').click(function(){
            $.ajax({url: base_url+'panel/usun_danie',
                method: 'POST',
                data: {id_dania:id_dania},
                success: function(){
                    tr.remove();
                }
            });
        });
        
    });
    
    /***************************adsy*************************/
   
   $("#ads tbody").sortable({
        helper: fixHelper,
        cursor: "move",
        update: function(e, ui){

        var dataStr = $('#ads').serializeSortable('.sortElement','data-ad-id','a_ads');

            $.ajax({url: base_url+'panel/zapisz_pozycje_reklam',
                    method: 'POST',
                    data: dataStr
            });
        }
    }).disableSelection();

    $(document).on('change','input.zmien_widocznosc_reklamy',function(){
        $.ajax({url: base_url+'panel/zapisz_widocznosc_reklamy',
                method: 'POST',
                data: {ad_id:$(this).closest('tr').attr('data-ad-id'),val: $(this).prop('checked')}
          });
    });
    
    $('#ads').on('click','a.czyUsunacReklame',function(e){
        e.preventDefault();
        var content = "<p class='communicat_question'>Czy chcesz usunąć tą reklamę'a?</p>";
        content+='<button class="usunReklame modalClose">Tak</button><button class="modalClose">Nie</button>';
        $('body').modal({content: content,height: 0});
        var tr=$(this).closest('tr');
        var ad_id=tr.attr('data-ad-id');
        
        $('.usunReklame').click(function(){
            $.ajax({url: base_url+'panel/usun_reklame',
                method: 'POST',
                data: {ad_id:ad_id},
                success: function(){
                    tr.remove();
                }
            });
        });
        
    });
   
   /************************end adsy****************************/
    
    if($('#rodzaj_menu').length && $('#rodzaj_menu').val()!=' ')
    {
        var rodzaj_menu = $('#rodzaj_menu_'+$('#rodzaj_menu').val());
        var content = rodzaj_menu.clone();
        rodzaj_menu.remove();
        $('#rodzaj_menu_content').html(content.show());
    }
    
        $('#rodzaj_menu_'+$('#rodzaj_menu').val()).show();
    
    $('#rodzaj_menu').change(function(){
        var val = $(this).val(),
            rodzaj_menu_content = $('#rodzaj_menu_content'),
            rodzaj_menu_site = $('#rodzaj_menu_site'),
            rodzaj_menu_sites_by_category = $('#rodzaj_menu_sites_by_category'),
            rodzaj_menu_gallery = $('#rodzaj_menu_gallery'),
            rodzaj_menu_link = $('#rodzaj_menu_link');
        
        var zawartosc = rodzaj_menu_content.children().clone();
        rodzaj_menu_content.children().remove();
        rodzaj_menu_content.closest('form').after(zawartosc).nextAll().hide();
            
        if(val=='site')
        {
            var content = rodzaj_menu_site.clone();
            rodzaj_menu_site.remove();
            rodzaj_menu_content.html(rodzaj_menu_site.show());
        }
        else if(val=='sites_by_category')
        {
            var content = rodzaj_menu_sites_by_category.clone();
            rodzaj_menu_sites_by_category.remove();
            rodzaj_menu_content.html(rodzaj_menu_sites_by_category.show());
        }
        else if(val=='gallery')
        {
            var content = rodzaj_menu_gallery.clone();
            rodzaj_menu_gallery.remove();
            rodzaj_menu_content.html(rodzaj_menu_gallery.show());
        }
        else if(val=='link')
        {
            var content = rodzaj_menu_link.clone();
            rodzaj_menu_link.remove();
            rodzaj_menu_content.html(rodzaj_menu_link.show());
        }
    });
    
    $('#dish_category').change(function(){
        if($(this).val()==17)
            $('#hiddenPriceSecond').show();
        else
            $('#hiddenPriceSecond').hide();
    });
    
    /*********************mailing*******************/
   
    $('#szablon_mailingu').change(function(){
        $.get(base_url+'strony/get_site_by_id/id/'+$(this).val(),function(dane){
            CKEDITOR.instances.tresc.insertHtml(dane.text);
            $('#tytul').val(dane.title);
        },'json')
    });
    
    $('#wyslij_testowy_mailing').click(function(){
        
        $.ajax({url: base_url+'mailing/wyslij_testowy',
                data: {tytul: $('#tytul').val(), tresc: CKEDITOR.instances.tresc.getData()},
                type: 'post',
                success: function(){
                    alert('Wysłano testowy email');
                }
        });
        
    });
    
    //usuwanie mailingu
    $(document).on('click','.usun_mailing',function(){
        var content = "<p class='communicat_question'>Czy chcesz usunąć ten mailing?</p>";
        
        content+='<button class="usunMailing modalClose" data-id="'+$(this).attr('data-id')+'">Tak</button><button class="modalClose">Nie</button>';
        $('body').modal({content: content,height: 0});

    });
    
    $(document).on('click','.usunMailing',function(){
            var $this = $(this),
                id = $this.attr('data-id');
            $.ajax({url: base_url+'mailing/usun_mailing',
                method: 'POST',
                data: {id_mailing:id},
                success: function(){
                     $('tr[data-id="'+id+'"]').fadeRemove();
                }
            });
        });
        
   $('.contestCommentTxtArea').blur(function(){
       var contest_id = $(this).closest('tr').find('td').eq(1).html(),
           comment = $(this).val();
       $.post(base_url+'konkursy/save_contest_comment',{contest_id: contest_id, comment: comment});
       $(this).css('height','30px');
   }).focus(function(){
       $(this).css('height','200px');
   });
    
});