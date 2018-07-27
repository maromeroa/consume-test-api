<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link href="css/app.css" rel="stylesheet" type="text/css">
        <script src="js/jquery-3.3.1.min.js"></script>
        <style>
            table {
                margin: 0 auto;
            }
            th, td {
                padding: 5px;
            }
            li {
                list-style: none;
            }

            label {
                cursor: pointer;
            }
            
            #form-data {
                display: none;
            }

            a.active {
                color: #636b6f;
                text-decoration: underline;
            }

        </style>
    </head>
    <body>
    <div class="container">
        <h1 class="text-center">Welcome to dance with the dead agenda</h1>
        <div class="text-center">
            <table class="table-bordered">
                <tr>
                    <th>LUN</th>
                    <th>MAR</th>
                    <th>MIE</th>
                    <th>JUE</th>
                    <th>VIE</th>
                    <th>SAB</th>
                    <th>DOM</th>
                </tr>
                <tr>
                @foreach($days as $week => $day)
                <tr>
                    @for($d = 1; $d <= 7; $d++)
                        <td>@if(isset($day[$d])) <a href="{{ current($day[$d]) }}" class="date-selector">{{ key($day[$d]) }}</a> @endif</td>
                    @endfor
                </tr>
                @endforeach
                </tr>
            </table>
            <div id="form-data">
            <h2>Available Hours for: <span class="show-date"></span></h2>
                <form id="agenda-hours" class="form-inline" method="POST">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control" required="required"/>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" name="email" id="email" class="form-control" required="required" />
                        </div>
                        <input type="hidden" name="date" id="agenda-date"/>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </div>
                    <fieldset class="col-md-6"></fieldset>
                    <div class="col-xs-12 text-center">
                        <input type="submit" class="btn btn-primary" value="SEND" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('.date-selector').click(function(event) {
                event.preventDefault();
                $('#form-data').show();
                $('a.active').removeClass('active');
                $(this).addClass('active');
                var _date = $(this).attr('href');
                $.get('agenda/' +_date, function(result){
                    $('#agenda-date').val(_date);
                    $('.show-date').html(_date);
                    var fieldsetHtml = '';
                    $.each(JSON.parse(result), function(index, value){
                        fieldsetHtml += '<label class="radio-inline"><input type="radio" required name="start_time" value="' + value + '">' + value + '</option></label>'
                    });
                    $('#agenda-hours fieldset').html(fieldsetHtml);
                });
            });

            $('body').on('submit', '#agenda-hours', function(event){
                event.preventDefault();
                console.log($(this).serialize());
                $.post('reservation', $(this).serialize(), function(result){
                    _date = $('#agenda-date').val();
                    $.get('agenda/' + _date, function(result){
                        ;
                        $('.show-date').html(_date);
                        var fieldsetHtml = '';
                        $.each(JSON.parse(result), function(index, value){
                            fieldsetHtml += '<label class="radio-inline"><input type="radio" required name="start_time" value="' + value + '">' + value + '</option></label>'
                        });
                        $('#agenda-hours fieldset').html(fieldsetHtml);
                    });
                });
            });
        });
    </script>
    </body>
</html>