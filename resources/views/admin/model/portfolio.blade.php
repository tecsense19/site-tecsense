<div class="modal fade bs-example-modal-lg" id="portfolio-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.portfolio.store') }}" enctype="multipart/form-data" name="portfolio_model_form" id="portfolio_model_form">
                {!! csrf_field() !!}
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Portfolio</h4>
                    <button type="button" class="close portfolio_close_model" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="form-group">
                                <label>Image's</label>
                                <input type="file" class="form-control" name="portfolio_images[]" id="logos" multiple/>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="col-md-12 col-sm-12 d-flex flex-wrap" id="portfolio_images">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary portfolio_close_model" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>