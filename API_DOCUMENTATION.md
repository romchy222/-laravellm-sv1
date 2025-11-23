# API Documentation

## Base URL
```
http://localhost:8000/api
```

## Authentication

All authenticated endpoints require a Bearer token in the Authorization header:
```
Authorization: Bearer {your-token}
```

Use Laravel Sanctum for authentication.

## Endpoints

### Authentication

#### Register
```http
POST /register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

#### Login
```http
POST /login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123",
  "remember": true
}
```

#### Logout
```http
POST /logout
Authorization: Bearer {token}
```

---

### User Profile

#### Get Current User Profile
```http
GET /api/profile
Authorization: Bearer {token}
```

**Response:**
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "roles": [...],
    "achievements": [...],
    "wallet": {...}
  },
  "enrollments": [...],
  "statistics": {
    "total_lessons": 50,
    "completed_lessons": 30,
    "total_points": 1500,
    "total_achievements": 10
  }
}
```

#### Update Profile
```http
PUT /api/profile
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "John Smith",
  "bio": "Software developer passionate about learning",
  "timezone": "UTC",
  "locale": "en"
}
```

#### Get Enrolled Courses
```http
GET /api/my-courses
Authorization: Bearer {token}
```

#### Get Achievements
```http
GET /api/my-achievements
Authorization: Bearer {token}
```

#### Get Certificates
```http
GET /api/my-certificates
Authorization: Bearer {token}
```

#### Get Wallet
```http
GET /api/my-wallet
Authorization: Bearer {token}
```

---

### Courses

#### List All Courses (Public)
```http
GET /api/courses?category_id=1&level=beginner&search=python&per_page=15&page=1
```

**Query Parameters:**
- `category_id` - Filter by category
- `level` - Filter by level (beginner, intermediate, advanced)
- `search` - Search in title and description
- `per_page` - Results per page (default: 15)
- `page` - Page number

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "title": "Introduction to Python",
      "slug": "introduction-to-python",
      "description": "Learn Python from scratch",
      "thumbnail": "url",
      "instructor": {...},
      "category": {...},
      "level": "beginner",
      "format": "self_paced",
      "price": 49.99,
      "currency": "USD",
      "is_published": true
    }
  ],
  "links": {...},
  "meta": {...}
}
```

#### Get Course Details (Public)
```http
GET /api/courses/{id}
```

**Response:**
```json
{
  "id": 1,
  "title": "Introduction to Python",
  "modules": [
    {
      "id": 1,
      "title": "Getting Started",
      "lessons": [...]
    }
  ],
  "reviews": [...],
  "instructor": {...}
}
```

#### Create Course
```http
POST /api/courses
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Advanced JavaScript",
  "description": "Master advanced JavaScript concepts",
  "category_id": 2,
  "level": "advanced",
  "format": "self_paced",
  "language": "en",
  "price": 99.99,
  "currency": "USD",
  "duration": 1200,
  "max_students": 100,
  "drip_content": false,
  "certificate_enabled": true,
  "passing_percentage": 70
}
```

#### Update Course
```http
PUT /api/courses/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Advanced JavaScript - Updated",
  "price": 89.99
}
```

#### Delete Course
```http
DELETE /api/courses/{id}
Authorization: Bearer {token}
```

#### Publish Course
```http
POST /api/courses/{id}/publish
Authorization: Bearer {token}
```

#### Enroll in Course
```http
POST /api/courses/{id}/enroll
Authorization: Bearer {token}
```

**Response:**
```json
{
  "id": 1,
  "user_id": 1,
  "course_id": 1,
  "status": "enrolled",
  "progress": 0,
  "enrolled_at": "2024-01-01T00:00:00Z",
  "payment_status": "pending",
  "payment_amount": 49.99,
  "payment_currency": "USD"
}
```

---

### Lessons

#### Get Lesson Details
```http
GET /api/lessons/{id}
Authorization: Bearer {token}
```

**Response:**
```json
{
  "lesson": {
    "id": 1,
    "title": "Variables and Data Types",
    "type": "text",
    "content": {...},
    "questions": [...],
    "comments": [...]
  },
  "progress": {
    "status": "in_progress",
    "progress_percentage": 50,
    "attempts": 1
  }
}
```

#### Start Lesson
```http
POST /api/lessons/{id}/start
Authorization: Bearer {token}
```

#### Complete Lesson
```http
POST /api/lessons/{id}/complete
Authorization: Bearer {token}
```

#### Update Lesson Progress
```http
POST /api/lessons/{id}/progress
Authorization: Bearer {token}
Content-Type: application/json

{
  "progress_percentage": 75,
  "time_spent": 300
}
```

**Parameters:**
- `progress_percentage` - Progress percentage (0-100)
- `time_spent` - Time spent in seconds (added to total)

#### Submit Quiz Answers
```http
POST /api/lessons/{id}/submit-answers
Authorization: Bearer {token}
Content-Type: application/json

{
  "answers": [
    {
      "question_id": 1,
      "answer": "option_a"
    },
    {
      "question_id": 2,
      "answer": ["option_a", "option_c"]
    }
  ]
}
```

**Response:**
```json
{
  "score": 85.5,
  "earned_points": 17,
  "total_points": 20,
  "passed": true,
  "progress": {
    "status": "completed",
    "score": 85.5,
    "attempts": 1
  }
}
```

---

## Question Types

### Single Choice
```json
{
  "type": "single_choice",
  "question_text": "What is 2+2?",
  "options": ["2", "3", "4", "5"],
  "correct_answer": ["4"]
}
```

### Multiple Choice
```json
{
  "type": "multiple_choice",
  "question_text": "Select all prime numbers:",
  "options": ["1", "2", "3", "4"],
  "correct_answer": ["2", "3"]
}
```

### True/False
```json
{
  "type": "true_false",
  "question_text": "Python is a compiled language",
  "options": ["true", "false"],
  "correct_answer": ["false"]
}
```

### Short Answer
```json
{
  "type": "short_answer",
  "question_text": "What is the capital of France?",
  "correct_answer": ["Paris"]
}
```

### Essay
```json
{
  "type": "essay",
  "question_text": "Explain object-oriented programming",
  "correct_answer": []
}
```
*Note: Essay questions require manual grading*

---

## Error Responses

### 400 Bad Request
```json
{
  "message": "Validation error",
  "errors": {
    "email": ["The email field is required."]
  }
}
```

### 401 Unauthorized
```json
{
  "message": "Unauthenticated"
}
```

### 403 Forbidden
```json
{
  "message": "Not enrolled in this course"
}
```

### 404 Not Found
```json
{
  "message": "Resource not found"
}
```

### 500 Internal Server Error
```json
{
  "message": "Server error"
}
```

---

## Rate Limiting

API requests are rate-limited to:
- **60 requests per minute** for authenticated users
- **30 requests per minute** for guests

When rate limit is exceeded:
```json
{
  "message": "Too many requests"
}
```

---

## Pagination

All list endpoints support pagination with the following structure:

```json
{
  "data": [...],
  "links": {
    "first": "url",
    "last": "url",
    "prev": null,
    "next": "url"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 10,
    "per_page": 15,
    "to": 15,
    "total": 150
  }
}
```

---

## Webhooks (Coming Soon)

Webhook events will be available for:
- Course enrollment
- Lesson completion
- Certificate issued
- Payment processed
- Achievement earned

---

## Future Endpoints

The following endpoints are planned:

- `/api/groups` - Group management
- `/api/assignments` - Assignment submission and grading
- `/api/analytics` - User and course analytics
- `/api/notifications` - User notifications
- `/api/messages` - Private messaging
- `/api/forums` - Course forums
- `/api/payments` - Payment processing
- `/api/certificates/verify/{code}` - Certificate verification

---

## SDKs and Libraries

Official SDKs coming soon for:
- JavaScript/TypeScript
- Python
- PHP
- Ruby

---

## Support

For API support and questions:
- GitHub Issues: https://github.com/romchy222/-laravellm-sv1/issues
- Documentation: See IMPLEMENTATION.md
