
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

    $('.col').click(function(){
        $('#myModal').modal('show');
    });

});