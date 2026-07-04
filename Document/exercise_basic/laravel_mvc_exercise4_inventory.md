# Laravel MVC Exercise 4: Product Inventory with Image Upload & Form Requests

## Goal
Build an Inventory Management System that implements **File Upload (Images)**, custom validation using **Form Request Classes**, and file system cleanup when records are updated or deleted.

---

## Difficulty: Advanced-Intermediate (Focus: Form Requests & File Uploads)

## Database Fields
- `id` (Primary Key)
- `name` (String)
- `sku` (String, Unique) - Stock Keeping Unit (e.g. `PROD-SHIRT-01`)
- `price` (Decimal, 8 digits total, 2 decimal places)
- `stock` (Integer)
- `image_path` (String, Nullable) - Storing target path of the file
- `created_at` / `updated_at` (Timestamps)

---

## Implementation Guide

### Step 1: Model & Migration
Create the model and migration:
```bash
php artisan make:model Product -m
```

#### Migration (`database/migrations/xxxx_xx_xx_xxxxxx_create_products_table.php`):
```php
public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('sku')->unique();
        $table->decimal('price', 8, 2);
        $table->integer('stock');
        $table->string('image_path')->nullable();
        $table->timestamps();
    });
}
```

Run database migrations:
```bash
php artisan migrate
```

#### Model (`app/Models/Product.php`):
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'sku', 'price', 'stock', 'image_path'];
}
```

---

### Step 2: Custom Form Requests (Validation Separation)
Instead of putting validation inside our controller, we will keep controllers clean by generating dedicated Request validation files.

```bash
php artisan make:request StoreProductRequest
php artisan make:request UpdateProductRequest
```

#### 1. Store Product Request (`app/Http/Requests/StoreProductRequest.php`):
```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    // Set to true to allow request to be processed
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku|max:50',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // 2MB max
        ];
    }
}
```

#### 2. Update Product Request (`app/Http/Requests/UpdateProductRequest.php`):
```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // $this->route('product') gets the ID/Model of the current route binded item
        $productId = $this->route('product')->id;

        return [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku,' . $productId,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }
}
```

---

### Step 3: Routing (`routes/web.php`)
```php
use App\Http\Controllers\ProductController;

Route::resource('products', ProductController::class);
```

---

### Step 4: Controller (`app/Http/Controllers/ProductController.php`)
```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(8);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(StoreProductRequest $request)
    {
        // Data is already validated automatically by StoreProductRequest
        $data = $request->validated();

        // Handle Image Upload
        if ($request->hasFile('image')) {
            // Stores image in storage/app/public/products directory
            $path = $request->file('image')->store('products', 'public');
            $data['image_path'] = $path;
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        // Handle Image Update
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }

            // Store new image
            $path = $request->file('image')->store('products', 'public');
            $data['image_path'] = $path;
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        // Delete image associated with product from public directory
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
```

---

### Step 5: Configure Storage Link
In order to access files uploaded to `storage/app/public` directly from the web, we must create a symbolic link in our project pointing `public/storage` to `storage/app/public`.

Run the Artisan command:
```bash
php artisan storage:link
```

---

### Step 6: Views (Blade Files)

#### 1. Layout View (`resources/views/layouts/inventory.blade.php`):
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">
    <div class="container">
        @yield('content')
    </div>
</body>
</html>
```

#### 2. Index View (`resources/views/products/index.blade.php`):
Notice how we display stored images using `asset('storage/' . $product->image_path)` with a placeholder fallback if the image is missing.
```html
@extends('layouts.inventory')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 fw-bold mb-0">Inventory Dashboard</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">+ Add New Product</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row">
    @forelse($products as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <!-- Product Image -->
                @if($product->image_path)
                    <img src="{{ asset('storage/' . $product->image_path) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 180px; object-fit: cover;">
                @else
                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 180px;">
                        <span class="small">No Image Available</span>
                    </div>
                @endif
                
                <div class="card-body d-flex flex-column">
                    <span class="text-muted small text-monospace">{{ $product->sku }}</span>
                    <h5 class="card-title fw-bold mt-1 mb-2">{{ $product->name }}</h5>
                    
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="h6 fw-bold mb-0 text-success">${{ number_format($product->price, 2) }}</span>
                            <span class="badge {{ $product->stock > 5 ? 'bg-light text-dark border' : 'bg-danger' }}">
                                Stock: {{ $product->stock }}
                            </span>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-warning w-100">Edit</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="w-100" onsubmit="return confirm('Delete product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger w-100">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted mb-0">No products found in inventory.</p>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $products->links() }}
</div>
@endsection
```

#### 3. Create View (`resources/views/products/create.blade.php`):
**CRITICAL**: You must set `enctype="multipart/form-data"` on forms that upload files/images.
```html
@extends('layouts.inventory')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h4 class="mb-0 fw-bold">Add New Product</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">SKU</label>
                            <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku') }}">
                            @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price ($)</label>
                            <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}">
                            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Initial Stock Level</label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock') }}">
                            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Product Image</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                        <small class="text-muted">Allowed types: jpeg, png, jpg, webp. Max size: 2MB.</small>
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>
                        <button type="submit" class="btn btn-success">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
```

#### 4. Edit View (`resources/views/products/edit.blade.php`):
```html
@extends('layouts.inventory')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h4 class="mb-0 fw-bold">Edit Product</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Product Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">SKU</label>
                            <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku', $product->sku) }}">
                            @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price ($)</label>
                            <input type="number" step="0.01" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}">
                            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stock Level</label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock) }}">
                            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Product Image</label>
                        @if($product->image_path)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $product->image_path) }}" class="rounded shadow-sm" alt="Current Image" style="height: 100px; object-fit: cover;">
                                <div class="small text-muted mt-1">Current image uploaded</div>
                            </div>
                        @endif
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                        <small class="text-muted">Upload a new image if you want to replace the current one.</small>
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
```
