$(function () {
    var popup = $('[data-remodal-id=modal]').remodal();

    ymaps.ready(init);
    var myMap;

    function init() {
        myMap = new ymaps.Map("map", {
            center: [55.76, 37.64],
            zoom: 7
        });

        $.each(cities, function (index, item) {
            ymaps.geocode(item.title, {
                results: 1
            }).then(function (res) {
                var firstGeoObject = res.geoObjects.get(0);

                var coords = firstGeoObject.geometry.getCoordinates();

                var myPlacemark = new ymaps.Placemark(coords, {
                    hintContent: item.title,
                    title: item._id
                });

                myPlacemark.events.add('click', function (e) {
                    $.ajax({
                        method: 'get',
                        url: current_forecast_handler + '/' + myPlacemark.properties.get('title')
                    }).done(function (response) {

                        $('#city-temp').html(response.forecast.temperature);
                        $('#city-title').html(myPlacemark.properties.get('hintContent'));
                        $('#city-heat').html(response.forecast.heat_index);
                        $('#city-wind').html(response.forecast.wind);
                        $('#city-pressure').html(response.forecast.pressure);
                        $('#city-humidity').html(response.forecast.humidity);
                        $('#city-sunset').html(response.forecast.time_of_sunset);
                        $('#city-sunrise').html(response.forecast.time_of_sunrise);
                        $('#city-precip-title').html(response.forecast.precip_title);

                        var chart = $('#container').highcharts();

                        chart.xAxis[0].setCategories(response.archive.title);
                        chart.series[0].setData(response.archive.heat_index);
                        chart.series[1].setData(response.archive.humidity);
                        chart.series[2].setData(response.archive.pressure);
                        chart.series[3].setData(response.archive.temperature);
                        chart.series[4].setData(response.archive.wind);

                        chart.redraw();
                        popup.open();
                    })
                });

                myMap.geoObjects.add(myPlacemark);
            });
        });
    }

    $('#container').highcharts({
        title: {
            text: 'Архивные данные',
            x: -20 //center
        },
        xAxis: {
            categories: []
        },
        yAxis: {
            title: {
                text: ''
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            name: 'Ощущение',
            data: [],
            tooltip : {
                valueSuffix : 'C'
            }
        }, {
            name: 'Влажность',
            data: [],
            tooltip : {
                valueSuffix : '%'
            }
        }, {
            name: 'Давление',
            data: [],
            tooltip : {
                valueSuffix : 'мм'
            }
        }, {
            name: 'Температура',
            data: [],
            tooltip : {
                valueSuffix : 'C'
            }
        }, {
            name: 'Ветер',
            data: [],
            tooltip : {
                valueSuffix : 'м/с'
            }
        }]
    });
});