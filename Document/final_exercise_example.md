# 🚀 6 Final Project Examples for Laravel RESTful API

This document provides **6 real-world project ideas** designed for students to build complete Laravel RESTful APIs. Each project includes **5 or 6 interconnected Eloquent Models**, database schema fields, model relationships, and essential API endpoints.

---

## 📋 Table of Contents
1. [Project 1: E-Commerce Store API](#-project-1-e-commerce-store-api)
2. [Project 2: E-Learning Management System (LMS) API](#-project-2-e-learning-management-system-lms-api)
3. [Project 3: Food Delivery System API](#-project-3-food-delivery-system-api)
4. [Project 4: Clinic & Doctor Appointment System API](#-project-4-clinic--doctor-appointment-system-api)
5. [Project 5: Hotel Room Reservation System API](#-project-5-hotel-room-reservation-system-api)
6. [Project 6: Event Management & Ticketing System API](#-project-6-event-management--ticketing-system-api)

---

## 🛒 Project 1: E-Commerce Store API

### 📌 Overview
An API for an online retail store where users can browse products by category, add items to an order, write reviews, and track their purchase history.

### 🗄️ Models & Database Schemas (6 Models)

#### 1. User
- `id` (Primary Key)
- `name` (string)
- `email` (string, unique)
- `password` (string)
- `role` (enum: 'customer', 'admin' - default 'customer')
- `timestamps`

#### 2. Category
- `id` (Primary Key)
- `name` (string)
- `slug` (string, unique)
- `image` (string, nullable) - *Uses global `upload_image()` helper*
- `timestamps`

#### 3. Product
- `id` (Primary Key)
- `category_id` (foreignId -> categories)
- `name` (string)
- `description` (text, nullable)
- `price` (decimal: 10, 2)
- `stock` (integer)
- `image` (string, nullable) - *Uses global `upload_image()` helper*
- `timestamps`

#### 4. Order
- `id` (Primary Key)
- `user_id` (foreignId -> users)
- `total_price` (decimal: 10, 2)
- `status` (enum: 'pending', 'paid', 'shipped', 'cancelled')
- `shipping_address` (string)
- `timestamps`

#### 5. OrderItem
- `id` (Primary Key)
- `order_id` (foreignId -> orders)
- `product_id` (foreignId -> products)
- `quantity` (integer)
- `unit_price` (decimal: 10, 2)
- `timestamps`

#### 6. Review
- `id` (Primary Key)
- `user_id` (foreignId -> users)
- `product_id` (foreignId -> products)
- `rating` (integer: 1 to 5)
- `comment` (text, nullable)
- `timestamps`

### 🔗 Relationships
- `Category` **hasMany** `Product` | `Product` **belongsTo** `Category`
- `User` **hasMany** `Order` | `Order` **belongsTo** `User`
- `Order` **hasMany** `OrderItem` | `OrderItem` **belongsTo** `Order`
- `Product` **hasMany** `OrderItem` | `OrderItem` **belongsTo** `Product`
- `User` **hasMany** `Review` | `Product` **hasMany** `Review`

### 🌐 Key API Routes
- `POST /api/register` & `POST /api/login`
- `GET /api/categories` & `POST /api/categories`
- `GET /api/products` & `POST /api/products` (Image upload)
- `POST /api/orders` (Create order with order items)
- `GET /api/orders/my-orders`
- `POST /api/products/{id}/reviews`

---

## 🎓 Project 2: E-Learning Management System (LMS) API

### 📌 Overview
An API for an online learning platform allowing instructors to publish courses with multiple lessons, and students to enroll, watch lessons, and post reviews.

### 🗄️ Models & Database Schemas (6 Models)

#### 1. User
- `id` (Primary Key)
- `name` (string)
- `email` (string, unique)
- `password` (string)
- `role` (enum: 'student', 'instructor', 'admin')
- `timestamps`

#### 2. Category
- `id` (Primary Key)
- `name` (string)
- `description` (text, nullable)
- `timestamps`

#### 3. Course
- `id` (Primary Key)
- `instructor_id` (foreignId -> users)
- `category_id` (foreignId -> categories)
- `title` (string)
- `description` (text)
- `price` (decimal: 8, 2)
- `thumbnail` (string, nullable) - *Image Upload*
- `timestamps`

#### 4. Lesson
- `id` (Primary Key)
- `course_id` (foreignId -> courses)
- `title` (string)
- `video_url` (string, nullable)
- `content` (text, nullable)
- `order_index` (integer)
- `timestamps`

#### 5. Enrollment
- `id` (Primary Key)
- `student_id` (foreignId -> users)
- `course_id` (foreignId -> courses)
- `enrolled_at` (date)
- `status` (enum: 'active', 'completed')
- `timestamps`

#### 6. CourseReview
- `id` (Primary Key)
- `student_id` (foreignId -> users)
- `course_id` (foreignId -> courses)
- `rating` (integer: 1 to 5)
- `comment` (text, nullable)
- `timestamps`

### 🔗 Relationships
- `User` (instructor) **hasMany** `Course` | `Course` **belongsTo** `User` (instructor)
- `Category` **hasMany** `Course` | `Course` **belongsTo** `Category`
- `Course` **hasMany** `Lesson` | `Lesson` **belongsTo** `Course`
- `User` (student) **hasMany** `Enrollment` | `Course` **hasMany** `Enrollment`
- `Course` **hasMany** `CourseReview`

### 🌐 Key API Routes
- `GET /api/courses` & `GET /api/courses/{id}`
- `POST /api/courses` (Instructor creates course with thumbnail upload)
- `POST /api/courses/{id}/lessons` (Add lesson to course)
- `POST /api/courses/{id}/enroll` (Student enrolls)
- `GET /api/my-enrolled-courses`
- `POST /api/courses/{id}/reviews`

---

## 🍔 Project 3: Food Delivery System API

### 📌 Overview
An API for a food ordering service connecting customers, restaurants, menu items, and delivery orders.

### 🗄️ Models & Database Schemas (6 Models)

#### 1. User
- `id` (Primary Key)
- `name` (string)
- `email` (string, unique)
- `phone` (string)
- `password` (string)
- `role` (enum: 'customer', 'driver', 'restaurant_owner', 'admin')
- `timestamps`

#### 2. Restaurant
- `id` (Primary Key)
- `owner_id` (foreignId -> users)
- `name` (string)
- `address` (string)
- `phone` (string)
- `logo` (string, nullable) - *Image Upload*
- `timestamps`

#### 3. MenuCategory
- `id` (Primary Key)
- `restaurant_id` (foreignId -> restaurants)
- `name` (string) (e.g., 'Drinks', 'Main Course', 'Desserts')
- `timestamps`

#### 4. MenuItem
- `id` (Primary Key)
- `restaurant_id` (foreignId -> restaurants)
- `menu_category_id` (foreignId -> menu_categories)
- `name` (string)
- `description` (text, nullable)
- `price` (decimal: 8, 2)
- `image` (string, nullable) - *Image Upload*
- `is_available` (boolean, default true)
- `timestamps`

#### 5. Order
- `id` (Primary Key)
- `customer_id` (foreignId -> users)
- `restaurant_id` (foreignId -> restaurants)
- `driver_id` (foreignId -> users, nullable)
- `total_amount` (decimal: 10, 2)
- `status` (enum: 'pending', 'preparing', 'on_delivery', 'delivered', 'cancelled')
- `delivery_address` (string)
- `timestamps`

#### 6. OrderItem
- `id` (Primary Key)
- `order_id` (foreignId -> orders)
- `menu_item_id` (foreignId -> menu_items)
- `quantity` (integer)
- `unit_price` (decimal: 8, 2)
- `timestamps`

### 🔗 Relationships
- `User` (owner) **hasMany** `Restaurant` | `Restaurant` **belongsTo** `User`
- `Restaurant` **hasMany** `MenuCategory` & `MenuItem`
- `MenuCategory` **hasMany** `MenuItem`
- `Customer` **hasMany** `Order` | `Restaurant` **hasMany** `Order`
- `Order` **hasMany** `OrderItem` | `MenuItem` **hasMany** `OrderItem`

### 🌐 Key API Routes
- `GET /api/restaurants` & `GET /api/restaurants/{id}/menu`
- `POST /api/restaurants` (Create restaurant with logo)
- `POST /api/menu-items` (Create menu item with image)
- `POST /api/orders` (Place food order)
- `PATCH /api/orders/{id}/status` (Update status: preparing, on_delivery, delivered)

---

## 🏥 Project 4: Clinic & Doctor Appointment System API

### 📌 Overview
A healthcare API enabling patients to find doctors by medical specialty/department, book appointments, and receive digital prescriptions.

### 🗄️ Models & Database Schemas (6 Models)

#### 1. User
- `id` (Primary Key)
- `name` (string)
- `email` (string, unique)
- `password` (string)
- `phone` (string)
- `role` (enum: 'patient', 'doctor', 'admin')
- `timestamps`

#### 2. Department
- `id` (Primary Key)
- `name` (string) (e.g., Cardiology, Pediatrics, Dentistry)
- `description` (text, nullable)
- `timestamps`

#### 3. DoctorProfile
- `id` (Primary Key)
- `user_id` (foreignId -> users)
- `department_id` (foreignId -> departments)
- `bio` (text, nullable)
- `consultation_fee` (decimal: 8, 2)
- `avatar` (string, nullable) - *Image Upload*
- `timestamps`

#### 4. Appointment
- `id` (Primary Key)
- `patient_id` (foreignId -> users)
- `doctor_id` (foreignId -> doctor_profiles)
- `appointment_date` (dateTime)
- `status` (enum: 'scheduled', 'completed', 'cancelled')
- `reason` (string, nullable)
- `timestamps`

#### 5. Prescription
- `id` (Primary Key)
- `appointment_id` (foreignId -> appointments)
- `doctor_id` (foreignId -> doctor_profiles)
- `patient_id` (foreignId -> users)
- `notes` (text, nullable)
- `timestamps`

#### 6. PrescriptionItem
- `id` (Primary Key)
- `prescription_id` (foreignId -> prescriptions)
- `medicine_name` (string)
- `dosage` (string) (e.g., '500mg twice daily')
- `duration_days` (integer)
- `timestamps`

### 🔗 Relationships
- `Department` **hasMany** `DoctorProfile` | `DoctorProfile` **belongsTo** `Department`
- `User` (doctor) **hasOne** `DoctorProfile`
- `Patient` **hasMany** `Appointment` | `DoctorProfile` **hasMany** `Appointment`
- `Appointment` **hasOne** `Prescription`
- `Prescription` **hasMany** `PrescriptionItem`

### 🌐 Key API Routes
- `GET /api/departments`
- `GET /api/doctors` (Filter by department)
- `POST /api/appointments` (Book consultation slot)
- `GET /api/appointments/my-appointments`
- `POST /api/prescriptions` (Doctor issues prescription)

---

## 🏨 Project 5: Hotel Room Reservation System API

### 📌 Overview
An API for hotel booking management where guests reserve rooms based on room types, complete payments, and submit reviews.

### 🗄️ Models & Database Schemas (6 Models)

#### 1. User
- `id` (Primary Key)
- `name` (string)
- `email` (string, unique)
- `phone` (string)
- `password` (string)
- `role` (enum: 'guest', 'admin')
- `timestamps`

#### 2. RoomType
- `id` (Primary Key)
- `name` (string) (e.g., Standard Single, Deluxe Suite, Family Room)
- `description` (text, nullable)
- `base_price` (decimal: 10, 2)
- `capacity` (integer)
- `timestamps`

#### 3. Room
- `id` (Primary Key)
- `room_type_id` (foreignId -> room_types)
- `room_number` (string, unique)
- `floor` (integer)
- `status` (enum: 'available', 'occupied', 'maintenance')
- `image` (string, nullable) - *Image Upload*
- `timestamps`

#### 4. Booking
- `id` (Primary Key)
- `user_id` (foreignId -> users)
- `room_id` (foreignId -> rooms)
- `check_in_date` (date)
- `check_out_date` (date)
- `total_price` (decimal: 10, 2)
- `status` (enum: 'pending', 'confirmed', 'checked_in', 'cancelled')
- `timestamps`

#### 5. Payment
- `id` (Primary Key)
- `booking_id` (foreignId -> bookings)
- `amount` (decimal: 10, 2)
- `payment_method` (enum: 'card', 'cash', 'khqr', 'bank_transfer')
- `transaction_id` (string, nullable)
- `status` (enum: 'pending', 'completed', 'failed')
- `timestamps`

#### 6. RoomReview
- `id` (Primary Key)
- `user_id` (foreignId -> users)
- `room_id` (foreignId -> rooms)
- `rating` (integer: 1 to 5)
- `comment` (text, nullable)
- `timestamps`

### 🔗 Relationships
- `RoomType` **hasMany** `Room` | `Room` **belongsTo** `RoomType`
- `User` **hasMany** `Booking` | `Room` **hasMany** `Booking`
- `Booking` **hasOne** `Payment`
- `User` **hasMany** `RoomReview` | `Room` **hasMany** `RoomReview`

### 🌐 Key API Routes
- `GET /api/room-types` & `GET /api/rooms` (Filter by availability)
- `POST /api/rooms` (Admin adds room with image upload)
- `POST /api/bookings` (Guest reserves room)
- `POST /api/payments` (Process payment for booking)
- `POST /api/rooms/{id}/reviews`

---

## 🎟️ Project 6: Event Management & Ticketing System API

### 📌 Overview
An API for organizing events (concerts, tech conferences, workshops) where users purchase different tier tickets and receive digital access codes.

### 🗄️ Models & Database Schemas (6 Models)

#### 1. User
- `id` (Primary Key)
- `name` (string)
- `email` (string, unique)
- `password` (string)
- `role` (enum: 'attendee', 'organizer', 'admin')
- `timestamps`

#### 2. EventCategory
- `id` (Primary Key)
- `name` (string) (e.g., Concert, Workshop, Sports, Tech)
- `description` (text, nullable)
- `timestamps`

#### 3. Event
- `id` (Primary Key)
- `organizer_id` (foreignId -> users)
- `category_id` (foreignId -> event_categories)
- `title` (string)
- `description` (text)
- `location` (string)
- `start_time` (dateTime)
- `end_time` (dateTime)
- `banner_image` (string, nullable) - *Image Upload*
- `timestamps`

#### 4. TicketType
- `id` (Primary Key)
- `event_id` (foreignId -> events)
- `name` (string) (e.g., Regular, VIP, Early Bird)
- `price` (decimal: 8, 2)
- `quantity` (integer)
- `timestamps`

#### 5. TicketPurchase
- `id` (Primary Key)
- `user_id` (foreignId -> users)
- `event_id` (foreignId -> events)
- `total_price` (decimal: 10, 2)
- `payment_status` (enum: 'unpaid', 'paid', 'refunded')
- `timestamps`

#### 6. TicketItem
- `id` (Primary Key)
- `ticket_purchase_id` (foreignId -> ticket_purchases)
- `ticket_type_id` (foreignId -> ticket_types)
- `ticket_code` (string, unique) (e.g., UUID/Random Code)
- `price` (decimal: 8, 2)
- `is_used` (boolean, default false)
- `timestamps`

### 🔗 Relationships
- `User` (organizer) **hasMany** `Event` | `Event` **belongsTo** `User`
- `EventCategory` **hasMany** `Event`
- `Event` **hasMany** `TicketType`
- `User` **hasMany** `TicketPurchase` | `Event` **hasMany** `TicketPurchase`
- `TicketPurchase` **hasMany** `TicketItem`

### 🌐 Key API Routes
- `GET /api/events` & `GET /api/events/{id}`
- `POST /api/events` (Create event with banner upload)
- `POST /api/events/{id}/ticket-types` (Add VIP/Regular tickets)
- `POST /api/ticket-purchases` (Buy tickets and generate unique ticket codes)
- `GET /api/my-tickets`

---

## 🎯 Recommended Technical Requirements for Students
For any chosen project, students should implement:
1. **Laravel Sanctum Authentication**: Token-based login, register, logout (`auth:sanctum`).
2. **5 to 6 Eloquent Models & Migrations**: Proper foreign keys and cascade rules.
3. **Global Image Helper**: Use `upload_image($file, $folder, $oldPath)` for file uploads.
4. **Validation Requests**: Proper request rules (e.g., `required`, `email`, `unique`, `image`).
5. **API JSON Responses**: Consistent JSON response formatting with appropriate status codes (`200 OK`, `201 Created`, `400 Bad Request`, `404 Not Found`).
