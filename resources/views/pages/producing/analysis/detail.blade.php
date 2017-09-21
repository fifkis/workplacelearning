@extends('layout.HUdefault')
@section('title')
    Detail Page
@stop
@section('content')

    <div class="container-fluid">
        <script>
            $(document).ready(function(){
                $(".expand-detail").click(function(e){
                    $("#detail-"+($(this).attr("data-id"))).toggle();
                    e.preventDefault();
                });
            });
        </script>

        @if(Auth::user()->getCurrentWorkplaceLearningPeriod() != null && Auth::user()->getCurrentWorkplaceLearningPeriod()->hasLoggedHours())
            <div class="row">
                <div class="col-lg-12">
                    <h1>{{ Lang::get('rapportages.pageheader') }}
                        <?php
                        $intlfmt = new IntlDateFormatter(
                                (LaravelLocalization::getCurrentLocale() == "en") ? "en_US" : "nl_NL",
                                IntlDateFormatter::GREGORIAN,
                                IntlDateFormatter::NONE,
                                NULL,
                                NULL,
                                "MMMM YYYY"
                        );
                        echo $intlfmt->format(strtotime($year."-".$monthno."-01"));
                        ?>
                    </h1>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h2>Tijd per categorie</h2>
                    <canvas id="chart_hours"></canvas>
                    <script>
                        var canvasHours = document.getElementById('chart_hours');
                        var chart_hours = new Chart(canvasHours, {
                            type: 'pie',
                            data: {
                                labels: {!! $producingAnalysis->charts('hours')->labels->toJson() !!},
                                datasets: [{
                                    data: {!! $producingAnalysis->charts('hours')->data->toJson() !!},
                                    backgroundColor: [
                                        'rgba(255,99,132,1)',
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 206, 86, 1)',
                                        'rgba(75, 192, 192, 1)',
                                        'rgba(153, 102, 255, 1)',
                                        'rgba(255, 159, 64, 1)'
                                    ],
                                    hoverBackgroundColor: []
                                }]
                            },
                            options: {
                                tooltips: {
                                    enabled: true,
                                    mode: 'single',
                                    callbacks: {
                                        label: function(tooltipItem, data) {
                                            var tooltipLabel = data.labels[tooltipItem.index];
                                            var tooltipData = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                                            return tooltipLabel + ' ' + tooltipData + '%';
                                        }
                                    }
                                }
                            }
                        });
                    </script>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    {!! Form::open(array('url' => 'dummy', 'class' => 'form-horizontal')) !!}
                    <h2>Statistiek</h2>
                    <div class="form-group">
                        {!! Form::label('', "Gemiddelde Moeilijkheid", array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-9"><p
                                    class="form-control-static">{{ $producingAnalysis->statistic('averageDifficulty') }}
                                (10 is het meest complex)</p></div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('', "Percentage moeilijke activiteiten", array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-9"><p
                                    class="form-control-static">{{ $producingAnalysis->statistic('percentageDifficultTasks') }}
                                % van je werkzaamheden vond je <b>Moeilijk</b></p></div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('', "Percentage zelfstandig werken", array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-9"><p
                                    class="form-control-static">{{ $producingAnalysis->statistic('percentageAloneHours') }}
                                % van de activiteiten voerde je Alleen uit</p></div>
                    </div>
                    {!! Form::close() !!}
                    <canvas id="chart_categories"></canvas>

                    <script>
                        var canvas_categories = document.getElementById("chart_categories");
                        var cat_chart = new Chart(canvas_categories, {
                            type: 'bar',
                            data: {
                                labels: {!! $producingAnalysis->charts('categories')->labels->toJson() !!},
                                datasets: [{
                                    label: 'Moeilijkheidsgraad op schaal van 1-10',
                                    data: {!! $producingAnalysis->charts('categories')->data->toJson() !!},
                                    backgroundColor: [
                                    ],
                                    borderColor: [
                                        'rgba(255,99,132,1)',
                                        'rgba(54, 162, 235, 1)',
                                        'rgba(255, 206, 86, 1)',
                                        'rgba(75, 192, 192, 1)',
                                        'rgba(153, 102, 255, 1)',
                                        'rgba(255, 159, 64, 1)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero:true
                                        }
                                    }]
                                }
                            }
                        });
                    </script>
                    <hr />
                </div>
            </div>

            <!-- Tips -->
            <div class="row">
                <div class="col-md-12">
                    <h2>Tips</h2>
                    @if($producingAnalysis->statistic('percentageAloneHours') > 75 && $producingAnalysis->statistic('percentageDifficultTasks') > 50)
                        <p>Je hebt {{ $producingAnalysis->statistic('percentageAloneHours') }}% van de tijd Alleen
                        gewerkt, en je vond {{ $producingAnalysis->statistic('percentageDifficultTasks') }}% van dit
                        zelfstandige werk Moeilijk. Je zou met je bedrijfsbegeleider kunnen bespreken op welke
                        manier je er samen voor kunt zorgen dat je eerder hulp of ondersteuning krijgt bij moeilijke
                        werkzaamheden.</p>
                    @endif
                    @if($producingAnalysis->statistic('percentageEasyHours') > 65)
                        <p>Je vindt maar liefst {{ $producingAnalysis->statistic('percentageAloneHours') }}% van je werk Makkelijk! Het lijkt erop dat je meer in je mars hebt. 
                        Je zou je bedrijfsbegeleider om meer uitdaging of een complexere opdracht kunnen vragen.</p>
                    @endif
                    @if($producingAnalysis->statistic('persentageMostDifficultCategory') > 75)
                        <p>Je vindt {{ $producingAnalysis->statistic('mostDifficultCategoryName') }} de moeilijkste categorie in jouw werk. Van alle activiteiten in deze categorie vind je {{ $producingAnalysis->statistic('persentageMostDifficultCategory') }}% Moeilijk. 
                        Je zou dit met je begeleider kunnen bespreken hoe je bij je werk in deze categorie ondersteund kunt worden. 
                        Misschien kun je vanuit de werkplek tips krijgen voor literatuur of personen die je hier verder mee kunnen helpen.</p>
                    @endif
                    @if($producingAnalysis->statistic('persentageAveragePersonDifficulty') < 20 && $producingAnalysis->statistic('persentageAveragePersonDifficulty') > 0)
                        <p>Wanneer jij samenwerk met {{ $producingAnalysis->statistic('averagePersonDifficultyName') }}, vind je jouw werk het makkelijkst. Ga eens voor jezelf na hoe deze persoon jou helpt, 
                        waardoor je meer kunt bereiken. En bedank deze persoon eens voor zijn of haar ondersteuning ;-)</p>
                    @endif
                </div>
            </div>

            @if(count($producingAnalysis->chains()) > 0)
                <div class="row">
                    <div class="col-md-12">
                        <h2>Detail</h2>
                        <p>Hieronder zie je alle series van opeenvolgende activiteiten in deze maand.</p>
                        <p>Je kunt hier op terugblikken en bekijken wat je in deze maand moeilijk vond en hoe je moeilijke situaties hebt overwonnen. Als je deze informatie wilt delen, zou je het kunnen bespreken bij een voortgangsgesprek met je bedrijfsbegeleider of je stagedocent.</p>
                    </div>
                    <table class="table blockTable col-md-12">
                        <thead class="blue_tile">
                        <tr>
                            <td>Datum</td>
                            <td>Activiteit</td>
                            <td>Aantal Uren</td>
                            <td>Afgerond?</td>
                            <td>Toon Detail</td>
                        </tr>
                        </thead>

                        <tbody>
                        <?php $count = 0; ?>
                        @foreach($producingAnalysis->chains() as $chain)
                            <tr class="{{ $count % 2 ? "even": "odd" }}-row">
                                <td>{{ $chain->dateText() }}</td>
                                <td>
                                    {{ $chain->descriptionText() }}
                                </td>
                                <td>
                                    {{ $chain->hoursText() }}
                                </td>
                                <td>
                                    {{ $chain->statusText() }}
                                </td>
                                <td>
                                    @if($chain->hasDetail())
                                        <a data-id="{{ $chain->first()->lap_id }}" href="#" class="expand-detail">Toon Detail</a>
                                    @else
                                        <p>N.V.T.</p>
                                    @endif
                                </td>
                            </tr>


                            @if($chain->count() >= 1)
                                <tr class="odd-row" id="detail-{{ $chain->first()->lap_id }}" style="display:none;" >
                                    <td colspan="5">
                                        <table class="table blockTable col-md-12">
                                            <tbody>
                                            <tr class="blue_tile">
                                                <td>Datum</td>
                                                <td>Omschrijving</td>
                                                <td>Complexiteit</td>
                                                <td>Tijd besteed</td>
                                                <td>Hulpbron</td>
                                                <td>Feedback</td>
                                                <td>Feedforward</td>
                                            </tr>
                                            @foreach($chain->raw() as $learningActProd)
                                                <?php
                                                $feedback = $learningActProd->feedback;

                                                ?>
                                                <tr>
                                                    <td>{{ date('d-m', strtotime($learningActProd->date)) }}<br/><br/></td>
                                                    <td>{{ $learningActProd->description }}</td>
                                                    <td>{{ ($feedback != null) ? $learningActProd->getDifficulty().": ".$feedback->notfinished : $learningActProd->getDifficulty() }}</td>
                                                    <td>{{ $learningActProd->getDurationString() }}</td>
                                                    <td>{{ $learningActProd->getResourceDetail() }}</td>
                                                    <td>{!! ($feedback != null) ? "Je was " . (($feedback->progress_satisfied == 2) ? "tevreden" : "niet tevreden") . " met het verloop van deze activiteit (<a href='".route("feedback-producing", array("id" => $feedback->fb_id))."'>Detail</a>)." : "Geen" !!}</td>
                                                    <td>{{ ($feedback != null) ? $feedback->nextstep_self : "" }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endif


                            <?php $count++; ?>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            @endif

        @endif

    </div>

@stop
