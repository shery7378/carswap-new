@extends('layouts/contentNavbarLayout')

@section('title', 'Create Subscription')

@section('content')

<form method="POST" action="">
@csrf

<div class="row">

    {{-- LEFT SIDE --}}
    <div class="col-lg-8">

        {{-- Page Heading --}}
        <div class="mb-4">
            <h4 class="fw-bold">Create Subscription</h4>
        </div>

        {{-- Title --}}
        <div class="card mb-4">
            <div class="card-body">
                <input type="text"
                       name="title"
                       class="form-control form-control-lg"
                       placeholder="Add title"
                       required>
            </div>
        </div>

        {{-- Plan Configuration --}}
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Plan Configuration</h6>
            </div>
            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Monthly Price</label>
                        <input type="number" name="monthly_price" class="form-control" value="0">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Yearly Price</label>
                        <input type="number" name="yearly_price" class="form-control" value="0">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Active Ads Limit</label>
                        <input type="number" name="active_ads_limit" class="form-control" value="5">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Garage Ads Limit</label>
                        <input type="number" name="garage_ads_limit" class="form-control" value="10">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Expandable Slots</label>
                        <input type="number" name="expandable_slots" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Highlight Ads</label>
                        <input type="number" name="highlight_ads" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">HD Images</label>
                        <input type="number" name="hd_images" class="form-control" value="1">
                    </div>

                </div>

                <hr class="my-4">

                {{-- Plan Features --}}
                <label class="form-label">Plan Features</label>

                <div id="features-wrapper">
                    <div class="feature-item d-flex gap-2 mb-2">
                        <input type="text" name="features[]" class="form-control">
                        <button type="button" class="btn btn-outline-danger remove-feature">Remove</button>
                    </div>
                </div>

                <button type="button" class="btn btn-primary btn-sm" id="add-feature">
                    Add Feature
                </button>

            </div>
        </div>

    </div>

    {{-- RIGHT SIDE --}}
    <div class="col-lg-4">

        {{-- Publish --}}
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Publish</h6>
            </div>
            <div class="card-body">

                <button type="button" class="btn btn-outline-primary w-100 mb-3">
                    Save Draft
                </button>

                <button type="submit" class="btn btn-primary w-100">
                    Publish
                </button>

            </div>
        </div>

       

    </div>

</div>

</form>

@endsection


@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const addBtn = document.getElementById('add-feature');
    const wrapper = document.getElementById('features-wrapper');

    addBtn.addEventListener('click', function () {

        const div = document.createElement('div');
        div.className = "feature-item d-flex gap-2 mb-2";

        div.innerHTML = `
            <input type="text" name="features[]" class="form-control">
            <button type="button" class="btn btn-outline-danger remove-feature">Remove</button>
        `;

        wrapper.appendChild(div);
    });

    wrapper.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-feature')) {
            e.target.closest('.feature-item').remove();
        }
    });

});
</script>
@endsection
