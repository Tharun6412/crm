<div class="row">
    <div class="table table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Description</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Base Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->price }}</td>
                    <td>{{ request()->quantity}}</td>
                    <td>{{ ($item->price * request()->quantity) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-4 offset-md-6 mt-0">
        <div class="table table-responsive">
            <table class="table table-bordered" id="tax-columns">
                <tr id="tax-type-row">
                    <td>Tax Type</td>
                    <td>:</td>
                    <td>
                        <select class="form-select" name="tax_type" id="tax_type" onchange="addTaxColumns(this.value)">
                            <option value="">Select Tax Type</option>
                            @foreach ($tax_types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<script>
    function addTaxColumns(val) {

        // Remove old tax rows
        document.querySelectorAll('.tax-row').forEach(r => r.remove());

        if (!val) return;

        const taxTypeRow = document.getElementById('tax-type-row');

        function createTaxRow(label, name) {
            const tr = document.createElement('tr');
            tr.classList.add('tax-row');

            const td1 = document.createElement('td');
            td1.textContent = label;

            const td2 = document.createElement('td');
            td2.textContent = ':';

            const td3 = document.createElement('td');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = name;
            input.id = name;
            input.className = 'form-control';

            td3.appendChild(input);
            tr.append(td1, td2, td3);

            return tr;
        }

        if (val == 1) {
            taxTypeRow.after(createTaxRow('VAT', 'vat'));
        }
        else if (val == 2) {
            const cgst = createTaxRow('CGST', 'cgst');
            const sgst = createTaxRow('SGST', 'sgst');

            taxTypeRow.after(cgst);
            cgst.after(sgst);
        }
        else if (val == 3) {
            taxTypeRow.after(createTaxRow('IGST', 'igst'));
        }
        else if (val == 4) {
            taxTypeRow.after(createTaxRow('CST', 'cst'));
        }
    }
</script>

