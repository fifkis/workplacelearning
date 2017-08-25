@extends('layout.HUdefault')
@section('title')
    Activiteiten
@stop
@section('content')
    <div class="container-fluid">
        <script>
            $(document).ready(function() {
                // Add new resource person or material
                (function() {
                    $('#new-rp-hidden').hide();
                    $('#new-rm-hidden').hide();
                    $('#res_material_detail').hide();

                    $('[name="res_person"]').click(function() {
                        if ($('#new_rp').is(':checked')) {
                            $('#new-rp-hidden').show();
                        } else {
                            $('#new-rp-hidden').hide();
                        }
                    });

                    $('[name="res_material"]').click(function() {
                        if ($('#new_rm').is(':checked')) {
                            $('#new-rm-hidden').show();
                        } else {
                            $('#new-rm-hidden').hide();
                        }

                        if ($('#rm_none').is(':checked')) {
                            $('#res_material_detail').hide();
                        } else {
                            $('#res_material_detail').show();
                        }
                    });
                })();

                // Help Text
                (function() {
                    $("#help-text").hide();

                    $(".expand-click").click(function(){
                        $(".cond-hidden").hide();
                        $(this).siblings().show();
                        $("#cond-select-hidden").hide();
                        $("#rp_id").trigger("change");
                    });

                    $("#help-click").click(function(){
                        $('#help-text').slideToggle('slow');
                    });
                })();

                // Tooltips
                (function() {
                    $('[data-toggle="tooltip"]').tooltip();
                })();
            });
        </script>
        <div class="row">
            <div class="col-md-12 well">
                <h4 id="help-click" data-collapsed-icon="arrow-d" data-expanded-icon="arrow-u"><i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i> Hoe werkt deze pagina?</h4>
                <div id="help-text">
                    <ol>
                        <li>Kies een datum waarop het leermoment plaatsvond. De datum mag niet in de toekomst liggen.</li>
                        <li>Vul een omschrijving in van de situatie waarin het leermoment plaatsvond.</li>
                        <li>Geef aan op welk moment van de dag het leermoment plaatsvond.</li>
                        <li>Geef aan met wie je dit leermoment meemaakte of dat je het alleen hebt ervaren.</li>
                        <li>Geef aan welke theorie je gebruikte tijdens dit leermoment. Geef het type bron en een beschrijving op.</li>
                        <li>Geef dan aan wat je precies geleerd hebt van dit moment en wat de volgende stap voor jou gaat zijn. Daarnaast kun je nog aangeven wat je eventueel voor ondersteuning daarbij nodig hebt van je stageplek of van de HU (let op: dit wordt niet doorgegeven aan de betreffende personen, maar wel opgeslagen in het systeem, dus je kunt het later wel weer terugvinden).</li>
                        <li>Tenslotte kun je dit leermoment nog koppelen aan een van je leervragen (of niet) en aan een van de competenties van je opleiding.</li>
                    </ol>
                </div>
            </div>
        </div>
        {{ Form::open(array('url' => route('process-acting-create'), 'class' => 'form-horizontal')) }}
            <div class="row well">
                <div class="col-md-2 form-group">
                    <h4>Activiteit</h4>
                    <div class='input-group date fit-bs' id='date-deadline'>
                        <input style="z-index:1;" id="datum" name="date" type='text' class="form-control" value="{{ (!is_null(old('datum'))) ? date('d-m-Y', strtotime(old('datum'))) : date('d-m-Y') }}"/>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                    <h4>Situatie</h4>
                    <div>
                        <textarea id="description" class="form-control fit-bs" name="description" required oninput="this.setCustomValidity('')" maxlength="1000" pattern="[ 0-9a-zA-Z-_,.?!*&%#()'/]{3,1000}" oninvalid="this.setCustomValidity('{{ Lang::get('elements.general.mayonlycontain') }} 0-9a-zA-Z-_,.?!*&%#()'\"')" rows="16" cols="19">{{ old('description') }}</textarea>
                        <a data-target-text="#description" data-target-title="{{ ucfirst(trans('process_export.situation')) }}" class="canBeEnlarged">{{ trans('process.enlarge') }}</a>
                    </div>

                </div>
                <div class="col-md-2 form-group buttons">
                    <h4>Wanneer? <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ trans('tooltips.acting_when') }}"></i></h4>
                    @foreach ($timeslots as $key => $value)
                        <label><input type="radio" name="timeslot" value="{{ $value->timeslot_id }}" {{ (old('timeslot') != null && old('timeslot') == $value->timeslot_id) ? "checked" : ($key == 0) ? "checked" : null }} /><span>{{ $value->timeslot_text }}</span></label>
                    @endforeach
                </div>
                <div class="col-md-2 form-group buttons">
                    <h4>Met wie? <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ trans('tooltips.acting_with') }}"></i></h4>
                    @foreach ($resPersons as $key => $value)
                        <label><input type="radio" name="res_person" value="{{ $value->rp_id }}" {{ (old('res_person') != null && old('res_person') == $value->rp_id) ? "checked" : ($key == 0) ? "checked" : null }} /><span>{{ $value->person_label }}</span></label>
                    @endforeach
                    <div>
                        <label><input type="radio" name="res_person" id="new_rp" value="new" {{ (old('res_person') == 'new') ? 'checked' : null }}><span class="new">Anders<br />(Toevoegen)</span></label>
                        <input id="new-rp-hidden" type="text" name="new_rp" value="{{ old('new-rp-hidden') }}" placeholder="Omschrijving" oninput="this.setCustomValidity('')" pattern="[ 0-9a-zA-Z,./\\]{1,50}" oninvalid="this.setCustomValidity('{{ Lang::get('elements.general.mayonlycontain') }} 0-9a-zA-Z')" />
                    </div>
                </div>
                <div class="col-md-2 form-group buttons">
                    <h4>Met welke theorie? <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ trans('tooltips.acting_theory') }}"></i></h4>
                    <label><input type="radio" name="res_material" id="rm_none" value="none" {{ (old('res_material') == 'none') ? 'checked' : 'checked' }}><span>Geen</span></label>
                    @foreach ($resMaterials as $key => $value)
                        <label><input type="radio" name="res_material" value="{{ $value->rm_id }}" {{ (old('res_material') != null && old('res_material') == $value->rm_id) ? "checked" : null }} /><span>{{ $value->rm_label }}</span></label>
                    @endforeach
                    <input type="text" name="res_material_detail" id="res_material_detail" placeholder="Beschrijving bron" value="{{ old('res_material_detail') }}" />
                    <label><input type="radio" name="res_material" id="new_rm" value="new" {{ (old('res_material') == 'new') ? 'checked' : null }}><span class="new">Anders<br />(Toevoegen)</span></label>
                    <input type="text" name="new_rm" id="new-rm-hidden" value="{{ old('new_rm') }}" placeholder="Omschrijving" oninput="this.setCustomValidity('')" pattern="[ 0-9a-zA-Z,./\\]{1,50}" oninvalid="this.setCustomValidity('{{ Lang::get('elements.general.mayonlycontain') }} 0-9a-zA-Z')" />
                </div>
                <div class="col-md-2 form-group">
                    <div>
                        <h4>Wat heb je geleerd?<br />Wat is het vervolg? <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ trans('tooltips.acting_learned') }}"></i></h4>
                        <textarea id="learned" class="form-control fit-bs" name="learned" required oninput="this.setCustomValidity('')" maxlength="1000" pattern="[ 0-9a-zA-Z-_,.?!*&%#()'/]{3,3}" oninvalid="this.setCustomValidity('{{ Lang::get('elements.general.mayonlycontain') }} 0-9a-zA-Z-_,.?!*&%#()'\"')" rows="5" cols="19">{{ old('learned') }}</textarea>
                        <a data-target-text="#learned" data-target-title="Wat heb je geleerd?" class="canBeEnlarged">{{ trans('process.enlarge') }}</a>
                    </div>
                    <div>
                        <h4>Wat heb je hierbij nodig van je werkplek? <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ trans('tooltips.acting_required_wp') }}"></i></h4>
                        <textarea id="support_wp" max-length="500" class="form-control fit-bs" name="support_wp" oninput="this.setCustomValidity('')" pattern="[ 0-9a-zA-Z-_,.?!*&%#()'/]{3,500}" oninvalid="this.setCustomValidity('{{ Lang::get('elements.general.mayonlycontain') }} 0-9a-zA-Z-_,.?!*&%#()'\"')" rows="5" cols="19">{{ old('support_wp') }}</textarea>
                        <a data-target-text="#support_wp" data-target-title="Wat heb je hierbij nodig van je werkplek?" class="canBeEnlarged">{{ trans('process.enlarge') }}</a>
                    </div>
                    <div>
                        <h4>Wat heb je hierbij nodig van de HU? <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ trans('tooltips.acting_required_ep') }}"></i></h4>
                        <textarea id="support_ed" maxlength="500" class="form-control fit-bs" name="support_ed" oninput="this.setCustomValidity('')" pattern="[ 0-9a-zA-Z-_,.?!*&%#()'/]{3,500}" oninvalid="this.setCustomValidity('{{ Lang::get('elements.general.mayonlycontain') }} 0-9a-zA-Z-_,.?!*&%#()'\"')" rows="5" cols="19">{{ old('support_ed') }}</textarea>
                        <a data-target-text="#support_ed" data-target-title="Wat heb je hierbij nodig van de HU?" class="canBeEnlarged">{{ trans('process.enlarge') }}</a>

                    </div>
                </div>
                <div class="col-md-2 form-group">
                    <div>
                        <h4>Leervraag <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ trans('tooltips.acting_learninggoal') }}"></i></h4>
                        <select name="learning_goal" class="form-control fit-bs">
                            @foreach ($learningGoals as $key => $value)
                                <option value="{{ $value->learninggoal_id }}" {{ (old('learning_goal') == $value->learninggoal_id) ? 'selected' : null }}>{{ $value->learninggoal_label }}</option>
                            @endforeach
                        </select>
                        <h4>Competentie <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="{{ trans('tooltips.acting_competence') }}"></i></h4>
                        <select name="competence" class="form-control fit-bs">
                            @foreach ($competencies as $value)
                                <option value="{{ $value->competence_id }}" {{ (old('competence') == $value->competence_id) ? 'selected' : null }}>{{ $value->competence_label }}</option>
                            @endforeach
                        </select>
                        @if($competenceDescription !== null)
                            <h5>
                                <a href="{{ $competenceDescription->download_url }}">{{ Lang::get('elements.competences.competencedetails') }}</a>
                            </h5>
                        @endif
                    </div>
                    <div>
                        <input type="submit" class="btn btn-info" style="margin: 44px 0 0 30px;" value="Save" />
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('#date-deadline').datetimepicker({
                        locale: 'nl',
                        format: 'DD-MM-YYYY',
                        minDate: "{{ date('Y-m-d', strtotime("-3 week")) }}",
                        maxDate: "{{ date('Y-m-d', strtotime("now")) }}",
                        useCurrent: false,
                    });
                }).on('dp.change', function(e) {
                    $('#datum').attr('value', moment(e.date).format("DD-MM-YYYY"));
                });
            </script>
        {{ Form::close() }}
        <div class="row">
            <script>
                window.activities = {!! $activitiesJson !!};
                window.exportTranslatedFieldMapping = {!! $exportTranslatedFieldMapping !!};
            </script>

            <div id="ActivityActingProcessTable" class="__reactRoot col-md-12"></div>
        </div>

        {{-- Modal used for enlarging fields --}}
        <div class="modal fade" id="enlargedModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-sm"  role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <textarea rows="10" class="form-control"></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Sluiten</button>
                        <a type="button" class="btn btn-primary" id="enlargedTextareaSave">Bevestigen</a>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <script>
            var enlargedModal = $('#enlargedModal');
            var title = $('.modal-title');
            var textarea = $(enlargedModal).find('textarea');
            var returnTarget = undefined;
            $('.canBeEnlarged').click(function() {
                $(enlargedModal).modal('toggle');
                var returnTargetId = $(this).data('target-text');

                returnTarget = $(this).parent().find('' + returnTargetId);
                $(textarea).attr('maxlength', $(returnTarget).attr('maxlength'));
                $(textarea).val($(returnTarget).val());
                $(title).text($(this).data('target-title'));
                $(textarea).focus();
            });
            $('#enlargedTextareaSave').click(function() {
                if(returnTarget === undefined) return;

                $(returnTarget).val($(textarea).val());
                $(enlargedModal).modal('hide')
            });
        </script>
    </div>
@stop
