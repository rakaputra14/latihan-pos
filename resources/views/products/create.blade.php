@extends('layouts.main')
@section('title', 'Add New Products')
@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Add New Products</h5>
                        <div align="right" class="mt-2">
                            <a href="{{ url()->previous() }}" class="btn btn-primary">Back</a>
                        </div>
                        <form action="{{ route('products.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="" class="col-form-label">Category *</label>
                                <select name="category_id" id="" class="form-control">
                                    <option value="">Select One</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="" class="col-form-label">Product Name *</label>
                                <input type="text" class="form-control" name="product_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="" class="col-form-label">Product Price *</label>
                                <input type="text" class="form-control" name="product_price" required>
                            </div>
                            <div class="mb-3">
                                <label for="" class="col-form-label">Product Photo </label>
                                <input type="file" class="form-control" name="photo">
                            </div>
                            <div class="mb-3">
                                <label for="" class="col-form-label">Product Description </label>
                                <input type="text" class="form-control" name="product_description">
                            </div>
                            <div class="mb-3">
                                <label for="" class="col-form-label">Status *</label>
                                <br>
                                Publish<input type="radio" name="is_active" value="1" checked>
                                Draft<input type="radio" name="is_active" value="0">
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="reset" class="btn btn-danger">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection