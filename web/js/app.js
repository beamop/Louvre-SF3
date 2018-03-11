var $container = $("div#form");
var index = 1;

function addBillet($container) {
    index++;
    var template = $container.attr("data-prototype").replace(/__name__/g, index);
    var $prototype = $(template);

    $container.append($prototype);
}

function delBillet() {
    swal({
        title: "Êtes-vous sûr ?",
        text: "Les informations de votre billet seront supprimés.",
        icon: "warning",
        buttons: true,
        dangerMode: false,
    })
    .then((willDel) => {
        if (willDel) {
            $('.form').children().last().remove();
        } else {
            console.log("Opération annulée.");
        }
    });
}

function warnTarif() {
    swal({
        title: "Information !",
        text: "Il vous sera nécessaire de présenter votre carte étudiant, militaire ou équivalent lors de l'entrée.",
        icon: "warning",
        buttons: true,
        dangerMode: false,
    })
        .then((willDel) => {
            if (willDel) {
                $('.form').children().last().remove();
            } else {
                console.log("Opération annulée.");
            }
        });
}
