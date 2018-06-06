<div class="modal-header">
    <h4 class="modal-title">Stap 3: filters, sortering en groepering</h4>
</div>
<div class="modal-body" style="height: 450px">
    <form id="wizard-form">
        <label for="analysis_entity">Gegevens</label>
        @for($i=0; $i<2; $i++)
        <div class="form-group row">
            <!--div class="col-md-1" style="width: 25px;"><a href="#" style="line-height: 34px; text-decoration: none;">X</a></div-->
            <div class="col-md-2">
                <select class="form-control" name="query_data[{{ $i }}][table]" id="analysis_entity">
                    <option>Table</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="query_data[{{ $i }}][column]" id="analysis_entity">
                    <option>Column</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="query_data[{{ $i }}][type]" id="analysis_entity">
                    <option>Column</option>
                    <option>Sum</option>
                    <option>Count</option>
                </select>
            </div>
        </div>
        @endfor
        <!--a style="font-size: 20px; text-decoration: none; display: block;" href="#">+</a-->
        <label for="analysis_entity">Filters</label>
        <div class="form-group row">
            <div class="col-md-1" style="width: 25px;"><a href="#" style="line-height: 34px; text-decoration: none;">X</a></div>
            <div class="col-md-2">
                <select class="form-control" name="query_filter[1][]" id="analysis_entity">
                    <option>Table Filter</option>
                    <option>Between</option>
                    <option>Equals</option>
                    <option>Larger than</option>
                    <option>Smaller than</option>
                    <option>Group by</option>
                    <option>Limit</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="query_filter[1][]" id="analysis_entity">
                    <option>Table</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="query_filter[1][]" id="analysis_entity">
                    <option>Column</option>
                </select>
            </div>
            <div class="col-md-2">
                <!--select class="form-control" name="query_data[]" id="analysis_entity">
                    <option>Value</option>
                </select-->
                <input name="query_filter[1][]" class="form-control" placeholder="Value">
            </div>
        </div>
        <a style="font-size: 20px; text-decoration: none; display: block;" href="#">+</a>
    </form>
    <div style="
    position: absolute;
    right:  0;
    top:  0;
    bottom: 0;
    width: 25%;
    border-left: 1px solid #ddd;
    background: #fff;">
        <table class="table table-striped">
            <thead>
                <th scope="col">Kolom 1</th>
                <th scope="col">Kolom 2</th>
            </thead>
            <tbody>
                @for($i = 0; $i < 10; $i++)
                <tr>
                    <td>Waarde 1</td>
                    <td>Waarde 2</td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-seconday" onclick="Wizard.step(2);">Vorige</button>
    <button type="button" class="btn btn-primary" onclick="Wizard.step(4);">Volgende</button>
</div>