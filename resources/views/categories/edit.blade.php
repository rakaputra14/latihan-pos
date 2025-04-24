@extends('layouts.main')
@section('title', 'Add New Categories')
@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Categories</h5>
                        <form action="{{ route('categories.update', $edit->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="" class="col-form-label">Category Name *</label>
                                <input type="text" class="form-control" name="category_name"
                                    placeholder="Enter Category Name" required value="{{ $edit->category_name }}">
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="reset" class="btn btn-danger">Cancel</button>
                                <a href="{{ url()->previous() }}" class="text-primary">Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection