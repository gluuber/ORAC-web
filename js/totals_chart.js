/* 
 Created on : 21/08/2019, 2:20:07 PM
 Author     : gregmclachlan boombana@gmail.com
 */

function drawSpeciesChart(e) {
    var json = $.ajax({
        url: "/includes/get_species_trend_data.php",
        data: {
            type: e.id,
            value: e.value
        },
        dataType: "json",
        async: !1
    }).responseText;

    var data = new google.visualization.DataTable(json);
    var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));

    //var colors = ['#1b9e77', '#f4b400', '#db4437'];
    var colours = [];
    for (var i = 0; i < data.getNumberOfRows(); i++) {
        var x = data.getValue(i, 1);
        switch (data.getValue(i, 1)) {
            case 1:
                colours.push('#1b9e77');
                break;

            case -1:
                colours.push('#db4437');
                break;
                
            default:
                colours.push('#f4b400');
                break;
        }
    }
    colours = ['#1b9e77', '#1b9e77', '#1b9e77', '#1b9e77', '#1b9e77', '#1b9e77', '#1b9e77', '#db4437', '#db4437', '#f4b400']
    var options = {
        title: e.value,
        colors: colours,
        fill: 25,
        height: 400,
        width: 1280,
        tooltip: {isHtml: true},
        legend: 'none',
        isStacked: true,
        hAxis: {
            gridlines: { color: '#333', count: 10 },
            viewWindowMode: 'explicit',
            slantedText: true,
            slantedTextAngle: 90,
            textStyle: {
                fontSize: 10,
                fontStyle: "Arial",
                align: "center",
                color: '#808080'
            },
            ticks: [5,10,15,20]
        },
        vAxis: {
            viewWindowMode: 'explicit',
            viewWindow: {
                max: 1,
                min: -1
            },
            ticks: [1, 0, -1]
        },
    };
    chart.draw(data, options);
    //$(".chart_title").text(e.value);
}

var selected;
var s = get("s");

function drawInitChart() {
    s = get("s");
    //alert('s: ' + s);
    if (s) {
        $('#species').val(s);
        $('#species').val(s).change();
    }
}

window.onload = function () {
    //$("#stats-button").addClass("active");

    // Get list of species
    $.ajax({
        url: "/includes/get_species_list.php", type: "GET", dataType: "json",
        success: function (e) {
            $.each(e, function (e, t) {
                $("#species").append('<option value="' + t + '">' + t + "</option>")
            })
        }
    });

    // Select first value as default
    $("#species").val($("#species option:first").val());

    // Add on change event handler to select list
    $("#species").on("change", function () {
        if ($("#species").val() == '') {
            alert('Please select a species.');
        }
        else {
            selected = this;
            drawSpeciesChart(selected);
        }
    });

    // Draw chart
    setTimeout(function () {
        google.charts.load("current", {packages: ["corechart"], callback: drawInitChart});
    }, 1300);

}

function get(name) {
    if (name = (new RegExp('[?&]' + encodeURIComponent(name) + '=([^&]*)')).exec(location.search)) {
        return decodeURIComponent(name[1]);
    }
}
