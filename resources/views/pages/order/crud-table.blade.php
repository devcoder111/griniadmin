<div class="table-responsive">
    <table id="" class=" table-bordered upper-case" style="width:100%;max-width: 1480px;margin: 0 auto;">
        <thead>
            <tr>
                <th nowrap="nowrap" style="width: 2.1%;"></th>
                <th nowrap="nowrap" style="width: 2.1%;">Note</th>
                <th class="column_sort" style="cursor: pointer;width: 7.1%;" column-name="order_no"
                    column-order="{{  !empty($order_data['column_name']) && $order_data['column_name']=='order_no' ?  $order_data['column_sort_by_new']  : 'ASC'}}"
                    nowrap="nowrap">Order <i class="fas fa-sort text-right"></i></th>
                <th nowrap="nowrap" style="width: 8.5%;">SKU</th>
                <th nowrap="nowrap" style="width: 7.5%;">Barcode</th>
                <th nowrap="nowrap" style="width: 3.1%;">Qty</th>
                <th nowrap="nowrap" style="width: 7.1%;"> Link</th>

                <th nowrap="nowrap" style="width: 5.1%;">Ship Type</th>
                <th nowrap="nowrap" style="width: 7.1%">Name</th>
                <th class="column_sort" style="cursor: pointer;width: 7.1%" column-name="order_created"
                    column-order="{{  !empty($order_data['column_name']) && $order_data['column_name']=='order_created' ?  $order_data['column_sort_by_new']  : 'ASC'}}"
                    nowrap="nowrap">Date
                    <i class="fas fa-sort text-right"></i>
                </th>
                <th class="column_sort" style="cursor: pointer;width: 7.1%" column-name="order_status"
                    column-order="{{  !empty($order_data['column_name']) && $order_data['column_name']=='order_status' ?  $order_data['column_sort_by_new']  : 'ASC'}}"
                    nowrap="nowrap">Status
                    <i class="fas fa-sort text-right"></i>
                </th>
                <th nowrap="nowrap" style="width: 2.1%">Country</th>
                <th nowrap="nowrap" style="width: 2.1%">Risk</th>
                <th nowrap="nowrap" style="width: 2.1%">Paid?</th>
                <th nowrap="nowrap" style="width: 2.1%">Pickup Date</th>
                <th nowrap="nowrap" class="not-display">Item Status</th>
                <th class="not-display">Product Title</th>
                <!-- <th class="column_sort" style="cursor: pointer;" column-name="order_update_at" column-order="{{  !empty($order_data['column_name']) && $order_data['column_name']=='order_update_at' ?  $order_data['column_sort_by_new']  : 'ASC'}}" >Updated at
                <i class="fas fa-sort text-right"></i>
                </th> -->
                <!-- <th>Action</th> -->
            </tr>
        </thead>
        <tbody id="order_data" style="background-color: white;">

            @if(! $listData->isEmpty())

            @foreach($listData as $list)
            @php
            $ship_type_bg="";

            if((!empty($list['ship_type'])) && ( stristr( $list['ship_type'], 'Australia Post - Standard' ) == false) &&
            ($list['ship_type'] != 'NSW Warehouse'))
            $ship_type_bg='#03a9f4';
            elseif((!empty($list['ship_type'])) && ($list['ship_type'] == 'NSW Warehouse'))
            $ship_type_bg='orange';
            else
            $ship_type_bg="";

            @endphp

            @php
            $order_row_bg="";
            if(!empty($list['order_row_color']))
            $order_row_bg=$list['order_row_color'];

            elseif((!empty($list['order_status'])) && ($list['order_status'] == 'fulfilled'))
            $order_row_bg='#7dfa7d';
            else
            $order_row_bg="";
            @endphp

            <tr class="">

                <td nowrap="nowrap"
                    class="{{  !empty($list['note']) && $list['payment_status']=='refunded' ?  'text-del'  : ''}}"
                    style="">
                    <input type="checkbox" class="order_check" id="chkRow1" style="width: 15px;height:15px;"
                        data-id="{{ $list['id'] }}" />
                    <!--    <a class="row_hightlight" title="Highlight order" data-field="note_field" data-id="{{ $list['id'] }}"><i class="fa fa-circle-o" style="font-size:14px;margin-top: 5px;"></i></a> -->
                </td>

                <td nowrap="nowrap"
                    class="{{  !empty($list['note']) && $list['payment_status']=='refunded' ?  'text-del'  : ''}}">
                    <a title="{{ isset($list['note']) ? $list['note'] :'' }}">{{ isset($list['note']) ? mb_strimwidth(trim($list['note']), 0, 28, "...") :'-' }}
                    </a>
                    &nbsp;
                    <a class="edit_order" data-field="note_field" data-id="{{ $list['id'] }}"><i class="fa fa-edit"
                            style="font-size:14px;margin-top: 5px;"></i></a>
                </td>
                <td nowrap="nowrap"
                    class="{{  !empty($list['payment_status']) && $list['payment_status']=='refunded' ?  'text-del'  : ''}}">
                    {{ isset($list['order_no']) ? trim($list['order_no']) :'-' }}
                </td>
                <td nowrap="nowrap"
                    class="{{  !empty($list['sku']) && $list['payment_status']=='refunded' ?  'text-del'  : ''}}">
                    {{ isset($list['sku']) ? $list['sku'] :'-' }}
                </td>

                <td nowrap="nowrap"
                    class="{{  !empty($list['barcode']) && $list['payment_status']=='refunded' ?  'text-del'  : ''}}">
                    {{ isset($list['barcode']) ? $list['barcode'] :'-' }}
                </td>

                <td nowrap="nowrap"
                    class="{{  !empty($list['quantity']) && $list['payment_status']=='refunded' ?  'text-del'  : ''}}">
                    {{ isset($list['quantity']) ? trim($list['quantity']) :'' }}
                </td>

                <td nowrap="nowrap">
                    <a href="{{ isset($list['image']) ? $list['image'] : '#'}}"
                        target="_blank">{{ isset($list['image']) ? substr($list['image'], 0, 19)  : '-'}}
                    </a>
                </td>



                <td nowrap="nowrap"
                    class="{{  !empty($list['ship_type']) && $list['payment_status']=='refunded' ?  'text-del'  : ''}}"
                    title="{{ isset($list['ship_type']) ? $list['ship_type'] : ''}}">
                    {{ isset($list['ship_type']) ? mb_strimwidth(trim($list['ship_type']), 0, 28, "...") :'-' }}</td>

                <td nowrap="nowrap">{{ isset($list['customer_name']) ? trim($list['customer_name']) :'-' }}</td>

                <td nowrap="nowrap"
                    class="{{  !empty($list['order_created']) && $list['payment_status']=='refunded' ?  'text-del'  : ''}}">
                    {{ isset($list['order_created']) ? explode("+",$list['order_created'])[0] :'-' }}
                </td>

                <td nowrap="nowrap">{{ isset($list['item_status']) ? $list['item_status'] :'-' }}&nbsp;
                    @if(empty($list['fulfillment_id']) && ($list['item_status'] == "fulfilled"))

                    <a title="Order fulfilled on Shopify" data-field="order_status_field" data-id="{{ $list['id'] }}"><i
                            class="fa fa-lock" style="font-size:14px;margin-top: 5px;"></i></a>
                    @elseif(empty($list['payment_status']) && ($list['payment_status'] == "refunded")))
                    <a title="Item Removed From Order" data-field="order_status_field" data-id="{{ $list['id'] }}"><i
                            class="fa fa-lock" style="font-size:14px;margin-top: 5px;"></i></a>
                    @else
                    <a class="edit_order" data-field="order_status_field" data-id="{{ $list['id'] }}"><i
                            class="fa fa-edit" style="font-size:14px;margin-top: 5px;"></i></a>

                    @endif
                </td>

                <td nowrap="nowrap"
                    class="{{  !empty($list['country']) && $list['payment_status']=='refunded' ?  'text-del'  : ''}}">
                    {{ isset($list['country']) ? $list['country'] :'-' }}
                </td>

                <td style="{{  !empty($list['risk']) && $list['risk'] == 'Medium' ?  'background-color:yellow;'  : (!empty($list['risk']) && $list['risk'] == 'High' ?  'background-color:#fe8c8c;'  : '')}}"
                    nowrap="nowrap"
                    class="{{  !empty($list['risk']) && $list['payment_status']=='refunded' ?  'text-del'  : ''}}">
                    {{ isset($list['risk']) ? $list['risk'] :'-' }}
                </td>

                <td nowrap="nowrap">
                    {{ isset($list['payment_status']) ? $list['payment_status'] :'-' }}
                </td>
                <td nowrap="nowrap">{{ isset($list['pickup_date']) ? $list['pickup_date'] :'-' }}
                </td>









                <td nowrap="nowrap" class="not-display">
                    <a title="{{ isset($list['product_title']) ? $list['product_title'] :'' }}">{{ isset($list['product_title']) ? substr($list['product_title'], 0, 40) :'-' }}
                    </a>
                </td>
                <td nowrap="nowrap" class="not-display">{{ isset($list['order_status']) ? $list['order_status'] :'-' }}
                </td>











                <!--   <td>{{ isset($list['order_status']) ? $list['order_status'] :'-' }} </td> -->

                <!--  <td>{{ isset($list['order_update_at']) ? $list['order_update_at'] :'-' }} </td> -->
                <!--  <td> <button class="edit_order btn btn-primary" data-id="{{ $list['id'] }}"><i class="fa fa-edit" style="font-size:14px"></i></button></td> -->
            </tr>
            @endforeach

            @else
            <tr>
                <td colspan="14" style="text-align:center;color:#b91010;">No Result Found</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-center">
    {{ $listData->links('pagination::bootstrap-4') }}
</div>
{{--
    <!-- <button  class="btn lni lni-trash edit" data-id="{{ $list['id'] }}">
Edit
</button>

<button class="btn lni lni-trash delete" data-id="{{ $list['id'] }}">
    Delete
</button> -->
--}}