<table class="table table-bordered">
    <thead>
        <tr>
            <th scope="col" class="text-center" style="width: 4%">#</th>
            <th scope="col" class="text-center" style="width: 10%">Profile Pic</th>
            <th scope="col" class="text-center" style="width: 10%">Full Name</th>
            <th scope="col" class="text-center" style="width: 10%">Country</th>
            <th scope="col" class="text-center" style="width: 50%">Client Description</th>
            <th scope="col" class="text-center" style="width: 16%">Action</th>
        </tr>
    </thead>
    <tbody>
        @if(count($testimonialDataArr) > 0)
            @php $i = 1; @endphp
            @foreach($testimonialDataArr as $singleRec)
                <tr>
                    <td rowspan="2" scope="row" class="text-center">{{ $i }}</td>
                    <td scope="row" class="text-center">@if($singleRec->profile_pic) <img src="{{ url('/') . '/' . $singleRec->profile_pic }}" style="width: 50px; height: 50px;"/> @else - @endif</td>
                    <td scope="row" class="text-center">{{ $singleRec->full_name }}</td>
                    <td scope="row" class="text-center">{{ $singleRec->country }}</td>
                    <td scope="row" class="text-left">{{ $singleRec->client_description }}</td>
                    <td scope="row" class="text-center">
                        <div class="d-flex justify-content-center">
                            <div class="testimonial-edit" style="cursor: pointer;" data-testimonial="{{ $singleRec }}">
                                <i class="icon-copy fa fa-edit m-2" aria-hidden="true"></i>
                            </div>
                            <div class="testimonial-delete" style="cursor: pointer;" data-id="{{ $singleRec->id }}"><i class="icon-copy fa fa-trash m-2" aria-hidden="true"></i></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="10" scope="row" class="text-center">
                        <div class="d-flex">
                            @foreach($singleRec->testimonialImages as $simg)
                                @php $imgPath = $simg->image_path ? url('/').'/'.$simg->image_path : ''; @endphp
                                <div class="flex-wrap text-center mx-2">
                                    @if($imgPath)
                                        <div><img src="{{ $imgPath }}" style="width: 100%; height: 25px;"/></div>
                                    @endif
                                    <div>{{ $simg->title }}</div>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>
                @php $i++; @endphp
            @endforeach
        @else
            <tr scope="row" style="text-align: center;">
                <td colspan="6">Testimonial List Not Found.</td>
            </tr>
        @endif
    </tbody>
</table>
{!! $testimonialDataArr->links('pagination') !!}