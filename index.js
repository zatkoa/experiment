
function post(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        if(params.hasOwnProperty(key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
}
var item_id = 0;
$(document).ready(function(){
    $(".dalej").unbind().click(function(e){
        var url = new URL(window.location.href);
        var par =  parseInt(url.searchParams.get("q"));
        var user_id =  parseInt(url.searchParams.get("userid"));

        if (isNaN(par))
            par = 0;

        var values = {};

        values.round = par;

        values.user_id = user_id;
        values.item_id = null;
        values.event = 'skip';

        values.category = $('.category').attr('id');
        values.offset = $('.offset').attr('id');

        var currentdate = new Date();
        values.time = currentdate.getHours() + ":"
            + currentdate.getMinutes() + ":"
            + currentdate.getSeconds();

        $.ajax({
            url: "save.php",
            type: "post",
            async: true,
            data: values,
            success: function (response) {
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });

        par++;

        url = "http://localhost/test/index.php?q=" + encodeURIComponent(par);
        url = url + "&userid=" + encodeURIComponent(user_id);
        window.location = url;
    });

    $(".display").unbind().click(function(e){
       $(".container").css("display","block");
       $(".dalej").css("display","block");
       $("a.display").css("display","none");

        var values = {};
        var url = new URL(window.location.href);
        var user_id =  parseInt(url.searchParams.get("userid"));
        var round =  parseInt(url.searchParams.get("q"));
        if (isNaN(round))
            round = 0;
        values.round = round;
        values.user_id = user_id;
        values.item_id = null;
        values.event = 'begin';

        values.category = $('.category').attr('id');
        values.offset = $('.offset').attr('id');

        var currentdate = new Date();
        values.time = currentdate.getHours() + ":"
            + currentdate.getMinutes() + ":"
            + currentdate.getSeconds();

        $.ajax({
            url: "save.php",
            type: "post",
            async: true,
            data: values,
            success: function (response) {
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });

    });


    $('.col').unbind().on('click',function(e) {

        var values = {};
        var id = $(this).attr('id');
        item_id = parseInt(id);
        var url = new URL(window.location.href);
        var round =  parseInt(url.searchParams.get("q"));
        if (isNaN(round))
            round = 0;
        values.round = round;
        values.user_id = parseInt(url.searchParams.get("userid"));
        values.item_id = item_id;
        values.event = 'detail';

        var currentdate = new Date();
        values.time = currentdate.getHours() + ":"
            + currentdate.getMinutes() + ":"
            + currentdate.getSeconds();

        values.category = $('.category').attr('id');
        values.offset = $('.offset').attr('id');

        $.ajax({
            url: "save.php",
            type: "post",
            async: true,
            data: values,
            success: function (response) {
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });

        var url = new URL(window.location.href);
        var par =  parseInt(url.searchParams.get("q"));
        var content = 'index.php';
        if (!isNaN(par)) {
            content += '?q=' + encodeURIComponent(par);
            content += '&id=' + encodeURIComponent(id);
        } else
            content += '?id=' + encodeURIComponent(id);

        $('#myModal .modal-content').load(content, function(){
            $('#myModal').modal();

            $('#myModal').off('hidden.bs.modal').on('hidden.bs.modal', function (e) {
                var url = new URL(window.location.href);
                var round =  parseInt(url.searchParams.get("q"));
                var currentdate = new Date();

                if (isNaN(round))
                    round = 0;

                values.round = round;
                values.user_id = parseInt(url.searchParams.get("userid"));
                values.item_id = item_id;
                values.time = currentdate.getHours() + ":"
                    + currentdate.getMinutes() + ":"
                    + currentdate.getSeconds();
                values.event = 'close';

                values.category = $('.category').attr('id');
                values.offset = $('.offset').attr('id');

                $.ajax({
                    url: "save.php",
                    type: "post",
                    async:true,
                    data: values ,
                    success: function (response) {
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });

            })

            $("div.kupit").unbind().click(function(){
                var values = {};
                var currentdate = new Date();
                var url = new URL(window.location.href);
                var round =  parseInt(url.searchParams.get("q"));
                if (isNaN(round))
                    round = 0;

                values.round = round;
                values.user_id = parseInt(url.searchParams.get("userid"));
                values.item_id = item_id;
                values.time = currentdate.getHours() + ":"
                    + currentdate.getMinutes() + ":"
                    + currentdate.getSeconds();
                values.event = 'nakup';

                values.category = $('.category').attr('id');
                values.offset = $('.offset').attr('id');

                $.ajax({
                    url: "save.php",
                    type: "post",
                    async:true,
                    data: values ,
                    success: function (response) {
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                    }
                });

                $(".container").css("display","none");
                $(".dalej").css("display","none");
                $('#myModal').modal('hide');
                $('#dotaz').modal({backdrop: 'static', keyboard: false})

                $('.odoslat').click(function(event) {
                    var currentdate = new Date();

                    var time = currentdate.getHours() + ":"
                        + currentdate.getMinutes() + ":"
                        + currentdate.getSeconds();
                    var feedback = $('#feedback').val();
                    var url = new URL(window.location.href);
                    var par =  parseInt(url.searchParams.get("q"));
                    var user_id = parseInt(url.searchParams.get("userid"));
                    if (isNaN(par))
                        par = 1;
                    else
                        par++;
                    url = "http://localhost/test/index.php?q=" + encodeURIComponent(par);
                    url = url + "&userid=" + encodeURIComponent(user_id);

                    post(url, {feed: feedback, user_id: user_id, time: time, item_id: item_id});
                });

            });
        });
    });

});