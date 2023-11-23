<table class="table table-bordered">

    <thead>

        <tr>

            <th scope="col" class="text-center" style="width: 4%">#</th>

            <th scope="col" class="text-center" style="width: 16%">Service</th>

            <th scope="col" class="text-center" style="width: 10%">Title</th>

            <th scope="col" class="text-center" style="width: 16%">Action</th>


        </tr>

    </thead>

    <tbody>

        @if(count($featuresDataArr) > 0)

            @php $i = 1; @endphp

            @foreach($featuresDataArr as $singleRec)
           
                @php 
                    $service_detail_Url = $singleRec->features_detail_pic ? url('/').'/'.$singleRec->features_detail_pic : ''; 
                @endphp
                <tr>

                    <td rowspan="2" scope="row" class="text-center">{{ $i }}</td>

                    @foreach($singleRec->serviceTitle as $title)
                    <td scope="row" class="text-center">{{ $title->service_title }}</td>
                    @endforeach

                    <td scope="row" class="text-center">{{ $singleRec->text }}</td> 

                    <td scope="row" class="text-center">

                        <div class="d-flex justify-content-center">

                            <div class="feature-detail-edit" style="cursor: pointer;" data-features-detail="{{ $singleRec }}">

                                <i class="icon-copy fa fa-edit m-2" aria-hidden="true"></i>

                            </div>

                            <div class="feature-detail-delete" style="cursor: pointer;" data-id="{{ $singleRec->id }}"><i class="icon-copy fa fa-trash m-2" aria-hidden="true"></i></div>

                        </div>

                    </td>

                </tr>
                <tr>
                </tr>

                <tr>

                    <td colspan="10" scope="row" class="text-center">

                        <div class="d-flex">

                            @foreach($singleRec->featuresDetailImages as $simg)

                                @php $imgPath = $simg->image_path ? url('/').'/'.$simg->image_path : ''; @endphp

                                <div class="flex-wrap text-center mx-2">

                                    @if($imgPath)

                                        <div><img src="{{ $imgPath }}" style="width: 25px; height: 25px;"/></div>

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

                <td colspan="6">Services List Not Found.</td>

            </tr>

        @endif

    </tbody>

</table>

<!--  -->