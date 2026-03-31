@extends('layouts/contentNavbarLayout')

@section('title', 'Create CMS Section')

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">CMS /</span> Create Section</h4>

<div class="row">
  <div class="col-md-8">
    <div class="card mb-4">
      <h5 class="card-header">Section Details</h5>
      <div class="card-body">
        <form action="{{ route('admin.cms.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label" for="name">Internal Name</label>
              <input type="text" class="form-control" id="name" name="name" 
                     placeholder="e.g. Hero Section, Features List" required />
            </div>
            <div class="col-md-6">
              <label class="form-label" for="slug">Slug (Unique Key)</label>
              <input type="text" class="form-control" id="slug" name="slug" 
                     placeholder="e.g. home-features" required />
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label" for="title">Public Title</label>
            <input type="text" class="form-control" id="title" name="title" 
                   placeholder="Section heading displayed on site" />
          </div>

          <div class="mb-3">
            <label class="form-label" for="subtitle">Subtitle / Caption</label>
            <input type="text" class="form-control" id="subtitle" name="subtitle" />
          </div>

          <div class="mb-3">
            <label class="form-label" for="description">Main Description / Content</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
          </div>

          <div class="mt-4">
            <button type="submit" class="btn btn-primary me-2">Create Section</button>
            <a href="{{ route('admin.cms.index') }}" class="btn btn-outline-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('name').addEventListener('input', function() {
    let slug = this.value.toLowerCase()
        .replace(/[^\w\s-]/g, '')
        .replace(/[\s_-]+/g, '-')
        .replace(/^-+|-+$/g, '');
    document.getElementById('slug').value = slug;
});
</script>
@endsection
