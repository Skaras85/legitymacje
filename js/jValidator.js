/********************************jValidator.js ver. 3.1 by Łukasz Karaś***********************/

/*
 * changelog:
 * - przyspieszone działanie
 * - usunięte bugi
 * - usunięty zbędny kod
 * 
 */

/*autoTrigger:
    - 'submit' - walidacja dokonuje się tylko po kliknięciu submita
    - true (domyślne) - walidacja dokonuje się przy submicie, wpisywaniu, opuszczaniu pola itd.
*/
/*
$.fn.rob = function(options){
    
    var defaults = {text: 'pies',
                    text2: 'szczurek'};
    var options = $.extend({},defaults,options);
    options = $.extend({},options,$.fn.rob.defaults);
    
     return this.each(function(index,element){
          $(element).html(options.text);
     });
}
$.fn.rob.defaults = {text: 'kot'}
*/
    $.fn.jValidate = function(options,callback)
    {
        var defaults = {
            autoTrigger: true,
            txts: {
                jrequired: 'To pole jest wymagane ',
                jchecked: 'Musisz zaznaczyć to pole',
                jcheckmany: 'Musisz zaznaczyć wszystkie pola',
                jid: 'Nieprawidłowa liczba, powinna być całkowita i dodatnia',
                jnumber: 'Nieprawidłowa liczba',
                jprice: 'Nieprawidłowa cena',
                jalfanum: 'Niedozwolone znaki. Dozwolone są litery, liczby, spacja, podkreślnik, myślnik, przecinek i kropka',
                jalfanumstrict: 'Niedozwolone znaki. Dozwolone są litery, liczby, podkreślnik, myślnik i kropka',
                jalfanumhc: 'Niedozwolone znaki. Dozwolone są litery i liczby',
                jletters: 'Niedozwolone znaki. Dozwolone są litery i spacja',
                jemailkeyup: 'Nieprawidłowy adres email',
                jemailblur: 'Nieprawidłowy adres email',
                jpostal: 'Nieprawidłowy kod pocztowy',
                jminlength: 'Minimalna ilość znaków: ',
                jextension: 'Nieprawidłowy typ pliku. Dopuszczalne: ',
                jdata: 'Nieprawidłowa data',
                jpesel: 'Nieprawidłowy pesel',
            }
        };
        
        var options = $.extend({},defaults,options);
        options = $.extend({},options,$.fn.jValidate.defaults);
        
        return this.each(function(index,element){
            
        var self = $(element),
            submit = self.find('input[type="submit"], .submitButton');
        self.communicat = false;
        
        self.getMsg = function(type,input)
        {
            return input.is('[data-'+type+'-txt]') ? input.attr('data-'+type+'-txt') : false;
        }
        
        self.formError = function(input)
        {
            if( !input.next().is('.err_container') )
            {
                input.addClass('err_input')
                     .removeClass('success_input')
                     .after('<div class="err_container" style="display: none;position:absolute;"><span class="err_msg"><nobr>'+self.communicat+'</nobr></span></div>')
                        .next().css('top',input.position().top);
                        
                        if(input.is('[type="checkbox"]'))
                        {
                            var label = input.attr('id');
                            input.next().css('left',input.position().left+input.outerWidth()+$('label[for="'+label+'"]').width()+10);
                        }
                        else
                            input.next().css('left',input.position().left+input.outerWidth()+20);
                        
                        input.next().show();           
            }
            else
                input.addClass('err_input').removeClass('success_input').next().find('nobr').html(self.communicat);
            
            return true;
        }
    
        self.formRemoveError = function(input)
        {
            input.removeClass('err_input').addClass('success_input');
            self.communicat = false;
             
            if( input.next().is('.err_container') )
                input.next().fadeOut(200, function(){ $(this).remove() });
        }

        self.triggerSubmit = function()
        {
          // var submit = self.find('input[type="submit"]');
               
           submit.click(function(e){

               var errors = 0;
               
               $(submit).closest('.jValidate').find('input[type="text"],input[type="checkbox"],input[type="password"],input[type="file"],textarea,select').each(function(index,element){
                    
                    if($(element).is(':not(:disabled)') && !self.checkControllsValid($(element),''))
                    {
                        self.formError($(element));
                        errors++;
                    }
                    else
                    {
                        self.formRemoveError($(element));
                    }
               })

               if(errors)
               {
                   e.preventDefault();
                   submit.data('error',true);
                   $("html, body").animate({ scrollTop: $('.err_input:first').position().top-20}, 200);
               }
               else
               {
                    submit.data('error',false);
                    return true;
               }
           });
        }
        
        self.triggerValidation = function()
        {
            self.find('input[type="text"],input[type="password"],textarea').blur(function(){
                 if($(element).is(':not(:disabled)') && !self.checkControllsValid($(this),'blur'))
                    self.formError($(this));
                 else
                    self.formRemoveError($(this));
            }).keyup(function(){
                 var $this = $(this);
                 if($this.val()!='')
                 {
                    if(!self.checkControllsValid($this,'keyup'))
                        self.formError($this);
                    else
                        self.formRemoveError($this);
                    
                    var sludge = $this.nextAll('.sludge').first();
                    
                    if($this.hasClass('sludgeSource'))
                    {
                        if(!self.checkControllsValid(sludge,''))
                            self.formError($this);
                        else
                            self.formRemoveError($this);
                    }
                 }
            });
            
            self.find('input[type="text"],input[type="file"],input[type="checkbox"],select').change(function(){
                 var $this = $(this);
                 if($this.is(':not(:disabled)') && !self.checkControllsValid($this,''))
                    self.formError($this);
                 else
                    self.formRemoveError($this);
            });
        
            self.find('input[type="checkbox"]').on('ifChecked ifUnchecked', function(){
                var $this = $(this);
                if($this.is(':not(:disabled)') && !self.checkControllsValid($this,''))
                    self.formError($this);
                else
                    self.formRemoveError($this);
            });
        }

        self.checkControllsValid = function(input,funkcja)
        {
            var val=input.val();
            
             if( input.hasClass('jID') && !self.jID(input))
                return false;
                
             if( input.hasClass('jNumber') && !self.jNumber(input))
                return false;
             
             if( input.hasClass('jPrice') && !self.jPrice(input))
                return false;
                
             if( input.hasClass('jRequired') && !self.jRequired(input))
                return false;
                
             if( input.hasClass('jAlfanum') && !self.jAlfanum(input))
                return false;
                
             if( input.hasClass('jAlfanumStrict') && !self.jAlfanumStrict(input))
                return false;
                
             if( input.hasClass('jAlfanumHC') && !self.jAlfanumHC(val))
                return false;
                
            if( input.hasClass('jPesel') && !self.jPesel(input))
                return false;
                 
             if( input.hasClass('jLetters') && !self.jLetters(val))
                return false;
                
             if( input.hasClass('jEMail') && input.val()!='' && (funkcja=='keyup' && !self.jEMailKeyUp(input) || (funkcja=='blur' && !self.jEMailBlur(input))))
                return false;
                
             if( input.hasClass('jPostal') && funkcja=='blur' && !self.jPostal(input))
                return false;
                
             if( (input.hasClass('jMinLength') && !self.jMinLength(input,input.attr('data-min-length'))) && ((funkcja=='blur' || funkcja=='') || input.next().is('.err_container') ) )
                return false;
                
             if( (input.hasClass('jMaxLength') && !self.jMaxLength(input,input.attr('data-max-length'))) && ((funkcja=='blur' || funkcja=='') || input.next().is('.err_container') ) )
                return false;
             
             if( (input.hasClass('jMinVal') && !self.jMinVal(input,input.attr('data-min-val'))) && ((funkcja=='blur' || funkcja=='') || input.next().is('.err_container') ) )
                return false;
                 
             if( (input.hasClass('jMaxVal') && !self.jMaxVal(input,input.attr('data-max-val'))) && ((funkcja=='blur' || funkcja=='') || input.next().is('.err_container') ) )
                return false;
                
             if( (input.hasClass('jBetween') && !self.jBetween(input,input.attr('data-min-length'),input.attr('data-max-length'))) && ((funkcja=='blur' || funkcja=='') || input.next().is('.err_container') ) )
                return false;
                
             if( input.hasClass('jNrKontaBankowego') &&  !self.jBankAccountNumber(val))
                return false;
                
             if( input.hasClass('jDate') && (funkcja=='blur' && !self.jDate(input)) )
                return false;
                
             if( input.hasClass('jNIP') && !self.jNIP(val))
                return false;
                
             if( input.hasClass('jAjax') && funkcja=='blur' && !self.jAjax(input))
                return false;
                
             if( input.hasClass('jRegon') && !self.jRegon(val))
                return false;
                
             if( input.hasClass('jExtension') && !self.jExtension(input,input.attr('data-extensions')))
                return false;
    
             if( input.hasClass('jChecked') && !self.jChecked(input) )
                return false;
                
             if( input.hasClass('jCheckMany') && !self.jCheckMany(input) )
                return false;
                
             if( input.hasClass('jEqual') && !self.jEqual(input,input.attr('data-equal-to')))
                return false;
             
             if( input.hasClass('jNumberMultiple') &&  !self.jNumberMultiple(val,input.data('number-multiple')))
                return false;
             
             return true;    
        }
        
        /*-------------------------------validatory---------------------------------*/
    
        self.jChecked = function(input)
        {
            if( !input.is(':checked') )
            {
                self.communicat = self.getMsg('jchecked',input) || options.txts.jchecked;
                return false;
            }   
            else
                return true;
        }
        
       self.jCheckMany = function(input)
        {
            var target = input.data('jcheckmany-target');
            
            if( input.is(':checked') )
            {
                var err = 0;
                $(target).each(function(e,i){
                    if(!$(i).is(':checked'))
                    {
                        self.communicat = self.getMsg('jcheckmany',input)
                        self.formError($(i));
                        err++;
                    }
                    else
                        self.formRemoveError($(i));
                });
                
                if(err)
                {
                    self.communicat = self.getMsg('jcheckmany',input) || options.txts.jcheckmany;
                    return false;
                }
                else
                    return true;   
            }
            else
                return true;
        }
    
        self.jRequired = function(input)
        {
            if( input.val()=='' )
            {
                self.communicat = self.getMsg('jrequired',input) || options.txts.jrequired;
                return false;
            }
            else
                return true;
        }
        
        self.jID = function(input)
        {
        	var val = input.val();
            if( val.match(/[^0-9]/) || val<1 )
            {
                self.communicat = self.getMsg('jid',input) || options.txts.jid;
                return false;
            }
            else
                return true;
        }
        
        self.jNumber = function(input)
        {
            var val = input.val();
            if( val.match(/[^0-9]/) )
            {
                self.communicat = self.getMsg('jnumber',input) || options.txts.jnumber;
                return false;
            }
            else
                return true;
        }
        
        self.jPrice = function(input)
        {
        	var val = input.val();
            if(val!='')
            {
                if( val.match(/^[0-9]+[\.\,]?[0-9]{0,2}$/) )
                    return true;
                else
                {
                    self.communicat = self.getMsg('jprice',input) || options.txts.jprice;
                    return false;
                }
            }
            else
                return true;
        }
        
        self.jAlfanum = function(input)
        {
            if( input.val().match(/[^a-zA-ZąężźćśłóńŻŹĆŚŁÓĘĄŃ0-9\s\.\_,\-]/) )
            {
                self.communicat = self.getMsg('jalfanum',input) || options.txts.jalfanum;
                return false;
            }  
            else
                return true;
        }
    
        self.jAlfanumStrict = function(input)
        {
            if( input.val().match(/[^a-zA-ZąężźćśłóńŻŹĆŚŁÓĘĄŃ0-9\.\_\-]/) )
            {
                self.communicat = self.getMsg('jalfanumstrict',input) || options.txts.jalfanumstrict;
                return false;
            }   
            else
                return true;
        }
        
        self.jAlfanumHC = function(val)
        {
            if( val.match(/[^a-zA-ZąężźćśłóńŻŹĆŚŁÓĘĄ0-9]/) )
            {
                self.communicat = self.getMsg('jalfanumhc',input) || options.txts.jalfanumhc;
                return false;
            } 
            else
                return true;
        }
        
        self.jLetters = function(val)
        {
            if( val.match(/[^a-zA-ZąężźćśłóńŻŹĆŚŁÓĘĄ\s]/) )
            {
                self.communicat = self.getMsg('jletters',input) || options.txts.jletters;
                return false;
            } 
            else
                return true;
        }
        
        self.jEMailKeyUp = function(input)
        {
            var result = false,
                val = input.val();
            
            if( val.match(/^[a-zA-Z0-9_\.\-]+$/) )
                result=true;
            else if( val.match(/^[a-zA-Z0-9_\.\-]+\@$/) )
                result=true;
            else if( val.match(/^[a-zA-Z0-9_\.\-]+\@[a-zA-Z0-9\-\.]+$/) )
                result=true;
            else if( val.match(/^[a-zA-Z0-9_\.\-]+\@[a-zA-Z0-9\-\.]+[a-zA-Z0-9]{2,4}$/) )
                result=true;
            
            if(result)
                return true;
            else
            {
                self.communicat = self.getMsg('jemailkeyup',input) || options.txts.jemailkeyup;
                return false;
            }
        }
        
        self.jEMailBlur = function(input)
        {
            if( input.val().match(/^[a-zA-Z0-9_\.\-]+\@[a-zA-Z0-9\-\.]+\.[a-zA-Z0-9]{2,4}$/) )
                return true;
            else
            {
                self.communicat = self.getMsg('jemailblur',input) || options.txts.jemailblur;
                return false;
            }
                
        }
        
        self.jPesel = function(input)
        {
        	var reg = /^[0-9]{11}$/,
        		pesel = input.val(),
        		result;
		    if(reg.test(pesel) == false) 
		        result = false;
		    else
		    {
		        var digits = (""+pesel).split("");
		        if ((parseInt(pesel.substring( 4, 6)) > 31)||(parseInt(pesel.substring( 2, 4)) > 12))
		            result = false;
		         
		        var checksum = (1*parseInt(digits[0]) + 3*parseInt(digits[1]) + 7*parseInt(digits[2]) + 9*parseInt(digits[3]) + 1*parseInt(digits[4]) + 3*parseInt(digits[5]) + 7*parseInt(digits[6]) + 9*parseInt(digits[7]) + 1*parseInt(digits[8]) + 3*parseInt(digits[9]))%10;
		        if(checksum==0) checksum = 10;
		            checksum = 10 - checksum;
		 
		        result = (parseInt(digits[10])==checksum);
		    }
		    
		     if(!result)
		     	self.communicat = self.getMsg('jpesel',input) || options.txts.jpesel;
		     	
	     	return result;
        }
        
        self.jPostal = function(input)
        {
            if( !input.val().match(/^\d\d-\d\d\d$/) )
            {
                self.communicat = self.getMsg('jpostal',input) || options.txts.jpostal;
                return false;
            } 
            else
                return true;
        }
        /*
        self.jAjax = function(input)
        {
        	$.ajax({url: input.data('ajax-url')+'/val/'+input.val(),
                method: 'GET',
				async: false,
				dataType: 'json',
                success: function(result){
                    if(!result['result'])
	        		{
	        			self.communicat = result['comm'];
	               		return false;
	        		}
	        		return true;
                }
            });
        }
        */
        self.jMinLength = function(input,minLength)
        {
            if(input.val()!='' && input.val().length<minLength)
            {
                self.communicat = self.getMsg('jminlength',input) || options.txts.jminlength + minLength;
                return false;
            }
            else
                return true;
        }
        
        self.jMaxLength = function(input,maxLength)
        {
            if(input.val().length>maxLength)
            {
                self.communicat = "To pole musi zawierać max. "+input.attr('data-max-length')+" znaków";
                return false;
            } 
            else
                return true;
        }
        
        self.jMinVal = function(input,minVal)
        {
            if(parseFloat(input.val())<parseFloat(minVal))
            {
                if(input.attr('data-min-val-txt'))
                    self.communicat = input.attr('data-min-val-txt');
                else
                    self.communicat = "Liczba nie może być mniejsza niż. "+input.attr('data-min-val');
                return false;
            } 
            else
                return true;
        }
        
        self.jMaxVal = function(input,maxVal)
        {
            if(parseFloat(input.val())>parseFloat(maxVal))
            {
                self.communicat = "Liczba nie może być większa niż. "+input.attr('data-max-val');
                return false;
            } 
            else
                return true;
        }
        
        self.jBetween = function(input,minLength,maxLength)
        {
            if(input.val().length<minLength || input.val().length>maxLength)
            {
                self.communicat = "To pole musi zawierać "+input.attr('data-min-length')+" do "+input.attr('data-max-length')+" znaków";
                return false;
            }               
            else
                return true;
        }
        
        self.jBankAccountNumber = function(val)
        {
            // Usuniecie spacji
            var iNRB = val.replace(/\s/g, '');
        
            // Sprawdzenie czy przekazany numer zawiera 26 znaków
            if(iNRB.length != 26)
            {
                self.communicat = "Numer konta bankowego powinien zawierać 26 znaków";
                return false;
            }
       
                // Zdefiniowanie tablicy z wagami poszczególnych cyfr                
            var aWagiCyfr = [1, 10, 3, 30, 9, 90, 27, 76, 81, 34, 49, 5, 50, 15, 53,
                            45, 62, 38, 89, 17, 73, 51, 25, 56, 75, 71, 31, 19, 93, 57];
        
            // Dodanie kodu kraju (w tym przypadku dodajemu kod PL)        
            iNRB = iNRB+'2521';
            //102010130000030201957521 2521 96
            iNRB = iNRB.substr(2)+iNRB.substr(0, 2); 
    
            // Wyzerowanie zmiennej
            iSumaCyfr = 0;
        
            // Pętla obliczająca sumę cyfr w numerze konta
            for(var i = 0; i < 30; i++) 
                iSumaCyfr += iNRB[29-i] * aWagiCyfr[i];
        
            // Sprawdzenie czy modulo z sumy wag poszczegolnych cyfr jest rowne 1
            if (iSumaCyfr % 97 !== 1)
            {
                self.communicat = "Numer konta bankowego jest nieprawidłowy";
                return false;
            }
    
            return true;
        }
        
        self.checkDate = function(m, d, y)
        {
            return m > 0 && m < 13 && y > 0 && y < 32768 && d > 0 && d <= (new Date(y, m, 0)).getDate();
        }
        
        self.jDate = function(input)
        {
            var val = input.val(),
            	saved=val.match(/^(\d\d\d\d)-(\d\d)-(\d\d)$/);
    
            if(!val.match(/^(\d\d\d\d)-(\d\d)-(\d\d)$/))
            {
                self.communicat = 'Data powinna być w formacie: rrrr-mm-dd';
                return false;
            }
    
            if (!self.checkDate(saved[2],saved[3],saved[1]))
            {
                self.communicat = 'Podano nieprawidłową datę';
                return false;
            }
                 
            return true;
        }
    
        self.jNIP = function(val)
        {
        	if(val.length==0) return true;
            var weights = [6, 5, 7, 2, 3, 4, 5, 6, 7];
            val = val.replace(/[\s-]/g, '');
            self.communicat = 'Nieprawidłowy NIP';
            if (val.length == 10 && parseInt(val, 10) > 0) {
                var sum = 0;
                for(var i = 0; i < 9; i++){
                    sum += val[i] * weights[i];
                }
                return (sum % 11) == val[9];
            }
            
            return false;
        }
        
        self.jRegon = function(val)
        {
             var reg = /^[0-9]{9}$/;
		     if(!reg.test(val))
			 {
			 	self.communicat = "Nieprawidłowy regon";
		        return false;
		     }
		    else
		    {
		        var digits = (""+val).split("");
		        var checksum = (8*parseInt(digits[0]) + 9*parseInt(digits[1]) + 2*parseInt(digits[2]) + 3*parseInt(digits[3]) + 4*parseInt(digits[4]) + 5*parseInt(digits[5]) + 6*parseInt(digits[6]) + 7*parseInt(digits[7]))%11;
		        if(checksum == 10) 
		            checksum = 0;
		        self.communicat = "Nieprawidłowy regon";
		        return (parseInt(digits[8])==checksum);
		    }
        }
        /*
        self.jExtension = function(input)
        {
            var isMultiple = $(input).is('[multiple]'),
                extensions = $(input).attr('data-extensions'),
                a_extensions = extensions.split(' '),
                id = $(input).attr('id'),
                err = 0;
               
            if(isMultiple)  
            {
                var filelist = document.getElementById(id).files;
                for (var i = 0; i < filelist.length; i++)
                {
                    $.each(a_extensions, function(index, ext){
                        var length = ext.length,
                            file_ext = filelist[i].name.substr(-length,length);
                        
                        if(file_ext.toLowerCase()==ext)
                            err++;
                    });
                }
            }
            else
            {
                filename = $(input).val();
                
                if(filename=='')
                    return true;
                    
                $.each(a_extensions, function(index, ext){
                    var length = ext.length,
                        file_ext = filename.substr(-length,length);
                    
                    if(file_ext.toLowerCase()!=ext)
                        err=0;
                });    
            }

            if(err==1)
            {
                self.communicat = 'Nieprawidłowy typ pliku. Dopuszczalne '+extensions;
                return false;
            }
            else
                return true;
        }*/
        
        self.jExtension = function(input,extensions)
        {
            var filename = input.val();
            if(filename=='')
                return true;
            
            var a_extensions=extensions.split(' ');
    
            var err=1;
            $.each(a_extensions, function(index, ext){
                var length = ext.length;
                
                var file_ext = filename.substr(-length,length);
                
                if(file_ext.toLowerCase()==ext)
                    err=0;
            })
    
            if(err==1)
            {
                self.communicat = self.getMsg('jextension',input) || options.txts.jextension+extensions;
                return false;
            }
            else
                return true;
        }
        
        self.jEqual = function(input,equalTo)
        {
            var val = input.val();
            var val2 = $('#'+equalTo).val();
    
            if(val!='' && val2!='' && val!=val2)
            {
                if(input.attr('data-equal-txt'))
                    self.communicat = input.attr('data-equal-txt'); 
                else
                    self.communicat = 'Wpisane hasła nie są identyczne';
                
                return false; 
            } 

            return true;
        }
        
        self.jNumberMultiple = function(val,number)
        {
            if(val%number!=0)
            {
                self.communicat = "Wpisana liczba musi być wielokrotnością liczby "+number;
                return false;
            }
            
            return true;
        }
        
        if(options.autoTrigger===true)
        {
            self.triggerValidation();
            self.triggerSubmit();
        }
        else if(options.autoTrigger=='submit')
            self.triggerSubmit();

        //jezeli user nie podal opcji ale podal callback
        if($.isFunction(options))
            callback = options;
        
        //sprawdzamy czy callback jest funkcja
        if(typeof callback == 'function')
            callback.call(self);

    });
}

$(function(){
    $('.jValidate').jValidate();
});
