<div class="modal-header">
    <h4 class="modal-title">Stap 2: Kies entiteit en relaties</h4>
</div>
<div class="modal-body" style="height: 450px">
    <form id="wizard-form">
        <div class="form-group">
            <label for="analysis_entity">Entiteit</label>
            <select class="form-control" name="analysis_entity" id="analysis_entity">
                @foreach($models as $model)
                    <option {{ isset($data['analysis_entity']) && $data['analysis_entity'] == $model ? "selected" : "" }} value="{{ $model }}">{{ Lang::get('querybuilder.'.$model) }}</option>
                @endforeach
            </select>
        </div>
        <p style="font-weight: bold;">Relaties</p>
        <div class="relations">
            @foreach($relations as $relation => $type)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="analysis_relation[]" id="analysis_relations_{{ $relation }}" value="{{ $relation }}"
                            {{ isset($data['analysis_relation']) && in_array($relation, $data['analysis_relation']) ? "checked" : "" }}>
                    <label class="form-check-label" for="analysis_relations_{{ $relation }}">
                        {{ $relation }}
                    </label>
                </div>
            @endforeach
        </div>
    </form>
    <script>

        $(document).ready(function() {
            $('#analysis_entity').on('change', function(data) {
                $.getJSON( "/dashboard/builder/relations/" + $(this).val(), function( data ) {
                    var items = "";
                    $.each( data, function( key, val ) {
                        items += ` <div class="form-check">
                <input class="form-check-input" type="checkbox" name="analysis_relation[]" id="analysis_relations_${key}" value="${key}">
                <label class="form-check-label" for="analysis_relations_${key}">
                    ${val}
                            </label>
                        </div>`;
                    });

                    $('.relations').html(items);
                });
            });
        });
    </script>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-seconday" onclick="Wizard.step(1);">Vorige</button>
    <button type="button" class="btn btn-primary" onclick="Wizard.step(3);">Volgende</button>
</div>