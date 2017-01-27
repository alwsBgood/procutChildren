$(function() {
  $("[name=send]").click(function () {
    $(":input.error").removeClass('error');
    $(".allert").remove();

    var error;
    var btn = $(this);
    var form = btn.closest('form');
    var validaton = btn.closest('form').find('[required]');
    var msg = btn.closest('form').find('input, textarea, select');
    var send_btn = btn.closest('form').find('[name=send]');

    var leade_name = btn.closest('form').find('[name=name]').val();
    var href = document.location.href;
    var new_url = href.split('?')[1];
    var ref = '&ref=' + document.referrer;
    var id = 'procut_kids_masterclass';
    var url = href.split('?')[0];
    var utm_catch = '&' + new_url + "&page_url=" + url;
    var lead_price = "&lead_price=" + $('#price').html();
    var invite_id = "&invite_id="+href.split('invite_id=')[1];
    var cook_ga;
    var hmid;


    $(validaton).each(function() {
      if ($(this).val() == '') {
        var errorfield = $(this);
        $(this).addClass('error').parent('.field').append('<div class="allert"><span>Заполните это поле</span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 286.1 286.1"><path d="M143 0C64 0 0 64 0 143c0 79 64 143 143 143 79 0 143-64 143-143C286.1 64 222 0 143 0zM143 259.2c-64.2 0-116.2-52-116.2-116.2S78.8 26.8 143 26.8s116.2 52 116.2 116.2S207.2 259.2 143 259.2zM143 62.7c-10.2 0-18 5.3-18 14v79.2c0 8.6 7.8 14 18 14 10 0 18-5.6 18-14V76.7C161 68.3 153 62.7 143 62.7zM143 187.7c-9.8 0-17.9 8-17.9 17.9 0 9.8 8 17.8 17.9 17.8s17.8-8 17.8-17.8C160.9 195.7 152.9 187.7 143 187.7z" fill="#E2574C"/></svg></div>');
        error = 1;
        $(":input.error:first").focus();
        return;
      } else {
        var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
        if ($(this).attr("type") == 'email') {
          if(!pattern.test($(this).val())) {
            $("[name=email]").val('');
            $(this).addClass('error').parent('.field').append('<div class="allert"><span>Укажите коректный e-mail</span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 286.1 286.1"><path d="M143 0C64 0 0 64 0 143c0 79 64 143 143 143 79 0 143-64 143-143C286.1 64 222 0 143 0zM143 259.2c-64.2 0-116.2-52-116.2-116.2S78.8 26.8 143 26.8s116.2 52 116.2 116.2S207.2 259.2 143 259.2zM143 62.7c-10.2 0-18 5.3-18 14v79.2c0 8.6 7.8 14 18 14 10 0 18-5.6 18-14V76.7C161 68.3 153 62.7 143 62.7zM143 187.7c-9.8 0-17.9 8-17.9 17.9 0 9.8 8 17.8 17.9 17.8s17.8-8 17.8-17.8C160.9 195.7 152.9 187.7 143 187.7z" fill="#E2574C"/></svg></div>');
            error = 1;
            $(":input.error:first").focus();
          }
        }
        var patterntel = /^()[- +()0-9]{9,18}/i;
        if ( $(this).attr("type") == 'tel') {
          if(!patterntel.test($(this).val())) {
            $("[name=phone]").val('');
            $(this).addClass('error').parent('.field').append('<div class="allert"><span>Укажите номер телефона в формате +3809999999</span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 286.1 286.1"><path d="M143 0C64 0 0 64 0 143c0 79 64 143 143 143 79 0 143-64 143-143C286.1 64 222 0 143 0zM143 259.2c-64.2 0-116.2-52-116.2-116.2S78.8 26.8 143 26.8s116.2 52 116.2 116.2S207.2 259.2 143 259.2zM143 62.7c-10.2 0-18 5.3-18 14v79.2c0 8.6 7.8 14 18 14 10 0 18-5.6 18-14V76.7C161 68.3 153 62.7 143 62.7zM143 187.7c-9.8 0-17.9 8-17.9 17.9 0 9.8 8 17.8 17.9 17.8s17.8-8 17.8-17.8C160.9 195.7 152.9 187.7 143 187.7z" fill="#E2574C"/></svg></div>');
            error = 1;
            $(":input.error:first").focus();
          }
        }
      }
    });
    if(!(error==1)) {
      $(send_btn).each(function() {
        $(this).attr('disabled', true);
      });
      var loc = ymaps.geolocation.city+', '+ymaps.geolocation.region+', '+ymaps.geolocation.country;
      form.find('.geoloc').val(loc);
      var data = form.serialize();
      var data_form = form.attr('data-form');
      var temp_date = new Date();
      var temp_month = temp_date.getMonth();
      temp_month++;
      var date_submitted = '&date_submitted=' +temp_date.getDate()+" "+temp_month+" " +temp_date.getFullYear();
      var time_submitted = '&time_submitted=' +temp_date.getHours() + ":" +temp_date.getMinutes();
      data += leade_name;
      data += utm_catch;
      data += date_submitted;
      data += time_submitted;
      data += ref;
      data += cook_ga;

      $.ajax({
        type: "POST",
        url:"../amo/amocontactlist.php",
        data: data,
        success: function() {
          console.log('amo ok!');

          setTimeout(function() {
            form.find('button').text('✔ Отправлено');
          }, 350);
          dataLayer.push({'event': 'FormSubmit', 'form_type': data_form});
          setTimeout(function() {
            if($("#modal_question").hasClass('md-show')) {
              window.location = "http://kids.procut.com.ua/mc/success/index_question.html"
            } else {
              window.location = "http://kids.procut.com.ua/mc/success/index.html"
            }
          }, 1500);
        }
      });

    }
    return false;
  })
});

$(document).ready(function() {
    $('.slider').slick({
        slidesToShow: 4,
        dots: false,
        arrows: false,
        // fade: true,
        // slidesToScroll: 1,
        autoplay: true,
        adaptiveHeight: false,
        responsive:[
          {
            breakpoint: 600,
            settings: {
              slidesToShow: 3,
            }
          },
          {
            breakpoint: 480,
            settings: {
              slidesToShow: 2,
            }
          },
        ]
    });
});



// MAP overlay disable
$('.overlay').click(function() {
  $(this).css('display', 'none');
  $('.map .item').css('display', 'none');
});


