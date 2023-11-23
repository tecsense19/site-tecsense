<div class="row clearfix">
    @foreach($portfolioDataArr as $port)
        @php $imagePath = url('/') . '/' . $port->image_path; @endphp
        <div class="col-lg-4 col-md-6 col-sm-12 mb-30">
            <div class="da-card">
                <div class="da-card-photo">
                    <img src="{{ $imagePath }}" alt="">
                    <div class="da-overlay">
                        <div class="da-social">
                            <ul class="clearfix">
                                <li><a href="#" class="portfolio-delete" data-id="{{ $port->id }}"><i class="fa fa-trash"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- <div class="da-card-content">
                    <h5 class="h5 mb-10">Don H. Rabon</h5>
                    <p class="mb-0">Lorem ipsum dolor sit amet</p>
                </div> -->
            </div>
        </div>
    @endforeach
</div>