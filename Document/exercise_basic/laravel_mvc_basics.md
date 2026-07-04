# មូលដ្ឋានគ្រឹះនៃ Laravel MVC ជាមួយកូដអនុវត្តជាក់ស្តែង (Laravel MVC Basics)

Laravel គឺជា framework របស់ PHP ដ៏ពេញនិយមមួយដែលដំណើរការទៅតាមលំនាំ **Model-View-Controller (MVC)**។ លំនាំ (Pattern) នេះជួយបែងចែកតួនាទី និងភារកិច្ចក្នុងកម្មវិធីជាបីផ្នែកផ្សេងគ្នា (ទិន្នន័យ, UI, និង Logic គ្រប់គ្រងការងារ) ដែលធ្វើឱ្យការសរសេរកូដមានភាពងាយស្រួលក្នុងការអភិវឌ្ឍ ថែទាំ និងពង្រីកនៅពេលក្រោយ។

---

## ការស្វែងយល់លម្អិតអំពី MVC នៅក្នុង Laravel

### ១. Model (គ្រប់គ្រងទិន្នន័យ និង Database)
- **តួនាទី**: ធ្វើការជាមួយ Database សរសេរ Query ទាញទិន្នន័យ បង្កើតទំនាក់ទំនងរវាង Table និងរក្សាទុក Business Logic របស់ទិន្នន័យ។
- **ទីតាំង**: ស្ថិតនៅក្នុង Folder `app/Models/` (ឧទាហរណ៍៖ `app/Models/Student.php`)
- **Eloquent ORM**: Laravel ប្រើប្រាស់ Eloquent ORM។ រាល់ Table នីមួយៗនៅក្នុង Database គឺមាន Model មួយដែលតំណាងឱ្យវា ដើម្បីឱ្យយើងងាយស្រួលទាញយក ឬរក្សាទុកទិន្នន័យដោយមិនបាច់សរសេរ SQL query ផ្ទាល់។
- **គោលគំនិតសំខាន់ៗ**:
  - **Migrations**: គឺជាប្លង់មេ (Blueprints) សម្រាប់បង្កើត ឬកែប្រែ Table នៅក្នុង Database (`database/migrations/`)។
  - **Mass Assignment**: ការកំណត់ `$fillable` (អនុញ្ញាតឱ្យបញ្ចូលទិន្នន័យ) ឬ `$guarded` (ការពារមិនឱ្យបញ្ចូលទិន្នន័យ) ដើម្បីការពារសុវត្ថិភាពទិន្នន័យ។

### ២. View (ផ្នែកបង្ហាញ UI)
- **តួនាទី**: ជាផ្ទៃ Interface សម្រាប់បង្ហាញព័ត៌មានទៅកាន់អ្នកប្រើប្រាស់ និងទទួលយកការចុច ឬការបញ្ចូលទិន្នន័យពីអ្នកប្រើប្រាស់។
- **ទីតាំង**: ស្ថិតនៅក្នុង Folder `resources/views/` (ឧទាហរណ៍៖ `resources/views/students/index.blade.php`)
- **Blade Templating Engine**: Laravel ប្រើប្រាស់ Blade ដែលជា Tool ជួយសម្រួលដល់ការសរសេរ HTML ឱ្យកាន់តែមានភាពរស់រវើក។
  - **Layouts**: ការប្រើប្រាស់ `@extends()`, `@section()`, និង `@yield()` ជួយឱ្យយើងបង្កើត Layout រួមមួយ ហើយយកទៅប្រើប្រាស់ឡើងវិញនៅលើទំព័រផ្សេងៗ។
  - **Directives**: ដូចជា `@if`, `@foreach`, `@empty`, `@auth` ជួយសរសេរលក្ខខណ្ឌ ឬ loop នៅក្នុង HTML ដោយមិនបាច់ប្រើប្រាស់កូដ PHP ញ៉េរញ៉ៃ។
  - **CSRF Protection**: ការប្រើ `@csrf` នៅក្នុង Form ដើម្បីបង្កើត Token ការពារសុវត្ថិភាពពីការវាយប្រហារពីខាងក្រៅ (Cross-Site Request Forgery)។

### ៣. Controller (អ្នកបញ្ជា និងគ្រប់គ្រង Request)
- **តួនាទី**: ដូចជាប៉ូលីសចរាចរណ៍ ឬអ្នកសម្របសម្រួល។ វាទទួលសំណើ (HTTP Request) ពី Browser រួចទៅទាក់ទងជាមួយ Model ដើម្បីទាញយកទិន្នន័យ រួចបញ្ជូនទិន្នន័យនោះទៅឱ្យ View ដើម្បីបង្ហាញទៅកាន់អ្នកប្រើប្រាស់។
- **ទីតាំង**: ស្ថិតនៅក្នុង Folder `app/Http/Controllers/` (ឧទាហរណ៍៖ `app/Http/Controllers/StudentController.php`)
- **Resource Controllers**: ជា Controller ដែលបង្កើតឡើងមកមានស្រាប់នូវ function សំខាន់ៗសម្រាប់ការធ្វើ CRUD (`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`)។

---

## រចនាសម្ព័ន្ធ Folder សំខាន់ៗរបស់ Laravel

```text
bootstrap/              # កូដសម្រាប់ចាប់ផ្តើមដំណើរការកម្មវិធី និងការកំណត់ (configuration)
config/                 # ឯកសារសម្រាប់កំណត់ settings ផ្សេងៗ (database, mail, session...)
database/
├── migrations/        # ឯកសារ schema សម្រាប់បង្កើត table ក្នុង database
└── seeders/           # ឯកសារសម្រាប់បញ្ចូលទិន្នន័យគំរូ (dummy data) ទៅក្នុង database
app/
├── Http/
│   ├── Controllers/   # ឯកសារ Controller ទាំងឡាយ
│   └── Requests/      # ឯកសារសម្រាប់កំណត់លក្ខខណ្ឌផ្ទៀងផ្ទាត់ Form Validation
└── Models/            # ឯកសារ Model (ទាក់ទងនឹង Database)
resources/
└── views/             # ឯកសារ Blade template (HTML/UI)
routes/
└── web.php            # កន្លែងកំណត់ផ្លូវ Route សម្រាប់សំណើរបស់អ្នកប្រើប្រាស់
```

---

## ដំណើរការ Request នៅក្នុងលំនាំ MVC (MVC Flow)

```text
    +------------------+
    |     Browser      | <------------------------------------+
    +------------------+                                      |
       |             ^                                        |
       | HTTP        | HTML                                   |
       | Request     | Response                               |
       v             |                                        |
    +------------------+                                      |
    |      Routes      |                                      |
    | (routes/web.php) |                                      |
    +------------------+                                      |
       |                                                      |
       | ហៅដំណើរការ function ក្នុង Controller                      |
       v                                                      |
    +----------------------+                                  |
    |      Controller      |                                  |
    | (App\Http\Controller)|                                  |
    +-----------+----------+                                  |
       |        ^                                             |
       | សួររក  | ប្រគល់                                        |
       | ទិន្នន័យ | ទិន្នន័យ                                       |
       v        |                                             |
    +-----------+----------+       +------------------+       |
    |        Model         | <---> |     Database     |       |
    |   (App\Models\*)     |       +------------------+       |
    +-----------+----------+                                  |
       |                                                      |
       | បញ្ជូនទិន្នន័យទៅកាន់ View                                    |
       v                                                      |
    +----------------------+                                  |
    |         View         | ---------------------------------+
    |  (resources/views)   |
    +----------------------+
```

---

## ឧទាហរណ៍ជាក់ស្តែង៖ ប្រព័ន្ធគ្រប់គ្រងសិស្ស (Student CRUD)

ដើម្បីឱ្យយល់ច្បាស់ យើងនឹងរៀនបង្កើត **ប្រព័ន្ធគ្រប់គ្រងព័ត៌មានសិស្ស** មួយពីដំបូងជាមួយគ្នា។

### ជំហានទី ១៖ ការបង្កើត Database Table និង Model

វាយបញ្ជានៅក្នុង Terminal ដើម្បីបង្កើត Model និងឯកសារ Migration ក្នុងពេលតែមួយ៖
```bash
php artisan make:model Student -m
```
*(Option `-m` គឺសម្រាប់ប្រាប់ឱ្យ Laravel បង្កើតឯកសារ database migration ឱ្យយើងដោយស្វ័យប្រវត្តនៅក្នុង folder `database/migrations/`)*។

#### ១. ឯកសារ Migration (`database/migrations/xxxx_xx_xx_xxxxxx_create_students_table.php`)
បើកឯកសារ migration នោះ រួចបន្ថែមវាល (columns) ដូចខាងក្រោម៖
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ឈ្មោះសិស្ស
            $table->string('email')->unique(); // អ៊ីមែល (មិនអាចដូចគ្នាឡើយ)
            $table->string('phone')->nullable(); // លេខទូរស័ព្ទ (អាចទទេបាន)
            $table->timestamps(); // បង្កើត columns: created_at និង updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
```

បន្ទាប់មក វាយបញ្ជានេះដើម្បីបង្កើត table នៅក្នុង Database របស់អ្នក៖
```bash
php artisan migrate
```

#### ២. ឯកសារ Model (`app/Models/Student.php`)
កំណត់ `$fillable` ដើម្បីអនុញ្ញាតឱ្យយើងអាចរក្សាទុកទិន្នន័យចូលទៅក្នុង columns ទាំងនេះបានដោយសុវត្ថិភាព។
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    // កំណត់ឈ្មោះ table (មិនបាច់កំណត់ក៏បាន ប្រសិនបើឈ្មោះ table ជាអក្សរតូច និងថែមអក្សរ s នៅចុងបញ្ចប់៖ students)
    protected $table = 'students';

    // កំណត់ columns ដែលអនុញ្ញាតឱ្យបញ្ចូលទិន្នន័យបានក្នុងពេលតែមួយ (Mass Assignment)
    protected $fillable = [
        'name',
        'email',
        'phone',
    ];
}
```

---

### ជំហានទី ២៖ ការកំណត់ផ្លូវ Route (`routes/web.php`)

ជំនួសឱ្យការសរសេរ route មួយម្តងៗសម្រាប់បង្ហាញ បង្កើត កែប្រែ ឬលុប Laravel ផ្តល់នូវ `Route::resource` ដែលបង្កើតរាល់ routes សំខាន់ៗទាំងអស់ឱ្យយើងដោយស្វ័យប្រវត្តិ។

បើកឯកសារ `routes/web.php` រួចបន្ថែម៖
```php
use App\Http\Controllers\StudentController;

// បើកទំព័រដំបូង ឱ្យវាបញ្ជូន (redirect) ទៅកាន់ទំព័របញ្ជីសិស្ស
Route::get('/', function () {
    return redirect()->route('students.index');
});

// បង្កើត CRUD routes ទាំងអស់សម្រាប់ Student
Route::resource('students', StudentController::class);
```

#### តារាង Routes ដែលត្រូវបានបង្កើតឡើង៖
| HTTP Method | URL | Action (Function) | ឈ្មោះ Route (Route Name) | គោលបំណង |
|-------------|-----|-------------------|--------------------------|---------|
| GET | `/students` | `index` | `students.index` | បង្ហាញបញ្ជីឈ្មោះសិស្សទាំងអស់ |
| GET | `/students/create` | `create` | `students.create` | បង្ហាញ Form សម្រាប់បន្ថែមសិស្សថ្មី |
| POST | `/students` | `store` | `students.store` | រក្សាទុកទិន្នន័យសិស្សថ្មីចូល Database |
| GET | `/students/{student}` | `show` | `students.show` | បង្ហាញព័ត៌មានលម្អិតរបស់សិស្សម្នាក់ |
| GET | `/students/{student}/edit`| `edit` | `students.edit` | បង្ហាញ Form កែប្រែព័ត៌មានសិស្សម្នាក់ |
| PUT/PATCH | `/students/{student}` | `update` | `students.update` | ធ្វើបច្ចុប្បន្នភាពទិន្នន័យសិស្សក្នុង Database |
| DELETE | `/students/{student}` | `destroy` | `students.destroy` | លុបសិស្សនោះចេញពី Database |

---

### ជំហានទី ៣៖ ការបង្កើត និងសរសេរ Controller

បង្កើត resource controller មួយដោយប្រើពាក្យបញ្ជាខាងក្រោម៖
```bash
php artisan make:controller StudentController --resource
```
*(Option `--resource` នឹងបង្កើត function គំរូទាំងអស់ដូចជា index, create, store, edit, update, destroy មកស្រាប់តែម្តង)*។

បើកឯកសារ `app/Http/Controllers/StudentController.php` រួចសរសេរកូដដូចខាងក្រោម៖
```php
<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * បង្ហាញបញ្ជីឈ្មោះសិស្សទាំងអស់
     */
    public function index()
    {
        // ទាញយកទិន្នន័យសិស្ស ដោយរៀបចំពីថ្មីទៅចាស់ និងបែងចែកទំព័រ (Pagination) ១ទំព័រមាន ១០នាក់
        $students = Student::latest()->paginate(10);
        
        // បញ្ជូនទិន្នន័យទៅកាន់ View នៅក្នុង folder resources/views/students/index.blade.php
        return view('students.index', compact('students'));
    }

    /**
     * បង្ហាញ Form សម្រាប់បន្ថែមសិស្សថ្មី
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * ទទួលទិន្នន័យពី Form រួចរក្សាទុកក្នុង Database
     */
    public function store(Request $request)
    {
        // 1. ផ្ទៀងផ្ទាត់ទិន្នន័យដែលបញ្ចូលមក (Validation)
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'nullable|string|max:20',
        ]);

        // 2. បញ្ចូលទិន្នន័យទៅ Database តាមរយៈ Model
        Student::create($validatedData);

        // 3. ត្រឡប់ទៅកាន់ទំព័រ index វិញ ជាមួយសារដំណឹងជោគជ័យ
        return redirect()->route('students.index')
                         ->with('success', 'បានបន្ថែមសិស្សថ្មីដោយជោគជ័យ។');
    }

    /**
     * បង្ហាញព័ត៌មានលម្អិតរបស់សិស្សម្នាក់ (មិនសូវប្រើក្នុង CRUD មូលដ្ឋាន)
     */
    public function show(Student $student)
    {
        // Route Model Binding នឹងទាញយកទិន្នន័យ Student មកឱ្យយើងដោយស្វ័យប្រវត្តិតាមរយៈ id លើ URL
        return view('students.show', compact('student'));
    }

    /**
     * បង្ហាញ Form កែប្រែព័ត៌មានសិស្សម្នាក់
     */
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    /**
     * កែប្រែព័ត៌មានសិស្សនៅក្នុង Database
     */
    public function update(Request $request, Student $student)
    {
        // 1. ផ្ទៀងផ្ទាត់ទិន្នន័យ ដោយលើកលែង email របស់សិស្សបច្ចុប្បន្ន (មិនឱ្យជាន់គ្នាជាមួយអ្នកដទៃ)
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'phone' => 'nullable|string|max:20',
        ]);

        // 2. ធ្វើបច្ចុប្បន្នភាពទិន្នន័យសិស្ស
        $student->update($validatedData);

        // 3. ត្រឡប់ទៅទំព័រដើម រួចបង្ហាញសារដំណឹងជោគជ័យ
        return redirect()->route('students.index')
                         ->with('success', 'បានកែប្រែព័ត៌មានសិស្សដោយជោគជ័យ។');
    }

    /**
     * លុបព័ត៌មានសិស្សចេញពី Database
     */
    public function destroy(Student $student)
    {
        // លុបសិស្សចេញពី Database
        $student->delete();

        // ត្រឡប់ទៅទំព័រដើម ជាមួយសារដំណឹងជោគជ័យ
        return redirect()->route('students.index')
                         ->with('success', 'បានលុបព័ត៌មានសិស្សដោយជោគជ័យ។');
    }
}
```

---

### ជំហានទី ៤៖ ការបង្កើត Views (Blade Templates) ជាមួយ Styling

យើងនឹងប្រើប្រាស់ Bootstrap CSS ដើម្បីឱ្យទំព័ររបស់យើងមើលទៅស្អាត និងមានរបៀបរៀបរយ។

#### ១. ឯកសារ Layout រួម (`resources/views/layouts/app.blade.php`)
ឯកសារនេះជាគ្រោងទំព័រមេ ដែលទំព័រដទៃទៀតនឹងយកទៅប្រើប្រាស់បន្ត។
```html
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ប្រព័ន្ធគ្រប់គ្រងសិស្ស')</title>
    <!-- ប្រើប្រាស់ Bootstrap CDN ដើម្បីទទួលបានស្ទីលស្អាតភ្លាមៗ -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">

    <div class="container">
        <!-- ចំណងជើងខាងលើកម្មវិធី -->
        <header class="mb-4 text-center">
            <h1 class="fw-bold text-primary">ប្រព័ន្ធគ្រប់គ្រងព័ត៌មានសិស្ស</h1>
            <p class="text-muted">កម្មវិធីគំរូអភិវឌ្ឍន៍ដោយប្រើ Laravel MVC CRUD</p>
        </header>

        <!-- ផ្ទៃបង្ហាញមាតិកាដែលផ្លាស់ប្តូរទៅតាមទំព័រនីមួយៗ -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

#### ២. ទំព័របញ្ជីឈ្មោះសិស្ស (`resources/views/students/index.blade.php`)
ទំព័រនេះសម្រាប់បង្ហាញតារាងបញ្ជីឈ្មោះសិស្ស ប៊ូតុងបង្កើត កែប្រែ លុប និងបង្ហាញសារដំណឹងជោគជ័យ។
```html
@extends('layouts.app')

@section('title', 'បញ្ជីឈ្មោះសិស្ស')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-dark">បញ្ជីឈ្មោះសិស្សទាំងអស់</h5>
        <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm">+ បន្ថែមសិស្សថ្មី</a>
    </div>
    
    <div class="card-body">
        <!-- បង្ហាញសារដំណឹងជោគជ័យ ប្រសិនបើមាន -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($students->count() > 0)
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>លេខសម្គាល់ (ID)</th>
                            <th>ឈ្មោះ</th>
                            <th>អ៊ីមែល</th>
                            <th>លេខទូរស័ព្ទ</th>
                            <th>ថ្ងៃបង្កើត</th>
                            <th class="text-end">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td>{{ $student->id }}</td>
                                <td class="fw-semibold text-dark">{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->phone ?? 'មិនមាន' }}</td>
                                <td>{{ $student->created_at->format('d-M-Y H:i') }}</td>
                                <td class="text-end">
                                    <!-- ប៊ូតុងទៅកែប្រែព័ត៌មាន -->
                                    <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning btn-sm me-1 text-white">កែប្រែ</a>
                                    
                                    <!-- Form សម្រាប់លុបព័ត៌មាន -->
                                    <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="d-inline" onsubmit="return confirm('តើអ្នកពិតជាចង់លុបព័ត៌មានសិស្សរូបនេះមែនទេ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">លុប</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- ប៊ូតុងប្តូរទំព័រ (Pagination Links) -->
            <div class="mt-3">
                {{ $students->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <p class="text-muted mb-0">មិនទាន់មានទិន្នន័យសិស្សនៅឡើយទេ។ សូមចុច "បន្ថែមសិស្សថ្មី" ដើម្បីបញ្ចូលទិន្នន័យ។</p>
            </div>
        @endif
    </div>
</div>
@endsection
```

#### ៣. ទំព័របង្កើតសិស្សថ្មី (`resources/views/students/create.blade.php`)
ទំព័រនេះបង្ហាញ Form សម្រាប់បញ្ចូលទិន្នន័យសិស្សថ្មី ជាមួយការបង្ហាញកំហុស (Validation Errors) ប្រសិនបើបំពេញមិនត្រឹមត្រូវ។
```html
@extends('layouts.app')

@section('title', 'បន្ថែមសិស្សថ្មី')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-dark">បញ្ចូលព័ត៌មានសិស្សថ្មី</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('students.store') }}" method="POST">
                    @csrf <!-- ការពារសុវត្ថិភាព Form របស់ Laravel -->

                    <!-- វាលបញ្ចូលឈ្មោះ -->
                    <div class="mb-3">
                        <label for="name" class="form-label">ឈ្មោះពេញ</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="ឧទាហរណ៍៖ សុខ ជា">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- វាលបញ្ចូលអ៊ីមែល -->
                    <div class="mb-3">
                        <label for="email" class="form-label">អាសយដ្ឋានអ៊ីមែល</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="sok.chea@example.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- វាលបញ្ចូលលេខទូរស័ព្ទ -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">លេខទូរស័ព្ទ</label>
                        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="+855 12 345 678">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- ប៊ូតុងសកម្មភាព -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">ត្រឡប់ក្រោយ</a>
                        <button type="submit" class="btn btn-success">រក្សាទុក</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
```

#### ៤. ទំព័រកែប្រែព័ត៌មានសិស្ស (`resources/views/students/edit.blade.php`)
ទំព័រនេះបង្ហាញ Form ដែលមានទិន្នន័យចាស់ស្រាប់ ដើម្បីឱ្យយើងអាចកែប្រែបាន។
```html
@extends('layouts.app')

@section('title', 'កែប្រែព័ត៌មានសិស្ស')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-dark">កែប្រែព័ត៌មានសិស្ស</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('students.update', $student->id) }}" method="POST">
                    @csrf
                    @method('PUT') <!-- ប្រាប់ឱ្យ Laravel ដឹងថានេះជាការកែប្រែ (PUT Request) ព្រោះ Form ធម្មតាស្គាល់តែ POST ទេ -->

                    <!-- វាលកែឈ្មោះ -->
                    <div class="mb-3">
                        <label for="name" class="form-label">ឈ្មោះពេញ</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $student->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- វាលកែអ៊ីមែល -->
                    <div class="mb-3">
                        <label for="email" class="form-label">អាសយដ្ឋានអ៊ីមែល</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $student->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- វាលកែលេខទូរស័ព្ទ -->
                    <div class="mb-3">
                        <label for="phone" class="form-label">លេខទូរស័ព្ទ</label>
                        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $student->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- ប៊ូតុងសកម្មភាព -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">បោះបង់</a>
                        <button type="submit" class="btn btn-primary text-white">ធ្វើបច្ចុប្បន្នភាព</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
```

---

## គោលការណ៍ណែនាំល្អៗសម្រាប់អ្នករៀនដំបូង (Laravel MVC Best Practices)

1. **សរសេរ Controller ឱ្យខ្លី (Keep Controllers Skinny)**: កុំសរសេរ Logic ស្មុគស្មាញ ឬគណនាលេខផ្សេងៗនៅក្នុង Controller។ ទុកឱ្យ Model ជាអ្នកគណនា ឬបង្កើត Service ផ្សេងទៀត ចំណែក Controller គ្រាន់តែជាអ្នកបញ្ជា និងបញ្ជូនទិន្នន័យប៉ុណ្ណោះ។
2. **ត្រូវតែប្រើប្រាស់ CSRF Protection**: រាល់ Form ទាំងអស់ដែលត្រូវបញ្ជូនទិន្នន័យទៅកាន់ Database ត្រូវតែមាន `@csrf` ដើម្បីការពារសុវត្ថិភាព។
3. **ផ្ទៀងផ្ទាត់ទិន្នន័យជានិច្ច (Validate All Input)**: មិនត្រូវជឿជាក់លើទិន្នន័យដែលបញ្ចូលមកពីក្រៅឡើយ។ ត្រូវតែសរសេរការកំណត់លក្ខខណ្ឌ `$request->validate()` ជានិច្ចដើម្បីការពារកំហុសទិន្នន័យ។
4. **ប្រើប្រាស់ Route Model Binding**: ជំនួសឱ្យការសរសេរកូដស្វែងរកដូចជា `Student::find($id)` អ្នកអាចបញ្ជាក់ Model Type នៅក្នុង Controller Function ប៉ារ៉ាម៉ែត្រ (`Student $student`) ដើម្បីឱ្យ Laravel ទាញទិន្នន័យមកឱ្យដោយស្វ័យប្រវត្ត។
5. **ប្រើប្រាស់ Database Seeders & Factories**: ប្រើវាសម្រាប់បង្កើតទិន្នន័យគំរូច្រើនៗលឿនរហ័ស ដើម្បីធ្វើតេស្តសាកល្បងដោយមិនបាច់បំពេញដៃ។

---

## លំហាត់អនុវត្តជាក់ស្តែងសម្រាប់អ្នករៀនដំបូង (Practice Exercises)

ដើម្បីឱ្យយល់កាន់តែច្បាស់អំពីលំនាំ Laravel MVC សូមអនុវត្តលំហាត់ទាំង ៤ ជំហានដោយផ្អែកលើឯកសារណែនាំខាងក្រោម៖

1. **[លំហាត់ទី ១៖ ប្រព័ន្ធគ្រប់គ្រងប្លុកអត្ថបទ (Blog Post CRUD - Beginner)](file:///Users/choeurnchantha/Course/Laravel/Satur-Sun-8/Document/laravel_mvc_exercise1_blog.md)**
   - **ចំណុចផ្តោត**: ការធ្វើ CRUD ជាមូលដ្ឋាន, ការប្រើ Route Resource, និងការបង្កើតលក្ខខណ្ឌនៅក្នុង Blade ទំព័រ។
2. **[លំហាត់ទី ២៖ ប្រព័ន្ធគ្រប់គ្រងកិច្ចការតាមប្រភេទ (Category-based Todo - Easy-Intermediate)](file:///Users/choeurnchantha/Course/Laravel/Satur-Sun-8/Document/laravel_mvc_exercise2_todo.md)**
   - **ចំណុចផ្តោត**: ការបង្កើតទំនាក់ទំនង One-to-Many ក្នុង database, ទំនាក់ទំនង Model Relationships (`hasMany`/`belongsTo`), និងការបញ្ចូលទិន្នន័យគំរូដោយ Seeder។
3. **[លំហាត់ទី ៣៖ ប្រព័ន្ធបណ្ណាល័យសៀវភៅ ជាមួយការស្វែងរក និងបែងចែកទំព័រ (Library with Search & Pagination - Intermediate)](file:///Users/choeurnchantha/Course/Laravel/Satur-Sun-8/Document/laravel_mvc_exercise3_library.md)**
   - **ចំណុចផ្តោត**: ការប្រើប្រាស់ Query Scopes ដើម្បីស្វែងរក, ការបែងចែកទំព័រ (Pagination), និងការរក្សាតម្លៃស្វែងរកនៅពេលប្តូរទំព័រ។
4. **[លំហាត់ទី ៤៖ ប្រព័ន្ធគ្រប់គ្រងទំនិញ ជាមួយការបញ្ជូនរូបភាព និង Form Requests (Inventory with File Upload - Advanced-Intermediate)](file:///Users/choeurnchantha/Course/Laravel/Satur-Sun-8/Document/laravel_mvc_exercise4_inventory.md)**
   - **ចំណុចផ្តោត**: ការបញ្ចូលរូបភាព (File Upload), ការប្រើប្រាស់ Form Request Class សម្រាប់ Validation, និងការលុបឯកសារចេញពី Storage ពេលលុបទិន្នន័យ។

