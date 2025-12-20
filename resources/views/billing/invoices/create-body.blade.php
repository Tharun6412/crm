<!-- Create invoice body -->

@if ($items->count() > 0)
    @php
        $total = 0;
    @endphp
    @csrf
    <div class="table-responsive">
        <table class="table table-bordered table-success mb-0">
            <thead class="table-success">
                <tr>
                    <th width="1%" nowrap>#</th>
                    <th width="1%" nowrap>S No</th>
                    <th>Item Code</th>
                    <th>Item name</th>
                    <th class="text-end">Qty</th>
                    <th class="text-end">Unit price</th>
                    <th class="text-end">Total price</th>
                </tr>
                <tbody>
                    @foreach ($items as $item)
                        @php
                            $total += $inv_items[$item->id]['qty'] * $item->basic;
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ url('bill/invoice/removeItem?item_id=' . $item->id) }}" class="btn btn-outline-danger btn-sm ajax-link-delete"><i class="bi bi-trash"></i></a>
                            </td>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->code }}</td>
                            <td>{{ $item->name }}</td>
                            <td class="text-end">{{ $inv_items[$item->id]['qty'] }}</td>
                            <td class="text-end">{{ $item->basic }}</td>
                            <td class="text-end">{{ numberFormat($inv_items[$item->id]['qty'] * $item->basic, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="6" class="text-end fw-semibold">Items Total</td>
                        <td class="text-end fw-semibold">
                            {{ numberFormat($total, 2) }}
                            <input type="hidden" name="items_total" id="items_total" value="{{ $total }}">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <td colspan="2">
                            <div class="input-group">
                                <select name="tax_type_id" id="tax_type_id" class="form-select">
                                    <option value="">Select Tax</option>
                                    @foreach ($tax_types as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="tax_value" id="tax_value" class="form-control text-end">
                                <label for="tax_value" class="input-group-text">%</label>
                            </div>
                        </td>
                        <td class="text-end fw-semibold"><span id="tax-total"></span></td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-end fw-semibold">Invoice Total</td>
                        <td class="text-end fw-semibold"><span id="inv-total"></span></td>
                    </tr>
                </tfoot>
            </thead>
        </table>
    </div>
    <div id="invoice-create-error" class="my-1"></div>
    <div class="text-end">
        <button type="button" onclick="invoiceBody()" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i>&nbsp;Refresh</button>
        <button type="submit" class="btn btn-success"><i class="bi bi-file-earmark-plus"></i>&nbsp;Create Invoice</button>
    </div>
@else
    <div class="alert alert-warning">No items!</div>
@endif
{{-- Scripts --}}
@include('scripts.ajax-link-delete', ['callback' => 'invoiceBody()'])
<script>
    $(function() {
        $("#tax_value").on('change', function(e){
            let items_total = $.isNumeric($("#items_total").val().trim()) ? parseFloat($("#items_total").val().trim()) : 0;
            let tax_value = $.isNumeric($("#tax_value").val().trim()) ? parseFloat($("#tax_value").val().trim()) : 0;
            let tax_total = ((items_total * tax_value) / 100);
            let inv_total = items_total + tax_total;
            $("#tax-total").html(tax_total.toFixed(2));
            $("#inv-total").html(inv_total.toFixed(2));
        });
    });
</script>