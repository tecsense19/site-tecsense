<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col" class="text-center" style="width: 4%">#</th>
            <th scope="col" class="text-center" style="width: 30%">Title</th>
            <th scope="col" class="text-center" style="width: 30%">Date</th>
            <th scope="col" class="text-center" style="width: 30%">Category</th>
            <th scope="col" class="text-center" style="width: 6%">Action</th>
        </tr>
    </thead>
    <tbody>
        @if(count($blogDataArr) > 0)
            @php $i = 1; @endphp
            @foreach($blogDataArr as $singleRec)
                <tr>
                    <td scope="row" class="text-center">{{ $i }}</td>
                    <td scope="row" class="text-center">{{ $singleRec->blog_title }}</td>
                    <td scope="row" class="text-center">{{ date('d-m-Y', strtotime($singleRec->created_at)) }}</td>
                    <td scope="row" class="text-center">{{ $singleRec->blog_category }}</td>
                    <td scope="row" class="text-center">
                        <div class="d-flex justify-content-center">
                            <div class="blog-edit" style="cursor: pointer;" data-blog="{{ $singleRec }}">
                                <i class="icon-copy fa fa-edit m-2" aria-hidden="true"></i>
                            </div>
                            <div class="blog-delete" style="cursor: pointer;" data-id="{{ $singleRec->id }}"><i class="icon-copy fa fa-trash m-2" aria-hidden="true"></i></div>
                        </div>
                    </td>
                </tr>
            @php $i++; @endphp
            @endforeach
        @else
            <tr scope="row" style="text-align: center;">
                <td colspan="5">Blog List Not Found.</td>
            </tr>
        @endif
    </tbody>
</table>
{!! $blogDataArr->links('pagination') !!}