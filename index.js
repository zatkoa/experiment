
$(document).ready(function(){
    $("p.nazov-produktu").each(function(){
        a = $(this).text();
        if (a.length > 50)
            $(this).css("padding-top", "11px");
    });

    $(".next").click(function(){
        var url = new URL(window.location.href);
        var par =  parseInt(url.searchParams.get("q"));
        if (isNaN(par))
            par = 1;
        else
            par++;
        window.location = "http://localhost/test/index.php?q=" + encodeURIComponent(par);
    });

    $('.col').on('click',function(){
        var id = $(this).attr('id');
        var url = new URL(window.location.href);
        var par =  parseInt(url.searchParams.get("q"));
        var content = 'index.php';
        if (!isNaN(par)) {
            content += '?q=' + encodeURIComponent(par);
            content += '&id=' + encodeURIComponent(id);
        } else
            content += '?id=' + encodeURIComponent(id);

        $('.modal-content').load(content, function(){
            $('#myModal').modal();
        });
    });

});