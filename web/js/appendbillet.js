var $container = $("div#form");
var index = 1;

function addBillet($container) {
    index++;
    var template = $container.attr("data-prototype").replace(/__name__/g, index);
    var $prototype = $(template);

    $container.append($prototype);
}

