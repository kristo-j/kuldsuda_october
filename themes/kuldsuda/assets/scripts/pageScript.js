$(document).ready(function(){
    var emailPattern = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
    var selectedDesign = 'funDesign';
    var canvas = document.getElementById('templateCanvas'),
        ctx = canvas.getContext('2d');
    var background = new Image();
    var imageName;
    var selectedPicture;
    var lineId;
    var languageId = $('#languageElem').attr('value');
    var controllerUrl = "./kuldsuda/kuldsuda/";

    /*
    var $.notify('E-maili väli peab olema täidetud.');
    $.notify("Midagi läks valesti!");
    $.notify('Teie e-mail ei vasta e-maili nõuetele.');
    $.notify('Kinnitage, et olete tingimustega tutvunud.');
    $.notify('Kolleegi e-mail ei vasta e-maili nõuetele.');
    $.notify('Kõik väljad peavad täidetud olema.');
    $.notify('E-maili saatmisel tekkis tõrge!');
    */

    function saveImageToServer(dataUrl, lineId){
        $.ajax({
            type: 'POST',
            url: controllerUrl+'saveimage',
            data: {
                imgBase: dataUrl,
                lang: languageId,
                lineId: lineId
            },
            success: function(data){
                imageName = data;

                $('.finishSection').append(
                    '<img id="generatedPicture" class="inheritDimension" align="center" src="./themes/kuldsuda/assets/images/genereeritud_tunnustused/'+data+'">'
                );
            }
        }).done(function(){
            //currentSlide(id+1);
        });
    }

    function saveRecognition() {
        $.ajax({
            type: 'POST',
            url: controllerUrl+'saverecognition',
            async: false,
            data: {
                email: $('.creatorInput').val(),
                reason: $('.textAreaInput').val(),
                pictureType: selectedPicture,
                recognizedName: $('.nameInput').val(),
                lang: languageId
            },
            success: function(data){
                lineId = data;
            },
            failure: function(){
                $.notify(languageId === 'est' ? 'Midagi läks valesti!' : 'Что-то пошло не так');
            }
        });
    }

    function saveUserAnswer(sentTo, name, email){
        $.ajax({
            type: 'post',
            url: controllerUrl+'saveuseranswer',
            data:{
                senderName: name,
                senderEmail: email,
                sentTo: sentTo,
                id: lineId
            },
            success: function(data){

            }
        });
    }

    function forwardSlide(id) {
        currentSlide(id+1);

        if(id+1==2){
            $('.carouselSectionFun').show();
        }

        window.scrollTo(0, 0);
    }

    $('.addPersonName').on('click', function() {
        if(!$('.nameInput').val()) {
            $('.nameInput').addClass('errorBorderStyle');
            $.notify(languageId === 'est' ? 'Nimi peab olema täidetud.' : 'Все графы должны быть заполнены');
            return;
        } else {
            $('.creatorInput').removeClass('errorBorderStyle');
        }

        forwardSlide(parseInt($(this).attr('id')));
    });

    $('.generatePicture').on('click', function(){
        if(!$('.creatorInput').val()) {
            $('.creatorInput').addClass('errorBorderStyle');
            $.notify(languageId === 'est' ? 'E-maili väli peab olema täidetud.' : 'Э-майл должен быть введён');
            return;
        } else {
            $('#senderEmail').val($('.creatorInput').val());
            $('.creatorInput').removeClass('errorBorderStyle');
        }

        if(!emailPattern.test($('.creatorInput').val())){
            $('.creatorInput').addClass('errorBorderStyle');
            $.notify(languageId === 'est' ? 'Teie e-mail ei vasta e-maili nõuetele.' : 'Ваш э-майл не отвечает требованиям');
            return;
        }else{
            $('#senderEmail').removeClass('errorBorderStyle');
        }

        if(!$('#confirmationCheckboxSlide').is(':checked')){
            $('.generateCheckBoxSection').addClass('errorBorderStyle');
            $.notify(languageId === 'est' ? 'Kinnitage, et olete tingimustega tutvunud.' : 'Подтвердите, что ознакомились с условиями');
            return;
        }else{
            $('.generateCheckBoxSection').removeClass('errorBorderStyle');
        }

        var selectedImage;
        var id = parseInt($(this).attr('id'));

        switch(selectedDesign){
            case 'funDesign':
                var imgElements = $('.carousel-fun').children();

                imgElements.each(function(i){
                    if($(this).hasClass('active')){
                        selectedImage = $(this).find('img');

                    }
                });

                break;
            case 'elegantDesign':
                var imgElements = $('.carousel-elegant').children();

                imgElements.each(function(i){
                    if($(this).hasClass('active')){
                        selectedImage = $(this).find('img');
                    }
                });
                break;
        }

        var image = new Image();

        image.src = selectedImage[0]['src'];
        selectedPicture = selectedImage[0]['name'];

        image.onload = function(){

            //ctx.canvas.width = this.width;
            //ctx.canvas.height = this.height;

            ctx.canvas.width = 1200;
            ctx.canvas.height = 630;
            var imgSrc = selectedImage[0]['src'];
/*
            if (imgSrc.includes('tunnustused_elegantne')) {
                imgSrc = imgSrc.replace('tunnustused_elegantne_svg', 'tunnustused_elegantne_png');
                imgSrc = imgSrc.replace('.svg', '.png');
            }

            if (imgSrc.includes('tunnustused_crazy')) {
                imgSrc = imgSrc.replace('tunnustused_crazy_svg', 'tunnustused_crazy_png');
                imgSrc = imgSrc.replace('.svg', '.png');
            }
*/
            background.src = imgSrc;

            saveRecognition();

            background.onload = function(){
                ctx.drawImage(background,0,0);

                switch(selectedDesign){
                    case 'funDesign':
                        ctx.fillStyle = "#244999";
                        ctx.font = "bold 40px arial";
                        ctx.fillText($('.companyInput').val(), 655, 315);
                        ctx.font = "bold 40px arial";
                        ctx.fillText($('.nameInput').val(), 655, 545);
                        break;
                    case 'elegantDesign':
                        ctx.fillStyle = "white";
                        ctx.font = "bold 40px arial";
                        ctx.fillText($('.companyInput').val(), 515, 205);
                        ctx.font = "bold 40px arial";
                        ctx.fillText($('.nameInput').val(), 515, 495);
                        break;
                }

                saveImageToServer(canvas.toDataURL(), lineId);
            }

        };

        forwardSlide(parseInt($(this).attr('id')));
    });

    $('.example-control-prev').on('click', function(){
        $('.exampleCarousel').carousel('prev');
    });

    $('.example-control-next').on('click', function(){
        $('.exampleCarousel').carousel('next');
    });

    $('.fun-control-prev').on('click', function(){
        $('.carouselSectionFun').carousel('prev');
    });

    $('.fun-control-next').on('click', function(){
        $('.carouselSectionFun').carousel('next');
    });

    $('.elegant-control-prev').on('click', function(){
        $('.carouselSectionElegant').carousel('prev');
    });

    $('.elegant-control-next').on('click', function(){
        $('.carouselSectionElegant').carousel('next');
    });

    $('.carouselSectionElegant').swipeleft(function(){
        $('.carouselSectionElegant').carousel('next');
    });

    $('.carouselSectionElegant').swiperight(function(){
        $('.carouselSectionElegant').carousel('prev');
    });

    $('.carouselSectionFun').swipeleft(function(){
        $('.carouselSectionFun').carousel('next');
    });

    $('.carouselSectionFun').swiperight(function(){
        $('.carouselSectionFun').carousel('prev');
    });

    $('.templateImage').on('load', function(){
        var css;
        var ratio=$(this).width() / $(this).height();
        var pratio=$(this).parent().width() / $(this).parent().height();
        if (ratio<pratio) css={width:'auto', height:'100%'};
        else css={width:'100%', height:'auto'};
        $(this).css(css);
    });

    var slideIndex = 1;
    showSlides(slideIndex);

    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    function showSlides(n) {
        var i;
        var slides = document.getElementsByClassName("mySlides");
        if (n > slides.length) {slideIndex = 1}
        if (n < 1) {slideIndex = slides.length}
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }

        slides[slideIndex-1].style.display = "block";

        window.scrollTo(0, 0);

    }

    function shareInFacebookDialog() {
        tunnustusLocation = "https://www.kuldsuda.ee/"+imageName.replace(".png", "");
        //imageLocation = "https://www.kuldsuda.ee/themes/kuldsuda/assets/images/genereeritud_tunnustused/"+imageName;

        FB.ui({
            method: 'share',
            href: tunnustusLocation,
            title: 'Kuldsüda',
        }, function(response){
            if (response && !response.error_message) {
                saveUserAnswer("facebook", "");
                currentSlide(7);
            } else {
                $.notify(languageId === 'est' ? "Midagi läks valesti!" : 'Что-то пошло не так');
            }
        });

        /*
        FB.ui({
            method: 'share_open_graph',
            action_type: 'og.shares',
            action_properties: JSON.stringify({
                object: {
                    'og:url': 'https://www.kuldsuda.ee',
                    'og:title': 'Kuldsüda',
                    'og:description': 'Oscareid jagatakse kord aastas, aga kuldsüdameid '+
                                      'saab jagada ka siis, kui kiitmise tunne peale tuleb.'+
                                      'Võta 3 minutit, et tunnustada kolleegi kuldsüdamega.'+
                                      'Vali välja sobiv tiitel 10-ne hulgast ning edasta see '+
                                      'tema Facebooki seinale või e-mailile.',
                    'og:image': pictureLocation,
                }
            })
        }, function(response){
            if (response && !response.error_message) {
              saveUserAnswer();
              currentSlide(7);
            } else {
                $.notify("Midagi läks valesti!");
            }
        });
        */
    }

    $('.forwardButton').on('click', function(){
        forwardSlide(parseInt($(this).attr('id')));
    });

    $('#sendToEmail').on('click', function(){
        var senderName = $('#senderName').val();
        var senderEmail = $('#senderEmail').val();
        var receiverEmail = $('#receiverEmail').val();
        var messageBody = $('#messageBody').val();
        var imageSrc = imageName;

        if(!senderName || !senderEmail || !receiverEmail || !messageBody)
        {
            if(!senderName){
                $('#senderName').addClass('errorBorderStyle');
            }else{
                $('#senderName').removeClass('errorBorderStyle');
            }

            if(!senderEmail){
                $('#senderEmail').addClass('errorBorderStyle');
            }else{
                $('#senderEmail').removeClass('errorBorderStyle');
            }

            if(!receiverEmail){
                $('#receiverEmail').addClass('errorBorderStyle');
            }else{
                $('#receiverEmail').removeClass('errorBorderStyle');
            }

            if(!messageBody){
                $('#messageBody').addClass('errorBorderStyle');
            }else{
                $('#messageBody').removeClass('errorBorderStyle');
            }

            $.notify(languageId === 'est' ? 'Kõik väljad peavad täidetud olema.' : 'Все графы должны быть заполнены');

            return;
        }

        $('#messageBody').removeClass('errorBorderStyle');
        $('#senderName').removeClass('errorBorderStyle');

        if(!emailPattern.test($('#senderEmail').val())){
            $('#senderEmail').addClass('errorBorderStyle');
            $.notify(languageId === 'est' ? 'Teie e-mail ei vasta e-maili nõuetele.' : 'Ваш э-майл не отвечает требованиям');
            return;
        }else{
            $('#senderEmail').removeClass('errorBorderStyle');
        }

        if(!emailPattern.test($('#receiverEmail').val())){
            $('#receiverEmail').addClass('errorBorderStyle');
            $.notify(languageId === 'est' ? 'Kolleegi e-mail ei vasta e-maili nõuetele.' : 'Э-майл коллеги не отвечает требованиям');
            return;
        }else{
            $('#receiverEmail').removeClass('errorBorderStyle');
        }

        if(!$('#confirmationCheckbox').is(':checked')){
            $('.checkBoxSection').addClass('errorBorderStyle');
            $.notify(languageId === 'est' ? 'Kinnitage, et olete tingimustega tutvunud.' : 'Подтвердите, что ознакомились с условиями');
            return;
        }else{
            $('.checkBoxSection').removeClass('errorBorderStyle');
        }

        $.ajax({
            type: 'POST',
            url: controllerUrl+'sendemail',
            data:{
                senderName: senderName,
                senderEmail: senderEmail,
                receiverEmail: receiverEmail,
                messageBody: messageBody,
                imageSrc: imageSrc,
                id: lineId
            },
            success: function(data){
                currentSlide(7);
                saveUserAnswer("email", senderName, senderEmail);
                $('.sendEmailModal').modal('toggle');
            },
            failure: function(data){
                $.notify(languageId === 'est' ? 'E-maili saatmisel tekkis tõrge!' : 'При отправлении э-майла возникла ошибка');
                $('.sendEmailModal').modal('toggle');
            }
        });
    });

    $('.templateSelection').on('click', function(){
        if($(this).attr('id') == 'funDesign'){
            selectedDesign = 'funDesign';

            $('#funDesign').addClass('selected');
            $('#elegantDesign').removeClass('selected');

            $('.carouselSectionFun').show();
            $('.carouselSectionElegant').hide();
        }

        if($(this).attr('id') == 'elegantDesign'){
            selectedDesign = 'elegantDesign';

            $('#funDesign').removeClass('selected');
            $('#elegantDesign').addClass('selected');

            $('.carouselSectionFun').hide();
            $('.carouselSectionElegant').show();
        }
    });

    $('#generateNew').on('click', function(){
        location.reload();
    });

    $('#checkJobOffers').on('click', function(){
        window.location.replace('https://www.cv.ee/toopakkumised');
    });

    $('.facebookTutImg').on('click', function(){
        shareInFacebookDialog();
    });

    $('#shareToFacebook').on('click', function(){
        shareInFacebookDialog();
    });

    $('#shareToFacebookTest').on('click', function(){
        //var pictureLocation = $('#generatedPicture').attr('src');
        //var picture = "5ad8e8fb2b9d4.png";
        var picture = "5ad8e9c1ecf54.png";

        pictureLocation = "https://www.kuldsuda.ee/tunnustused/"+picture;

        FB.ui({
            method: 'share',
            href: pictureLocation,
            title: 'Kuldsüda',
        }, function(response){
            if (response && !response.error_message) {
                saveUserAnswer();
                currentSlide(7);
            } else {
                $.notify("Midagi läks valesti!");
            }
        });
    });

    window.fbAsyncInit = function() {
        FB.init({
            appId            : '140977083233016',
            autoLogAppEvents : true,
            xfbml            : true,
            version          : 'v2.11'
        });
    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    $('.exampleCarousel').show();
});
