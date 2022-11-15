<table 
    id="" 
    class="table table-striped table-bordered upper-case" style="width:100%">
            <thead>
                <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Image</th>
                <th>Actions</th>  
                </tr>
            </thead>
        <tbody>
        @if(! $listData->isEmpty())
                @foreach($listData as $list)
                <tr>
                    <td>
                        {{ 
                            isset($list['title']) ? 
                            $list['title']
                            :'-' 
                        }}
                    </td>
                    <td>
                        {{ 
                            isset($list['description']) ? 
                            $list['description']
                            :'-' 
                        }}
                    </td>
                    <td>
                        {{ 
                            isset($list['image']) ? 
                            $list['image']
                            :'-' 
                        }}
                    </td>
                        
                    <td>
                    <button  class="btn lni lni-trash edit" data-id="{{ $list['id'] }}">
                        Edit
                    </button>

                    <button  class="btn lni lni-trash delete" data-id="{{ $list['id'] }}">
                        Delete
                    </button>
                    </td>
                </tr>
                @endforeach
            @endif
            </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $listData->links('pagination::bootstrap-4') }}
    </div>
    