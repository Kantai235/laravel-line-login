@inject('model', '\App\Domains\Chat\Models\MessageKeywords')

@extends('backend.layouts.app')

@section('title', __('Create Reply'))

@push('before-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            var keywords_container = $(".keywords_container");
            var keywords_add_button = $(".keywords_add_button");

            $(keywords_add_button).click(function(e) {
                e.preventDefault();
                $(keywords_container).append('<div class="row mb-1"><div class="col-10"><input type="text" name="keywords[]" class="form-control" maxlength="256" required /></div><div class="col-2"><a href="#" class="keywords_delete_button btn btn-block btn-danger">Delete</a></div></div>');
            });

            $(keywords_container).on("click", ".keywords_delete_button", function(e) {
                e.preventDefault();
                $(this).parent('div').parent('div').remove();
            })
        });
    </script>
@endpush

@section('content')
    <x-forms.post :action="route('admin.line.reply.store')">
        <x-backend.card>
            <x-slot name="header">
                @lang('Create Reply')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.line.reply.index')" :text="__('Cancel')" />
            </x-slot>

            <x-slot name="body">
                <div class="form-group row">
                    <label for="keywords" class="col-md-2 col-form-label">@lang('Keywords')</label>

                    <div class="col-md-10 keywords_container">
                        <button type="button" class="keywords_add_button btn btn-info mb-1">
                            <span class="cil-contrast btn-icon mr-2"></span> Add New Field
                        </button>
                        <div class="row mb-1">
                            <div class="col-12">
                                <input type="text" name="keywords[]" class="form-control" maxlength="256" required />
                            </div>
                        </div>
                    </div>
                </div><!--form-group-->

                <div class="form-group row">
                    <label for="response" class="col-md-2 col-form-label">@lang('Response - Quick reply')</label>

                    <div class="col-md-10">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="inputState">Action</label>
                                <select id="inputState" class="form-control">
                                    <option selected>Choose...</option>
                                    <option value="message">Message action</option>
                                    <option value="datetimepicker">Datetime picker action</option>
                                    <option value="camera">Camera action</option>
                                    <option value="cameraRoll">Camera roll action</option>
                                    <option value="uri">URI action</option>
                                    <option value="location">Location action</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputCity">City</label>
                                <input type="text" class="form-control" id="inputCity">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="inputZip">Zip</label>
                                <input type="text" class="form-control" id="inputZip">
                            </div>
                        </div>
                    </div>
                </div><!--form-group-->
            </x-slot>

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Create Reply')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
@endsection
