var svg = d3.select("svg");
var g = svg.append("g");
var path = d3.geoPath();

// Setting color range.
var color = d3.scaleLinear().domain([1, 100])
    .interpolate(d3.interpolateHcl)
    .range([d3.rgb("#c1e5ec"), d3.rgb('#1d5576')]);

var pathArray = window.location.pathname.split('/');
var year = pathArray[2];
if (!year) {
    year = default_year; // default_year set elsewhere as global var.
}
var category = pathArray[3];
if (!category || category < 1) {
    category = "all";
}
var subcategory = pathArray[4];
if (!subcategory || subcategory < 1) {
    subcategory = "all";
}

var mapToolTip = document.getElementById('mapToolTip');

// Loading US topology data.
d3.json("/modules/custom/sfal_nat_map/js/us2.json", function (unitedState) {

    var data = topojson.feature(unitedState, unitedState.objects.states).features;
    var names = {};

    // Load and map state names to tsv of fips and usps codes.
    d3.tsv("/modules/custom/sfal_nat_map/js/us-state-names.tsv", function (tsv) {
        tsv.forEach(function (d, i) {
            names[d.id] = d.code;
        });
        d3.json("/national_map_data_2/" + year + "/" + category + "/" + subcategory, function (mapData) {

            Object.keys(mapData).forEach(function (key) {
                for (var k = 0; k < data.length; k++) {
                    if (data[k].properties.state_abbrev == mapData[key].field_state_code) {
                        data[k].properties.lawCount = mapData[key].view.length;
                        data[k].properties.stateName = mapData[key].title;
                    }
                }
            });

            // Here's the svg generation magic.
            g.append("g")
                .attr("class", "states-bundle")
                .selectAll("path")
                .data(data)
                .enter()
                .append("path")
                .attr("d", path)
                .attr("stroke", "white")
                .attr("class", "states")
                .attr("role", "img")
                .attr("tab-index", "0")
                .attr("alt", function (d) {
                    return d.properties.stateName + ". " + d.properties.lawCount + " Gun Laws. Select this state to view more inforamation.";
                })
                .attr("aria-label", function (d) {
                    return d.properties.stateName + ". " + d.properties.lawCount + " Gun Laws. Select this state to view more inforamation.";
                })
                .style("fill", function (d) {
                    var value = d.properties.lawCount;
                    if (value) {
                        return color(value);
                    } else {
                        return "#e6e7e8";
                    }
                })
                .on('mouseover', function (d) {
                    if (d.properties.lawCount) {
                        mapToolTip.innerHTML = "<span class='mapToolTipName' id='mapToolTipName'>" + d.properties.stateName + "</span><span class='mapToolTipNumber' id='mapToolTipNumber'>" + d.properties.lawCount + "</span> Gun Laws";
                        mapToolTip.style.top = ((path.centroid(d)[1] * d3.select("svg").attr("scaleFac")) - 70) + "px";
                        mapToolTip.style.left = ((path.centroid(d)[0] * d3.select("svg").attr("scaleFac")) - (mapToolTip.offsetWidth / 2)) + "px";
                        d3.select(this).style("fill", "#f1ce65");
                    }
                })
                .on('mouseout', function (d) {
                    mapToolTip.innerHTML = "";
                    mapToolTip.style.top = "-2000px";
                    mapToolTip.style.left = "-2000px";
                    d3.select(this).style("fill", function (d) {
                        var value = d.properties.lawCount;
                        if (value) {
                            return color(value);
                        } else {
                            return "#e6e7e8";
                        }
                    });
                })
                .on('focus', function (d) {
                    if (d.properties.lawCount) {
                        mapToolTip.innerHTML = "<span class='mapToolTipName'>" + d.properties.stateName + "</span><span class='mapToolTipNumber'>" + d.properties.lawCount + "</span> Gun Laws";
                        mapToolTip.style.top = ((path.centroid(d)[1] * d3.select("svg").attr("scaleFac")) - 70) + "px";
                        mapToolTip.style.left = ((path.centroid(d)[0] * d3.select("svg").attr("scaleFac")) - (mapToolTip.offsetWidth / 2)) + "px";
                        d3.select(this).style("fill", "#f1ce65");
                    }
                })
                .on('blur', function (d) {
                    mapToolTip.innerHTML = "";
                    mapToolTip.style.top = "-2000px";
                    mapToolTip.style.left = "-2000px";
                    d3.select(this).style("fill", function (d) {
                        var value = d.properties.lawCount;
                        if (value) {
                            return color(value);
                        } else {
                            return "#e6e7e8";
                        }
                    });
                })
                .on('click', function (d) {
                    window.location = "/states/" + d.properties.state_abbrev + "/" + year;
                })
                .on('keydown', function (d) {
                    if (event.keyCode === 13) {
                        window.location = "/states/" + d.properties.state_abbrev + "/" + year;
                    }
                })
            ;

        });
    });

    function setLegend() {
        var legend = svg.selectAll("g.legend")
            .data([11, 22, 34, 45, 57, 68, 80, 91, 102, 115, 130]) // Law count limits. Arbitrary based on visual pleasure.
            .enter().append("g")
            .attr("class", "legend");

        var ls_w = 20, ls_h = 20;
        var legend_labels = ["1-10", "11-20", "21-30", "31-40", "41-50", "51-60", "61-70", "71-80", "81-90", "91-100", "100+"];
        var width = jQuery("#sfalNatMap").width();
        var height = jQuery("#sfalNatMap").height();
        var legTop = Math.max(30, height - (legend_labels.length * ls_h) - 100);

        legend.append("rect")
            .attr("x", 20)
            .attr("y", function (d, i) {
                return legTop + (i * ls_h); // Slight voodoo, but just an inc stepper for alignment.
            })
            .attr("width", ls_w)
            .attr("height", ls_h)
            .style("fill", function (d, i) {
                return color(d);
            })
            .style("opacity", 0.8);

        legend.append("text")
            .attr("x", 50)
            .attr("y", function (d, i) {
                return legTop + (i * ls_h) + (ls_h / 2) + 4; // More voodoo. Same stepper with a vertical centering offset.
            })
            .text(function (d, i) {
                return legend_labels[i];
            });

        legend.append("text")
            .attr("x", 0)
            .attr("y", legTop - 10)
            .text("State Gun Laws");
    }

    d3.select(window).on("resize", function () {
        sizeChange();
        svg.selectAll("g.legend").remove();
        if (jQuery("#sfalNatMap").width() > 900) {
            setLegend();
        }
    });
    jQuery(function () {
        sizeChange();
        setLegend();
    });

    function sizeChange() {
        d3.select("svg").attr("scaleFac", jQuery("#sfalNatMap").width() / 900);
        d3.select("g").attr("transform", "scale(" + jQuery("#sfalNatMap").width() / 900 + ")");
        jQuery("svg").height(jQuery("#sfalNatMap").width() * 0.618);
    }
});

/* Helper function to parse q */
function getQueryString() {
    var key = false, res = {}, itm = null;
    var qs = location.search.substring(1);
    if (arguments.length > 0 && arguments[0].length > 1)
        key = arguments[0];
    var pattern = /([^&=]+)=([^&]*)/g;
    while (itm = pattern.exec(qs)) {
        if (key !== false && decodeURIComponent(itm[1]) === key)
            return decodeURIComponent(itm[2]);
        else if (key === false)
            res[decodeURIComponent(itm[1])] = decodeURIComponent(itm[2]);
    }
    return key === false ? res : null;
}

