/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* 
 Created on : 09/04/2017, 2:20:07 PM
 Author     : gregmclachlan
 */

function drawSpeciesChart(e) {
  var json = $.ajax({
    url: "/src/NSWORAC_Index_of_Cases.json",
    data: {
      type: e.id,
      value: e.value
    },
    dataType: "json",
    async: !1
  }).responseText;
  source_data = new google.visualization.DataTable(json);
  chart = new google.visualization.ComboChart(document.getElementById("chart_div"));
  
  options = {
    title: "Totals for " + e.value + " averaged out. Data is shown from 1997 to the latest trip. Mouseover the bars for the totals for each month. January is not charted - retained as a label - as limited trips are run during that month. Trips weren't run on every month for every year. Some species are seasonal. NB: the average line may taper off dramatically on the current year as the records may not be complete.",
    width: "100%",
    height: 800,
    seriesType: 'bars',
    series: {12: { type: 'line', curveType: 'none' }},

    hAxis: {
      format: "0",
      title: "Year",
      textStyle: {
        color: "#666666",
        fontSize: "14",
        slantedText: true,
      },
      legend: {
        position: "bottom"
      },
      gridlines: {
        count: 22
      }
    },
    vAxis: {
      format: "0",
      title: "Count",
      textStyle: {
        color: "#666666",
        fontSize: "14",
        paddingRight: "10",
        marginRight: "10"
      },
      gridlines: {
        count: 5
      }
    },
    legend: {
      position: "top",
      maxLines: 3
    },
    bar: { 
      groupWidth: 30 
    },
    isStacked: true
  };
  chart.draw(source_data, options)
}

var selected;
var s = get("s");
//google.setOnLoadCallback(drawInitChart);

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
    url: "getSpeciesList.php", type: "GET", dataType: "json",
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
