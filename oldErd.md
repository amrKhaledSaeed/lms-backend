# Entity Relationship Diagram (ERD)
## husseinkhalid-backend Database

---

## Core Entities

### **users**
User accounts (students, teachers, supervisors, admins)

**Fields:**
- `id` (PK)
- `name`, `nickname`, `email` (unique), `phone` (unique)
- `gender` (enum: male, female)
- `password`, `avatar`, `zoom_id`
- `is_active`, `birth_date`
- `main_role_id` (FK → roles) - Primary role
- `created_by` - User ID who created this user
- `disk` - Storage disk for avatar
- `email_verified_at`, `deleted_at`
- Timestamps

**Relationships:**
- has_one → `student_profiles`
- has_many → `device_tokens`, `t_a_notes`, `t_a_transfers`, `t_a_droppeds`
- has_many → `answered_questions`, `video_views`, `report_files`
- belongs_to → `roles` (main_role_id)
- belongs_to_many → `classes` (via `class_user`)
- belongs_to_many → `roles` (via `model_has_roles`)
- belongs_to_many → `permissions` (via `model_has_permissions`)

---

### **schools**
Schools in the system

**Fields:**
- `id` (PK)
- `name`
- `principle_name` - Principal's name
- `principle_email` - Principal's email
- Timestamps

**Relationships:**
- has_many → `classes`

---

### **centers**
Educational centers

**Fields:**
- `id` (PK)
- `name`
- Timestamps

**Relationships:**
- has_many → `student_profiles`, `center_notes`

---

### **class_categories**
Categories for classes (e.g., Grade levels, subjects)

**Fields:**
- `id` (PK)
- `name`, `code`
- Timestamps

**Relationships:**
- has_many → `classes`, `past_paper_categories`, `events`, `educational_parts`

---

### **classes**
Classes/Courses

**Fields:**
- `id` (PK)
- `name`, `fees`, `type`
- `school_id` (FK → schools), `category_id` (FK → class_categories)
- `is_ranked`, `start_date`, `end_date`
- `automatic_reporting`, `sort_order`
- Timestamps

**Relationships:**
- belongs_to → `schools`, `class_categories`
- has_many → `class_sessions`, `attendances`, `homework`, `quizzes`, `exams`, `classworks`, `reports`
- belongs_to_many → `users` (via `class_user`)
- belongs_to_many → `class_videos` (via `class_video_pivot`)
- belongs_to_many → `class_pdfs` (via `class_pdf_pivot`)
- belongs_to_many → `zoom` (via `zoom_classes`)

---

### **class_sessions**
Scheduled class sessions by day of week

**Fields:**
- `id` (PK)
- `class_id` (FK → classes)
- `weekday` (enum: Sunday-Saturday)
- `start_time`
- Timestamps

**Relationships:**
- belongs_to → `classes`

---

### **student_profiles**
Extended profile information for students

**Fields:**
- `id` (PK)
- `user_id` (FK → users)
- `facebook_url`, `facebook_name`
- `mother_name`, `mother_email`, `mother_phone`
- `father_name`, `father_email`, `father_phone`
- `school_name`, `student_type` (enum: school, center)
- `center_id` (FK → centers), `code`
- Timestamps

**Relationships:**
- belongs_to → `users`, `centers`

---

## Assessment Entities

### **attendances**
Attendance tracking for classes

**Fields:**
- `id` (PK)
- `name`, `qr_code`, `date`
- `class_id` (FK → classes)
- `sort_order`, `deleted_at`
- Timestamps

**Relationships:**
- belongs_to → `classes`
- has_many → `attendance_students`

---

### **attendance_students**
Student attendance records

**Fields:**
- `id` (PK)
- `attendance_id` (FK → attendances)
- `student_id` (FK → users)
- `is_present`
- `deleted_at`
- Timestamps

**Relationships:**
- belongs_to → `attendances`, `users`

---

### **homework**
Homework assignments

**Fields:**
- `id` (PK)
- `name`, `file`, `date`, `due_date`
- `class_id` (FK → classes)
- `max_score`, `disk`, `sort_order`
- `deleted_at`
- Timestamps

**Relationships:**
- belongs_to → `classes`
- has_many → `homework_students`

---

### **homework_students**
Student homework submissions

**Fields:**
- `id` (PK)
- `homework_id` (FK → homework), `student_id` (FK → users)
- `score`, `file`, `updated_file`, `corrected_file`
- `status`, `can_upload_updated_file`, `can_submit_after_due_date`
- `submitted_at`, `notes`, `disk`
- `deleted_at`
- Timestamps

**Relationships:**
- belongs_to → `homework`, `users`

---

### **quizzes**
Quiz assignments

**Fields:**
- `id` (PK)
- `name`, `date`, `due_date`
- `class_id` (FK → classes)
- `max_score`, `grading_type`, `pdf`
- `sort_order`, `deleted_at`
- Timestamps

**Relationships:**
- belongs_to → `classes`
- has_many → `quiz_students`, `quiz_gradings`
- morph_many → `exam_questions` (as examable)

---

### **quiz_students**
Student quiz submissions

**Fields:**
- `id` (PK)
- `quiz_id` (FK → quizzes), `student_id` (FK → users)
- `score`, `file`, `updated_file`, `disk`
- `is_finished`, `can_upload_updated_file`, `can_submit_after_due_date`
- `submitted_at`, `notes`
- `deleted_at`
- Timestamps

**Relationships:**
- belongs_to → `quizzes`, `users`

---

### **quiz_gradings**
Grading scale for quizzes

**Fields:**
- `id` (PK)
- `quiz_id` (FK → quizzes)
- `grade`, `from`, `to`

**Relationships:**
- belongs_to → `quizzes`

---

### **exams**
Exam assignments

**Fields:**
- `id` (PK)
- `name`, `date`, `due_date`
- `class_id` (FK → classes)
- `max_score`, `grading_type`, `pdf`
- `sort_order`, `deleted_at`
- Timestamps

**Relationships:**
- belongs_to → `classes`
- has_many → `exam_students`, `exam_gradings`
- morph_many → `exam_questions` (as examable)

---

### **exam_students**
Student exam submissions

**Fields:**
- `id` (PK)
- `exam_id` (FK → exams), `student_id` (FK → users)
- `score`, `file`, `updated_file`, `disk`
- `is_finished`, `can_upload_updated_file`, `can_submit_after_due_date`
- `submitted_at`, `notes`
- `deleted_at`
- Timestamps

**Relationships:**
- belongs_to → `exams`, `users`

---

### **exam_gradings**
Grading scale for exams

**Fields:**
- `id` (PK)
- `exam_id` (FK → exams)
- `grade`, `from`, `to`

**Relationships:**
- belongs_to → `exams`

---

### **classworks**
Classwork assignments

**Fields:**
- `id` (PK)
- `name`, `date`, `due_date`
- `class_id` (FK → classes)
- `max_score`, `grading_type`, `pdf`, `disk`
- `sort_order`, `deleted_at`
- Timestamps

**Relationships:**
- belongs_to → `classes`
- has_many → `classwork_students`, `classwork_gradings`

---

### **classwork_students**
Student classwork submissions

**Fields:**
- `id` (PK)
- `classwork_id` (FK → classworks), `student_id` (FK → users)
- `score`, `file`, `updated_file`, `disk`
- `is_finished`, `status`, `notes`
- `deleted_at`
- Timestamps

**Relationships:**
- belongs_to → `classworks`, `users`

---

### **classwork_gradings**
Grading scale for classworks

**Fields:**
- `id` (PK)
- `classwork_id` (FK → classworks)
- `grade`, `from`, `to`

**Relationships:**
- belongs_to → `classworks`

---

## Content Entities

### **class_videos**
Video content for classes

**Fields:**
- `id` (PK)
- `class_id` (FK → classes, nullable)
- `title`, `video`, `type`, `video_id` (UUID)
- `status` (enum: pending, transferring, uploaded, failed, ready) - Default: pending
- `message` - Status message
- `disk` - Storage disk
- `sort_order`, `sub_folder`
- `thumbnail`, `duration` - Duration in seconds
- `is_secure` - Boolean, default true
- `embedded_html` - Embedded video HTML
- Timestamps

**Relationships:**
- belongs_to → `classes`
- has_many → `video_views`
- belongs_to_many → `classes` (via `class_video_pivot`)

---

### **video_views**
Track video view progress

**Fields:**
- `id` (PK)
- `video_id` (FK → class_videos)
- `user_id` (FK → users)
- `progress`
- Timestamps

**Relationships:**
- belongs_to → `class_videos`, `users`

---

### **class_pdfs**
PDF documents for classes

**Fields:**
- `id` (PK)
- `title`, `pdf`, `type`
- `disk`, `sub_folder`, `sort_order`
- Timestamps

**Relationships:**
- belongs_to_many → `classes` (via `class_pdf_pivot`)

---

### **presentations**
General presentation files

**Fields:**
- `id` (PK)
- `title`, `file`, `size`
- `type` (enum: audio, video, document, image, pdf, other)
- Timestamps

---

### **past_paper_categories**
Categories for past exam papers

**Fields:**
- `id` (PK)
- `name`
- `class_category_id` (FK → class_categories)
- Timestamps

**Relationships:**
- belongs_to → `class_categories`
- has_many → `past_papers`

---

### **past_papers**
Past exam papers

**Fields:**
- `id` (PK)
- `name`, `file`, `disk`
- `category_id` (FK → past_paper_categories)
- Timestamps

**Relationships:**
- belongs_to → `past_paper_categories`

---

## Question Bank Entities

### **educational_parts**
Educational curriculum parts/units

**Fields:**
- `id` (PK)
- `name`
- `class_category_id` (FK → class_categories)
- `deleted_at`
- Timestamps

**Relationships:**
- belongs_to → `class_categories`
- belongs_to_many → `questions` (via `question_educational_parts`)

---

### **questions**
Exam/quiz questions

**Fields:**
- `id` (PK)
- `type`, `text`, `points`
- `complexity`
- `deleted_at`
- Timestamps

**Relationships:**
- has_many → `mcq_options`, `answered_questions`
- belongs_to_many → `educational_parts` (via `question_educational_parts`)
- morph_to_many → `exam_questions` (as examable)

---

### **mcq_options**
Multiple choice question options

**Fields:**
- `id` (PK)
- `question_id` (FK → questions)
- `text`, `order`
- Timestamps

**Relationships:**
- belongs_to → `questions`
- belongs_to_many → `answered_questions` (via `answered_question_options`)

---

### **answered_questions**
Student answers to questions

**Fields:**
- `id` (PK)
- `question_id` (FK → questions), `user_id` (FK → users)
- `text_answer`, `score`
- `examable_type`, `examable_id` (polymorphic)
- Timestamps

**Relationships:**
- belongs_to → `questions`, `users`
- belongs_to_many → `mcq_options` (via `answered_question_options`)
- morph_to → `examable`

---

### **exam_questions**
Questions assigned to exams/quizzes

**Fields:**
- `id` (PK)
- `examable_type`, `examable_id` (polymorphic)
- `question_id` (FK → questions)
- `order`
- Timestamps

**Relationships:**
- belongs_to → `questions`
- morph_to → `examable` (exams, quizzes)

---

## Administrative Entities

### **reports**
Generated reports

**Fields:**
- `id` (PK)
- `type`, `status` (enum: pending, completed, failed)
- `user_id` (FK → users), `class_id` (FK → classes)
- `file`, `disk`
- `from_date`, `to_date`
- Timestamps

**Relationships:**
- belongs_to → `users`, `classes`
- has_many → `report_files`

---

### **report_files**
Files associated with reports

**Fields:**
- `id` (PK)
- `user_id` (FK → users), `report_id` (FK → reports)
- `file`, `file_disk`
- Timestamps

**Relationships:**
- belongs_to → `users`, `reports`

---

### **t_a_notes**
Teaching Assistant notes per class

**Fields:**
- `id` (PK)
- `class_id` (FK → classes) ← Changed from user_id (Dec 2024)
- `note`
- Timestamps

**Relationships:**
- belongs_to → `classes`

---

### **t_a_transfers**
Teaching Assistant transfers per class

**Fields:**
- `id` (PK)
- `class_id` (FK → classes) ← Changed from user_id (Dec 2024)
- `note`
- Timestamps

**Relationships:**
- belongs_to → `classes`

---

### **t_a_droppeds**
Teaching Assistant dropouts per class

**Fields:**
- `id` (PK)
- `class_id` (FK → classes) ← Changed from user_id (Dec 2024)
- `note`, `reason`
- Timestamps

**Relationships:**
- belongs_to → `classes`

---

### **center_notes**
Notes for centers

**Fields:**
- `id` (PK)
- `center_id` (FK → centers)
- `text`
- Timestamps

**Relationships:**
- belongs_to → `centers`

---

### **events**
Events related to class categories

**Fields:**
- `id` (PK)
- `class_category_id` (FK → class_categories)
- `name`, `description`, `start_at`
- Timestamps

**Relationships:**
- belongs_to → `class_categories`

---

### **supervisor_teachers**
Supervisor-Teacher relationships

**Fields:**
- `id` (PK)
- `supervisor_id` (FK → users)
- `teacher_id` (FK → users)
- Timestamps

**Relationships:**
- belongs_to → `users` (as supervisor)
- belongs_to → `users` (as teacher)

---

## Integration Entities

### **zoom**
Zoom meeting configurations

**Fields:**
- `id` (PK)
- `topic`, `meeting_id`, `join_url`, `password`
- `main_type` (enum: meeting, webinar)
- `type`, `agenda`, `duration`, `start_time`
- `has_integration`
- Timestamps

**Relationships:**
- has_many → `zoom_sessions`
- belongs_to_many → `classes` (via `zoom_classes`)

---

### **zoom_sessions**
Actual Zoom meeting sessions tracking

**Fields:**
- `id` (PK)
- `zoom_id` (FK → zoom)
- `meeting_id`, `meeting_uuid` - Zoom identifiers
- `session_type` (enum: meeting, webinar)
- `started_at`, `ended_at`
- `is_active` - Session currently active
- `session_data` (JSON) - Additional data from Zoom
- Timestamps

**Relationships:**
- belongs_to → `zoom`
- has_many → `zoom_session_participants`

---

### **zoom_session_participants**
Individual participants in Zoom sessions

**Fields:**
- `id` (PK)
- `zoom_session_id` (FK → zoom_sessions)
- `user_id` (FK → users, nullable) - Link to system user
- `participant_id`, `participant_uuid` - Zoom participant IDs
- `participant_name`, `participant_email` - From Zoom
- `joined_at`, `left_at`
- `duration` - Participation duration in seconds
- `participant_data` (JSON) - Additional Zoom data
- Timestamps

**Relationships:**
- belongs_to → `zoom_sessions`
- belongs_to → `users` (optional)

**Purpose:** Enable automatic attendance tracking via Zoom webhooks

---

### **device_tokens**
Push notification device tokens

**Fields:**
- `id` (PK)
- `user_id` (FK → users)
- `token` (unique)
- Timestamps

**Relationships:**
- belongs_to → `users`

---

### **import_export_requests**
Import/Export job requests

**Fields:**
- `id` (PK)
- `type`, `file_name`, `status`, `entity_type`
- `url`, `error_message`, `errors`, `error_json`
- `user_id`, `disk`
- `progress`, `current_row`, `total_rows`
- `deleted_at`
- Timestamps

---

### **settings**
Application settings

**Fields:**
- `id` (PK)
- `key` (unique), `value`
- Timestamps

---

## Authorization Entities (Spatie)

### **permissions**
User permissions

**Fields:**
- `id` (PK)
- `name`, `display_name`, `group_name`, `guard_name`
- Timestamps

**Relationships:**
- belongs_to_many → `roles` (via `role_has_permissions`)
- morph_to_many → `users` (via `model_has_permissions`)

---

### **roles**
User roles

**Fields:**
- `id` (PK)
- `name`, `guard_name`
- Timestamps

**Relationships:**
- belongs_to_many → `permissions` (via `role_has_permissions`)
- morph_to_many → `users` (via `model_has_roles`)

---

## Pivot Tables

### **class_user**
Links users to classes

**Fields:**
- `id` (PK)
- `class_id` (FK → classes)
- `user_id` (FK → users)
- Timestamps

---

### **class_video_pivot**
Links videos to classes

**Fields:**
- `id` (PK)
- `class_id` (FK → classes)
- `video_id` (FK → class_videos)
- Timestamps

---

### **class_pdf_pivot**
Links PDFs to classes

**Fields:**
- `id` (PK)
- `class_id` (FK → classes)
- `pdf_id` (FK → class_pdfs)
- Timestamps

---

### **zoom_classes**
Links zoom meetings to classes

**Fields:**
- `id` (PK)
- `zoom_id` (FK → zoom)
- `class_id` (FK → classes)
- Timestamps

---

### **question_educational_parts**
Links questions to educational parts

**Fields:**
- `id` (PK)
- `question_id` (FK → questions)
- `educational_part_id` (FK → educational_parts)
- Timestamps

---

### **answered_question_options**
Links answered questions to MCQ options

**Fields:**
- `id` (PK)
- `answered_question_id` (FK → answered_questions)
- `mcq_option_id` (FK → mcq_options)
- Timestamps

---

### **role_has_permissions**
Links roles to permissions

**Fields:**
- `permission_id` (FK → permissions)
- `role_id` (FK → roles)

---

### **model_has_roles**
Polymorphic link for models to roles

**Fields:**
- `role_id` (FK → roles)
- `model_type`, `model_id`

---

### **model_has_permissions**
Polymorphic link for models to permissions

**Fields:**
- `permission_id` (FK → permissions)
- `model_type`, `model_id`

---

## System Tables

### **password_reset_tokens**
Password reset tokens

### **sessions**
User sessions

### **cache**
Cache storage

### **jobs**
Queue jobs

### **notifications**
User notifications

### **personal_access_tokens**
API tokens (Sanctum)

### **telescope_entries**
Laravel Telescope debugging entries

---

## Key Relationships Summary

### User Ecosystem
- **Users** can have multiple **Roles** and **Permissions**
- **Users** can be **Students** (with extended profiles in `student_profiles`)
- **Users** can enroll in multiple **Classes** (via `class_user`)
- **Supervisors** can supervise multiple **Teachers** (via `supervisor_teachers`)

### Class Ecosystem
- **Classes** belong to **Schools** and **Class Categories**
- **Classes** have **Sessions**, **Attendances**, **Homework**, **Quizzes**, **Exams**, **Classworks**
- **Classes** can have **Videos**, **PDFs**, and **Zoom meetings**

### Assessment Ecosystem
- **Homework**, **Quizzes**, **Exams**, **Classworks** all follow similar patterns:
  - Belong to a **Class**
  - Have student submissions (e.g., `homework_students`)
  - Support grading scales (e.g., `exam_gradings`)

### Question Bank Ecosystem
- **Questions** can be of different types (MCQ, text, etc.)
- **Questions** linked to **Educational Parts** (curriculum units)
- **Questions** can be used in **Exams** and **Quizzes** (polymorphic via `exam_questions`)
- **Students** provide **Answered Questions** with optional **MCQ Options**

---

## Database Design Patterns Used

1. **Polymorphic Relationships**: `exam_questions`, `answered_questions`, Spatie permissions
2. **Soft Deletes**: Many tables support soft deletes (`deleted_at`)
3. **Pivot Tables**: Many-to-many relationships with additional fields
4. **Enum Types**: For controlled value sets (gender, weekday, status, etc.)
5. **File Storage**: Disk column for flexible storage locations
6. **Audit Trail**: Timestamps on all tables, `created_by` on users
7. **Queue Management**: Import/export with progress tracking

