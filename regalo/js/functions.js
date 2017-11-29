var actual_pop_obras = [];
actual_pop_obras[0] = 0;
actual_pop_obras[1] = 0;
actual_pop_obras[2] = 0;
actual_pop_obras[3] = 0;
var multiplier_pop_obras = 621;
var output_email=0

function openHelpLayer(){
    $("#black-layer").show();
    $("#como-funciona-layer").fadeIn(500);
}
function closeHelpLayer(){
    $("#black-layer").hide();
    $("#como-funciona-layer").hide();
}

function openUploadPop(url){
    $("#black-layer").show();
    $("#upload-image-pop").fadeIn(500);
    $( "#upload-image-pop-content" ).load(url);
}

function closeUploadPop(){
    $("#black-layer").hide();
    $("#upload-image-pop").hide();
}
