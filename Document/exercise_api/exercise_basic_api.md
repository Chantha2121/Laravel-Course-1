# Laravel API CRUD Practice Exercises

This document contains 5 progressive exercises for learning and practicing RESTful API CRUD (Create, Read, Update, Delete) development in Laravel.

---

## Exercise 1: Product Management API (Difficulty: Easy)

### Objective
Learn how to create a basic API CRUD with standard data types, request validation, and clean JSON responses.

### Database Schema
**Model**: `Product`
- `id` (Primary Key)
- `name` (String, Required)
- `description` (Text, Nullable)
- `price` (Decimal: 8, 2, Required)
- `stock` (Integer, Default: 0)
- `timestamps` (`created_at`, `updated_at`)

### API Endpoints
| HTTP Method | URI | Controller Action | Description |
|---|---|---|---|
| `GET` | `/api/products` | `ProductController@index` | Get all products |
| `GET` | `/api/products/{id}` | `ProductController@show` | Get single product detail |
| `POST` | `/api/products` | `ProductController@store` | Create a new product |
| `PUT` | `/api/products/{id}` | `ProductController@update` | Update a product (Full update) |
| `DELETE` | `/api/products/{id}` | `ProductController@destroy` | Delete a product |

### Validation Rules
For both `POST` and `PUT` requests:
- `name`: Required, String, Max 255 characters
- `price`: Required, Numeric, Minimum 0
- `stock`: Optional, Integer, Minimum 0

### Sample Request & Response payloads

#### Create Product (POST `/api/products`)
**Request Body (JSON)**:
```json
{
  "name": "Mechanical Keyboard",
  "description": "RGB backlight mechanical keyboard with blue switches.",
  "price": 59.99,
  "stock": 15
}
```

**Success Response (JSON - 201 Created)**:
```json
{
  "message": "Product created successfully.",
  "data": {
    "id": 1,
    "name": "Mechanical Keyboard",
    "description": "RGB backlight mechanical keyboard with blue switches.",
    "price": 59.99,
    "stock": 15,
    "created_at": "2026-07-05T13:20:00.000000Z",
    "updated_at": "2026-07-05T13:20:00.000000Z"
  }
}
```

#### Challenge/Bonus
Implement global exception handling in your application so that if a product is not found (`ModelNotFoundException`), it returns a clean JSON error response (`404 Not Found`) instead of a HTML exception page.

---

## Exercise 2: Student Registry API with Soft Deletes (Difficulty: Medium)

### Objective
Learn about route model binding, unique field validation (with exception on update), and implementing Soft Deletes.

### Database Schema
**Model**: `Student`
- `id` (Primary Key)
- `first_name` (String, Required)
- `last_name` (String, Required)
- `email` (String, Unique, Required)
- `date_of_birth` (Date, Required)
- `is_active` (Boolean, Default: true)
- `deleted_at` (Timestamp for Soft Deletes)

### API Endpoints
| HTTP Method | URI | Controller Action | Description |
|---|---|---|---|
| `GET` | `/api/students` | `StudentController@index` | Get all students (only non-deleted) |
| `GET` | `/api/students/trashed` | `StudentController@onlyTrashed` | Get all soft-deleted students |
| `GET` | `/api/students/{student}` | `StudentController@show` | View a single student (Route Model Binding) |
| `POST` | `/api/students` | `StudentController@store` | Register a new student |
| `PUT` | `/api/students/{student}` | `StudentController@update` | Update student details |
| `DELETE` | `/api/students/{student}` | `StudentController@destroy` | Soft delete a student |
| `PATCH` | `/api/students/{id}/restore` | `StudentController@restore` | Restore a soft-deleted student |

### Validation Rules
- `first_name`: Required, String, Max 100
- `last_name`: Required, String, Max 100
- `email`: Required, Valid Email format, Unique database constraint (Ignore current student ID on update)
- `date_of_birth`: Required, Valid Date before today

### Sample Response: Soft Delete (DELETE `/api/students/5`)
**Success Response (JSON - 200 OK)**:
```json
{
  "message": "Student soft-deleted successfully."
}
```

#### Challenge/Bonus
Create a database seeder that generates 50 students using Laravel Factory so you have dummy data to test your endpoints.

---

## Exercise 3: Task Manager API with Search & Pagination (Difficulty: Medium)

### Objective
Implement search query parameters, filtering, sorting, custom status patch updates, and pagination.

### Database Schema
**Model**: `Task`
- `id` (Primary Key)
- `title` (String, Required)
- `description` (Text, Nullable)
- `due_date` (Date, Required)
- `status` (Enum: `pending`, `in_progress`, `completed`, Default: `pending`)
- `priority` (Enum: `low`, `medium`, `high`, Default: `medium`)

### API Endpoints
| HTTP Method | URI | Controller Action | Description |
|---|---|---|---|
| `GET` | `/api/tasks` | `TaskController@index` | List tasks with sorting, filters, and pagination |
| `POST` | `/api/tasks` | `TaskController@store` | Create a task |
| `GET` | `/api/tasks/{task}` | `TaskController@show` | View a task |
| `PUT` | `/api/tasks/{task}` | `TaskController@update` | Full update |
| `PATCH` | `/api/tasks/{task}/status` | `TaskController@updateStatus` | Custom endpoint to update task status only |
| `DELETE` | `/api/tasks/{task}` | `TaskController@destroy` | Delete a task |

### Filtering & Sorting parameters
- `GET /api/tasks?search=laundry` (Search by title or description)
- `GET /api/tasks?status=completed` (Filter by status)
- `GET /api/tasks?priority=high` (Filter by priority)
- `GET /api/tasks?sort_by=due_date&sort_order=desc` (Sort by field and order)
- `GET /api/tasks?per_page=10` (Dynamic pagination limit)

### Sample Response: Paginated Tasks List (GET `/api/tasks?status=pending&per_page=2`)
**Success Response (JSON - 200 OK)**:
```json
{
  "data": [
    {
      "id": 2,
      "title": "Buy groceries",
      "description": "Buy milk, eggs, and bread.",
      "due_date": "2026-07-06",
      "status": "pending",
      "priority": "medium"
    },
    {
      "id": 4,
      "title": "Fix bug #104",
      "description": "Fix logout issue in API.",
      "due_date": "2026-07-08",
      "status": "pending",
      "priority": "high"
    }
  ],
  "links": {
    "first": "http://localhost:8000/api/tasks?page=1",
    "last": "http://localhost:8000/api/tasks?page=5",
    "prev": null,
    "next": "http://localhost:8000/api/tasks?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 5,
    "per_page": 2,
    "to": 2,
    "total": 10
  }
}
```

---

## Exercise 4: Book & Author API with API Resources (Difficulty: Hard)

### Objective
Build relationships (One-to-Many), configure foreign keys, and utilize Laravel API Resources (`JsonResource` and `ResourceCollection`) to format the returned JSON response and selectively load relationships.

### Database Schema

**Model**: `Author`
- `id` (Primary Key)
- `name` (String, Required)
- `bio` (Text, Nullable)

**Model**: `Book`
- `id` (Primary Key)
- `author_id` (Foreign Key pointing to `authors.id`, Cascade Delete)
- `title` (String, Required)
- `isbn` (String, Unique, Required)
- `published_year` (Integer, Required)

### API Endpoints
| HTTP Method | URI | Controller Action | Description |
|---|---|---|---|
| `GET` | `/api/authors` | `AuthorController@index` | Get list of authors |
| `POST` | `/api/authors` | `AuthorController@store` | Create author |
| `GET` | `/api/books` | `BookController@index` | Get list of books (include author resource details) |
| `POST` | `/api/books` | `BookController@store` | Create book |
| `GET` | `/api/books/{book}` | `BookController@show` | View details of a book |
| `PUT` | `/api/books/{book}` | `BookController@update` | Update details of a book |
| `DELETE` | `/api/books/{book}` | `BookController@destroy` | Delete a book |

### Validation Rules for Books
- `author_id`: Required, must exist in `authors` table
- `title`: Required, String, Max 255
- `isbn`: Required, Unique in `books` table, format must match valid ISBN-13 format (13 digits, optionally containing hyphens). Use custom Regex or Custom Rule validation.
- `published_year`: Required, Integer between 1000 and current year

### API Resource Customization
Create a `BookResource` that wraps the book response and includes author details nested inside it.
Expected output format for `GET /api/books/1`:
```json
{
  "data": {
    "id": 1,
    "book_title": "Laravel Patterns",
    "isbn_code": "978-3-16-148410-0",
    "year": 2024,
    "author": {
      "id": 3,
      "author_name": "Taylor Otwell"
    }
  }
}
```

---

## Exercise 5: Employee Profile API with File Upload (Difficulty: Hard)

### Objective
Learn to handle file uploads, validate file inputs, store uploaded files in the public directory, serve absolute file URLs, and ensure orphaned files are deleted from the disk on update or delete.

### Database Schema
**Model**: `Employee`
- `id` (Primary Key)
- `name` (String, Required)
- `position` (String, Required)
- `email` (String, Unique, Required)
- `profile_picture` (String, Nullable, stores relative file path in storage)

### API Endpoints
| HTTP Method | URI | Controller Action | Description |
|---|---|---|---|
| `GET` | `/api/employees` | `EmployeeController@index` | Get list of employees |
| `POST` | `/api/employees` | `EmployeeController@store` | Create employee (handles multipart file upload) |
| `GET` | `/api/employees/{employee}` | `EmployeeController@show` | View employee details |
| `POST` | `/api/employees/{employee}` | `EmployeeController@update` | Update employee (use POST with `_method=PUT` spoofing for file uploads) |
| `DELETE` | `/api/employees/{employee}` | `EmployeeController@destroy` | Delete employee & clean up their profile picture from disk |

### Validation Rules
- `name`: Required, String
- `position`: Required, String
- `email`: Required, Email, Unique in `employees` table (except current ID on update)
- `profile_picture`: Optional, must be an image, mime types: `jpeg, png, jpg, gif`, max size 2048 KB (2MB)

### Implementation Requirements
1. **Store File**: Save file under the public disk inside a directory: `public/avatars`.
2. **Path Helper**: Return the absolute asset URL of the profile picture in the JSON response using `asset(Storage::url($employee->profile_picture))`.
3. **Clean Up Disk**: 
   - When a user updates the profile picture, the old file must be physically deleted from disk.
   - When an employee record is deleted from the database, their profile picture must also be deleted from disk.

### Sample Response: GET `/api/employees/1`
**Success Response (JSON - 200 OK)**:
```json
{
  "data": {
    "id": 1,
    "name": "Jane Doe",
    "position": "Senior Backend Developer",
    "email": "jane.doe@example.com",
    "profile_picture_url": "http://localhost:8000/storage/avatars/abc123xyz_avatar.png"
  }
}
```
