<div class="table-responsive">
    <table id="" class=" table-bordered upper-case" style="width:100%;max-width: 1480px;margin: 0 auto;">
        <thead>
            <tr>
                <th nowrap="nowrap" style="width: 2.1%;"></th>
                <th nowrap="nowrap" style="width: 40%;">Product</th>
                <th nowrap="nowrap" style="width: 12%;">Today</th>
                <th nowrap="nowrap" style="width: 12%;">Tomorrow</th>
                <th nowrap="nowrap" style="width: 12%;">In Two Days</th>
                <th nowrap="nowrap" style="width: 12%;">1 Week</th>
                <!-- <th nowrap="nowrap" style="width: 12%;">Total</th> -->


                <!-- <th class="column_sort" style="cursor: pointer;" column-name="order_update_at" column-order="{{  !empty($order_data['column_name']) && $order_data['column_name']=='order_update_at' ?  $order_data['column_sort_by_new']  : 'ASC'}}" >Updated at
                <i class="fas fa-sort text-right"></i>
                </th> -->
                <!-- <th>Action</th> -->
            </tr>
        </thead>
        <tbody id="order_data" style="background-color: white;">

            @if(!empty($listData ))

            @foreach($listData as $list)


            <tr class="">
                <td nowrap="nowrap">
                </td>
                <td nowrap="nowrap">
                    {{$list->title}}
                </td>
                <td nowrap="nowrap">
                    {{$list->todayCounts}}
                </td>
                <td nowrap="nowrap">
                    {{$list->tomorrowCounts}}
                </td>
                <td nowrap="nowrap">
                    {{$list->inTwoDayCounts}}

                </td>
                <td nowrap="nowrap">
                    {{$list->inweekCounts}}
                </td>




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