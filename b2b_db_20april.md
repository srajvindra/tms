# 🗄️ Database Schema Documentation

---

📋 Table: `activity`

| Column Name   | Type         | Description                        |
| ------------- | ------------ | ---------------------------------- |
| id            | BIGINT (PK)  | Primary key                        |
| lead_id       | BIGINT (FK)  | Reference to leads                 |
| activity_name | VARCHAR(150) | Name of the activity               |
| activity_date | DATETIME     | Date and time of the activity      |
| user_id       | BIGINT (FK)  | Reference to users                 |
| ping          | TINYINT      | Ping flag (default: 1)             |
| description   | TEXT         | Activity description               |
| comments      | TEXT         | Additional comments                |
| created_at    | TIMESTAMP    | Record created time                |
| updated_at    | TIMESTAMP    | Record updated time                |
| deleted_at    | TIMESTAMP    | Soft delete column                 |

---

📋 Table: `activity_log`

| Column Name | Type         | Description                        |
| ----------- | ------------ | ---------------------------------- |
| id          | BIGINT (PK)  | Primary key                        |
| lead_id     | BIGINT (FK)  | Reference to leads                 |
| user_id     | BIGINT (FK)  | Reference to users                 |
| action_type | VARCHAR(100) | Type of action performed           |
| entity      | VARCHAR(100) | Entity affected                    |
| description | TEXT         | Log description                    |
| source      | VARCHAR(100) | Source of the action               |
| created_at  | TIMESTAMP    | Record created time                |
| updated_at  | TIMESTAMP    | Record updated time                |
| deleted_at  | TIMESTAMP    | Soft delete column                 |

---

📋 Table: `amenities`

| Column Name  | Type         | Description                        |
| ------------ | ------------ | ---------------------------------- |
| id           | BIGINT (PK)  | Primary key                        |
| amenity_name | VARCHAR(100) | Unique name of the amenity         |
| description  | TEXT         | Amenity description                |
| is_active    | BOOLEAN      | Status (1 = active, 0 = inactive)  |
| created_at   | TIMESTAMP    | Record created time                |
| updated_at   | TIMESTAMP    | Record updated time                |

---

📋 Table: `availability_time_slots`

| Column Name     | Type        | Description                        |
| --------------- | ----------- | ---------------------------------- |
| id              | BIGINT (PK) | Primary key                        |
| availability_id | BIGINT (FK) | Reference to user_availabilities   |
| start_time      | TIME        | Slot start time                    |
| end_time        | TIME        | Slot end time                      |
| created_at      | TIMESTAMP   | Record created time                |
| updated_at      | TIMESTAMP   | Record updated time                |

---

📋 Table: `business_settings`

| Column Name          | Type         | Description                        |
| -------------------- | ------------ | ---------------------------------- |
| id                   | BIGINT (PK)  | Primary key                        |
| company_name         | VARCHAR(255) | Company name                       |
| company_email        | VARCHAR(255) | Company email address              |
| company_phone        | VARCHAR(50)  | Company phone number               |
| website              | VARCHAR(500) | Company website URL                |
| street_address       | VARCHAR(500) | Street address                     |
| city                 | VARCHAR(100) | City                               |
| state                | VARCHAR(100) | State                              |
| zip_code             | VARCHAR(20)  | ZIP code                           |
| business_description | TEXT         | Business description               |
| logo_id              | BIGINT (FK)  | Reference to master_documents      |
| uploaded_status      | VARCHAR(255) | Upload status of the logo          |
| created_at           | TIMESTAMP    | Record created time                |
| updated_at           | TIMESTAMP    | Record updated time                |

---

📋 Table: `departments`

| Column Name | Type         | Description                        |
| ----------- | ------------ | ---------------------------------- |
| id          | BIGINT (PK)  | Primary key                        |
| name        | VARCHAR(150) | Department name                    |
| description | TEXT         | Department description             |
| is_active   | BOOLEAN      | Status (1 = active, 0 = inactive)  |
| created_at  | TIMESTAMP    | Record created time                |
| updated_at  | TIMESTAMP    | Record updated time                |

---

📋 Table: `documents`

| Column Name          | Type         | Description                        |
| -------------------- | ------------ | ---------------------------------- |
| id                   | BIGINT (PK)  | Primary key                        |
| did                  | VARCHAR(20)  | Unique document identifier         |
| document_name        | VARCHAR(200) | Name of the document               |
| document_description | TEXT         | Document description               |
| document_type        | VARCHAR(100) | Type of document                   |
| type                 | VARCHAR(100) | Additional type classification     |
| created_by           | BIGINT (FK)  | Reference to users                 |
| status               | VARCHAR(50)  | Document status (default: active)  |
| created_at           | TIMESTAMP    | Record created time                |
| updated_at           | TIMESTAMP    | Record updated time                |
| deleted_at           | TIMESTAMP    | Soft delete column                 |

---

📋 Table: `domains`

| Column Name | Type         | Description                        |
| ----------- | ------------ | ---------------------------------- |
| id          | INT (PK)     | Primary key                        |
| domain      | VARCHAR(255) | Unique domain name                 |
| tenant_id   | VARCHAR(255) | Reference to tenants               |
| created_at  | TIMESTAMP    | Record created time                |
| updated_at  | TIMESTAMP    | Record updated time                |

---

📋 Table: `custom_fields`

| Column Name   | Type         | Description                                                                                  |
| ------------- | ------------ | -------------------------------------------------------------------------------------------- |
| id            | BIGINT (PK)  | Primary key                                                                                  |
| label_name    | VARCHAR(200) | Display label for the custom field                                                           |
| field_type    | ENUM         | Field type: text, number, currency, percentage, multiple_choice, date, url_website, checkbox |
| is_active     | TINYINT      | Status (1 = active, 0 = inactive)                                                            |
| is_required   | TINYINT      | Required flag (1 = required, 0 = optional)                                                   |
| default_value | VARCHAR(500) | Default value for the field                                                                  |
| created_by    | BIGINT (FK)  | Reference to users                                                                           |
| created_at    | TIMESTAMP    | Record created time                                                                          |
| updated_at    | TIMESTAMP    | Record updated time                                                                          |
| deleted_at    | TIMESTAMP    | Soft delete column                                                                           |

---

📋 Table: `custom_field_options`

| Column Name     | Type         | Description                        |
| --------------- | ------------ | ---------------------------------- |
| id              | BIGINT (PK)  | Primary key                        |
| custom_field_id | BIGINT (FK)  | Reference to custom_fields         |
| option_value    | VARCHAR(200) | Option value for the field         |
| sort_order      | INT          | Display sort order (default: 0)    |
| created_at      | TIMESTAMP    | Record created time                |
| updated_at      | TIMESTAMP    | Record updated time                |

---

📋 Table: `custom_field_process_map`

| Column Name     | Type        | Description                        |
| --------------- | ----------- | ---------------------------------- |
| id              | BIGINT (PK) | Primary key                        |
| custom_field_id | BIGINT (FK) | Reference to custom_fields         |
| process_id      | BIGINT (FK) | Reference to a process             |
| created_at      | TIMESTAMP   | Record created time                |
| updated_at      | TIMESTAMP   | Record updated time                |

---

📋 Table: `entity_types`

| Column Name     | Type         | Description                        |
| --------------- | ------------ | ---------------------------------- |
| id              | BIGINT (PK)  | Primary key                        |
| custom_field_id | BIGINT (FK)  | Reference to custom_fields         |
| entity_category | VARCHAR(100) | Category of the entity             |
| entity_name     | VARCHAR(100) | Name of the entity                 |
| created_at      | TIMESTAMP    | Record created time                |
| updated_at      | TIMESTAMP    | Record updated time                |

---

📋 Table: `events`

| Column Name     | Type         | Description                                    |
| --------------- | ------------ | ---------------------------------------------- |
| id              | BIGINT (PK)  | Primary key                                    |
| title           | VARCHAR(200) | Event title                                    |
| event_legend_id | BIGINT (FK)  | Reference to event_legends                     |
| start_datetime  | DATETIME     | Event start date and time                      |
| end_datetime    | DATETIME     | Event end date and time                        |
| description     | TEXT         | Event description                              |
| status          | ENUM         | Status: active, done, cancelled, confirmed     |
| google_event_id | VARCHAR(255) | Google Calendar event ID                       |
| event_type_id   | BIGINT (FK)  | Reference to event_types                       |
| type            | ENUM         | Event type: property or staff                  |
| created_at      | TIMESTAMP    | Record created time                            |
| updated_at      | TIMESTAMP    | Record updated time                            |

---

📋 Table: `event_contacts`

| Column Name | Type        | Description                        |
| ----------- | ----------- | ---------------------------------- |
| id          | BIGINT (PK) | Primary key                        |
| event_id    | BIGINT (FK) | Reference to events                |
| contact_id  | BIGINT      | Reference to a contact             |
| created_at  | TIMESTAMP   | Record created time                |
| updated_at  | TIMESTAMP   | Record updated time                |

---

📋 Table: `event_legends`

| Column Name | Type         | Description                        |
| ----------- | ------------ | ---------------------------------- |
| id          | BIGINT (PK)  | Primary key                        |
| name        | VARCHAR(150) | Legend name                        |
| description | TEXT         | Legend description                 |
| color       | VARCHAR(20)  | Hex color code (default: #000000)  |
| created_at  | TIMESTAMP    | Record created time                |
| updated_at  | TIMESTAMP    | Record updated time                |

---

📋 Table: `event_notes`

| Column Name | Type        | Description                        |
| ----------- | ----------- | ---------------------------------- |
| id          | BIGINT (PK) | Primary key                        |
| event_id    | BIGINT (FK) | Reference to events                |
| note        | TEXT        | Note content                       |
| created_at  | TIMESTAMP   | Record created time                |
| updated_at  | TIMESTAMP   | Record updated time                |

---

📋 Table: `event_reminders`

| Column Name    | Type        | Description                        |
| -------------- | ----------- | ---------------------------------- |
| id             | BIGINT (PK) | Primary key                        |
| minutes_before | INT         | Minutes before event to remind     |
| method         | ENUM        | Reminder method: email or popup    |
| created_at     | TIMESTAMP   | Record created time                |
| updated_at     | TIMESTAMP   | Record updated time                |

---

📋 Table: `event_types`

| Column Name | Type         | Description                        |
| ----------- | ------------ | ---------------------------------- |
| id          | BIGINT (PK)  | Primary key                        |
| name        | VARCHAR(255) | Event type name                    |
| slug        | VARCHAR(255) | Unique slug identifier             |
| created_at  | TIMESTAMP    | Record created time                |
| updated_at  | TIMESTAMP    | Record updated time                |

---

📋 Table: `event_users`

| Column Name | Type        | Description                        |
| ----------- | ----------- | ---------------------------------- |
| id          | BIGINT (PK) | Primary key                        |
| event_id    | BIGINT (FK) | Reference to events                |
| user_id     | BIGINT (FK) | Reference to users                 |
| role        | ENUM        | User role: owner or staff          |

---

📋 Table: `global_steps`

| Column Name | Type         | Description                            |
| ----------- | ------------ | -------------------------------------- |
| id          | BIGINT (PK)  | Primary key                            |
| name        | VARCHAR(100) | Step name                              |
| icon        | VARCHAR(100) | Icon identifier (e.g. email, call)     |
| color       | VARCHAR(30)  | Hex or Tailwind color class            |
| sort_order  | TINYINT      | Display sort order                     |
| is_active   | BOOLEAN      | Status (1 = active, 0 = inactive)      |
| created_at  | TIMESTAMP    | Record created time                    |
| updated_at  | TIMESTAMP    | Record updated time                    |

---

📋 Table: `global_step_params`

| Column Name | Type         | Description                            |
| ----------- | ------------ | -------------------------------------- |
| id          | BIGINT (PK)  | Primary key                            |
| name        | VARCHAR(100) | Parameter key/identifier               |
| icon        | VARCHAR(100) | Lucide icon name (e.g. FileText, Star) |
| title       | VARCHAR(255) | Tooltip/label shown in UI              |
| sort_order  | TINYINT      | Display sort order                     |
| is_active   | BOOLEAN      | Status (1 = active, 0 = inactive)      |
| created_at  | TIMESTAMP    | Record created time                    |
| updated_at  | TIMESTAMP    | Record updated time                    |

---

📋 Table: `google_calendar_tokens`

| Column Name  | Type         | Description                        |
| ------------ | ------------ | ---------------------------------- |
| id           | BIGINT (PK)  | Primary key                        |
| user_id      | BIGINT (FK)  | Reference to users (unique)        |
| token        | JSON         | OAuth token data                   |
| google_email | VARCHAR(255) | Associated Google email            |
| created_at   | TIMESTAMP    | Record created time                |
| updated_at   | TIMESTAMP    | Record updated time                |

---

📋 Table: `leads`

| Column Name   | Type         | Description                        |
| ------------- | ------------ | ---------------------------------- |
| id            | BIGINT (PK)  | Primary key                        |
| lid           | VARCHAR(20)  | Unique lead identifier             |
| pipeline_id   | BIGINT (FK)  | Reference to pipelines             |
| stage_id      | BIGINT (FK)  | Reference to pipeline_stages       |
| contacts_id   | BIGINT       | Reference to a contact             |
| customer_name | VARCHAR(200) | Name of the customer               |
| email_send    | TINYINT      | Email send flag (default: 1)       |
| email_id      | VARCHAR(150) | Customer email address             |
| phone_number  | VARCHAR(20)  | Customer phone number              |
| unit          | VARCHAR(100) | Associated unit                    |
| assignee_id   | BIGINT (FK)  | Reference to users (assignee)      |
| created_by    | BIGINT (FK)  | Reference to users (creator)       |
| source        | VARCHAR(100) | Lead source                        |
| last_touch    | DATETIME     | Last interaction date/time         |
| closed_at     | DATETIME     | Date/time lead was closed          |
| status        | TINYINT      | Lead status (default: 1)           |
| created_at    | TIMESTAMP    | Record created time                |
| updated_at    | TIMESTAMP    | Record updated time                |
| deleted_at    | TIMESTAMP    | Soft delete column                 |

---

📋 Table: `lead_assign_user`

| Column Name | Type        | Description                        |
| ----------- | ----------- | ---------------------------------- |
| id          | BIGINT (PK) | Primary key                        |
| lead_id     | BIGINT (FK) | Reference to leads                 |
| user_id     | BIGINT (FK) | Reference to users                 |
| created_at  | TIMESTAMP   | Record created time                |
| updated_at  | TIMESTAMP   | Record updated time                |
| deleted_at  | TIMESTAMP   | Soft delete column                 |

---

📋 Table: `lead_document`

| Column Name     | Type         | Description                        |
| --------------- | ------------ | ---------------------------------- |
| id              | BIGINT (PK)  | Primary key                        |
| ldid            | VARCHAR(20)  | Unique lead document identifier    |
| lead_id         | BIGINT (FK)  | Reference to leads                 |
| document_id     | BIGINT       | Reference to documents             |
| document_name   | VARCHAR(200) | Name of the document               |
| uploadedDate    | DATETIME     | Date document was uploaded         |
| document_type   | VARCHAR(100) | Type of document                   |
| comments        | TEXT         | Additional comments                |
| master_images_id| BIGINT       | Reference to master documents      |
| uploaded_status | VARCHAR(255) | Upload status                      |
| file_type       | VARCHAR(255) | File type/extension                |
| uploadedby      | BIGINT (FK)  | Reference to users (uploader)      |
| assignedstaff   | BIGINT (FK)  | Reference to users (staff)         |
| status          | VARCHAR(50)  | Document status (default: active)  |
| created_at      | TIMESTAMP    | Record created time                |
| updated_at      | TIMESTAMP    | Record updated time                |
| deleted_at      | TIMESTAMP    | Soft delete column                 |

---

📋 Table: `lead_notes`

| Column Name | Type        | Description                        |
| ----------- | ----------- | ---------------------------------- |
| id          | BIGINT (PK) | Primary key                        |
| lnid        | VARCHAR(20) | Unique lead note identifier        |
| lead_id     | BIGINT (FK) | Reference to leads                 |
| created_by  | BIGINT (FK) | Reference to users                 |
| created_at  | TIMESTAMP   | Record created time                |
| updated_at  | TIMESTAMP   | Record updated time                |
| deleted_at  | TIMESTAMP   | Soft delete column                 |

---

📋 Table: `lead_process`

| Column Name | Type        | Description                        |
| ----------- | ----------- | ---------------------------------- |
| id          | BIGINT (PK) | Primary key                        |
| lpid        | VARCHAR(20) | Unique lead process identifier     |
| lead_id     | BIGINT (FK) | Reference to leads                 |
| process_id  | BIGINT      | Reference to a process             |
| status      | VARCHAR(50) | Process status (default: active)   |
| start_date  | DATE        | Process start date                 |
| close_date  | DATE        | Process close date                 |
| due_date    | DATE        | Process due date                   |
| assignee_id | BIGINT (FK) | Reference to users (assignee)      |
| stage_id    | BIGINT (FK) | Reference to stages                |
| created_at  | TIMESTAMP   | Record created time                |
| updated_at  | TIMESTAMP   | Record updated time                |
| deleted_at  | TIMESTAMP   | Soft delete column                 |

---

📋 Table: `lead_properties`

| Column Name | Type        | Description                        |
| ----------- | ----------- | ---------------------------------- |
| id          | BIGINT (PK) | Primary key                        |
| lprid       | VARCHAR(20) | Unique lead property identifier    |
| property_id | BIGINT (FK) | Reference to properties            |
| lead_id     | BIGINT (FK) | Reference to leads                 |
| created_at  | TIMESTAMP   | Record created time                |
| updated_at  | TIMESTAMP   | Record updated time                |
| deleted_at  | TIMESTAMP   | Soft delete column                 |

---

📋 Table: `lead_sources`

| Column Name | Type         | Description                        |
| ----------- | ------------ | ---------------------------------- |
| id          | BIGINT (PK)  | Primary key                        |
| name        | VARCHAR(100) | Unique source name                 |
| created_at  | TIMESTAMP    | Record created time                |
| updated_at  | TIMESTAMP    | Record updated time                |

---

📋 Table: `lead_stages`

| Column Name   | Type         | Description                                 |
| ------------- | ------------ | ------------------------------------------- |
| id            | BIGINT (PK)  | Primary key                                 |
| psid          | VARCHAR(20)  | Unique pipeline stage identifier            |
| pipeline_id   | BIGINT (FK)  | Reference to pipelines                      |
| created_by    | BIGINT (FK)  | Reference to users (creator)                |
| name          | VARCHAR(150) | Stage name                                  |
| description   | TEXT         | Stage description                           |
| type          | VARCHAR(50)  | Stage type (default: owner)                 |
| status        | VARCHAR(50)  | Stage status (default: active)              |
| stage_order   | INT          | Display order of the stage                  |
| color         | VARCHAR(30)  | Custom color (null = use default_color)     |
| default_color | VARCHAR(30)  | Fallback color assigned at seeding time     |
| created_at    | TIMESTAMP    | Record created time                         |
| updated_at    | TIMESTAMP    | Record updated time                         |
| deleted_at    | TIMESTAMP    | Soft delete column                          |

---

📋 Table: `lead_stage_steps`

| Column Name                   | Type         | Description                          |
| ----------------------------- | ------------ | ------------------------------------ |
| id                            | BIGINT (PK)  | Primary key                          |
| lead_stage_id             	| BIGINT (FK)  | Reference to lead_stages             |
| pipeline_stage_step_master_id | BIGINT (FK)  | Reference to global_steps            |
| name                          | VARCHAR(255) | Step name                            |
| step_type                     | VARCHAR(50)  | Type of step                         |
| timing                        | VARCHAR(100) | Timing info (e.g. after, before)     |
| delay                         | INT          | Delay value                          |
| unit                          | VARCHAR(50)  | Delay unit: minutes, hours, days     |
| day                           | INT          | Day value                            |
| sort_order                    | SMALLINT     | Display sort order                   |
| is_active                     | BOOLEAN      | Status (1 = active, 0 = inactive)    |
| created_at                    | TIMESTAMP    | Record created time                  |
| updated_at                    | TIMESTAMP    | Record updated time                  |
| deleted_at                    | TIMESTAMP    | Soft delete column                   |

---

📋 Table: `lead_stage_step_connect_params`

| Column Name            | Type        | Description                        |
| ---------------------- | ----------- | ---------------------------------- |
| id                     | BIGINT (PK) | Primary key                        |
| lead_stage_step_id     | BIGINT (FK) | Reference to lead_stage_steps      |
| global_step_param_id   | BIGINT (FK) | Reference to global_step_params    |
| created_at             | TIMESTAMP   | Record created time                |
| updated_at             | TIMESTAMP   | Record updated time                |

---

📋 Table: `lead_stage_step_instructions`

| Column Name            | Type        | Description                        |
| ---------------------- | ----------- | ---------------------------------- |
| id                     | BIGINT (PK) | Primary key                        |
| lead_stage_step_id     | BIGINT (FK) | Reference to lead_stage_steps      |
| instruction_message    | TEXT        | Instruction content                |
| created_at             | TIMESTAMP   | Record created time                |
| updated_at             | TIMESTAMP   | Record updated time                |

---

📋 Table: `lead_stage_step_escalation_rules`

| Column Name            | Type        | Description                        |
| ---------------------- | ----------- | ---------------------------------- |
| id                     | BIGINT (PK) | Primary key                        |
| lead_stage_step_id     | BIGINT (FK) | Reference to lead_stage_steps      |
| time                   | INT         | Time value for escalation          |
| time_unit              | ENUM        | Time unit: hours or days           |
| escalate_to_role_id    | BIGINT      | Reference to roles (escalation)    |
| created_at             | TIMESTAMP   | Record created time                |
| updated_at             | TIMESTAMP   | Record updated time                |

---

📋 Table: `lead_stage_step_assign_staff`

| Column Name            | Type        | Description                        |
| ---------------------- | ----------- | ---------------------------------- |
| id                     | BIGINT (PK) | Primary key                        |
| lead_stage_step_id     | BIGINT (FK) | Reference to lead_stage_steps      |
| role_id                | BIGINT      | Reference to roles                 |
| user_id                | BIGINT      | Reference to users                 |
| created_at             | TIMESTAMP   | Record created time                |
| updated_at             | TIMESTAMP   | Record updated time                |

---

📋 Table: `lead_stage_step_time_limits`

| Column Name            | Type        | Description                        |
| ---------------------- | ----------- | ---------------------------------- |
| id                     | BIGINT (PK) | Primary key                        |
| lead_stage_step_id | BIGINT (FK) | Reference to lead_stage_steps  |
| time                   | BIGINT      | Time limit value                   |
| created_at             | TIMESTAMP   | Record created time                |
| updated_at             | TIMESTAMP   | Record updated time                |

---

📋 Table: `lead_stage_step_display_conditions`

| Column Name            | Type         | Description                                        |
| ---------------------- | ------------ | -------------------------------------------------- |
| id                     | BIGINT (PK)  | Primary key                                        |
| lead_stage_step_id | BIGINT (FK)  | Reference to lead_stage_steps                  |
| display_condition      | VARCHAR(255) | Condition field name                               |
| field_type             | VARCHAR(255) | Type of the field                                  |
| operator               | ENUM         | Operator: is, isnot, contain, doesnotcontain       |
| value                  | VARCHAR(255) | Condition value                                    |
| created_at             | TIMESTAMP    | Record created time                                |
| updated_at             | TIMESTAMP    | Record updated time                                |

---

📋 Table: `lead_communication_templates`

| Column Name | Type         | Description                        |
| ----------- | ------------ | ---------------------------------- |
| id          | BIGINT (PK)  | Primary key                        |
| name        | VARCHAR(255) | Template name                      |
| type        | VARCHAR(100) | Template type (default: email)     |
| pipeline_id | BIGINT (FK)  | Reference to pipelines             |
| to_address  | VARCHAR(255) | Main recipient email               |
| cc_address  | VARCHAR(255) | CC email addresses                 |
| bcc_address | VARCHAR(255) | BCC email addresses                |
| subject     | VARCHAR(255) | Message subject                    |
| message     | TEXT         | Template content/message           |
| is_active   | BOOLEAN      | Status (1 = active, 0 = inactive)  |
| created_by  | BIGINT (FK)  | Reference to users (creator)       |
| created_at  | TIMESTAMP    | Record created time                |
| updated_at  | TIMESTAMP    | Record updated time                |
| deleted_at  | TIMESTAMP    | Soft delete column                 |

---

📋 Table: `lead_stage_step_template_links`

| Column Name            | Type        | Description                        |
| ---------------------- | ----------- | ---------------------------------- |
| id                     | BIGINT (PK) | Primary key                        |
| lead_stage_step_id     | BIGINT (FK) | Reference to lead_stage_steps  |
| template_id            | BIGINT (FK) | Reference to templates             |
| created_at             | TIMESTAMP   | Record created time                |
| updated_at             | TIMESTAMP   | Record updated time                |

---

📋 Table: `lead_task`   // not required, lead_stage_step can work for it

| Column Name | Type         | Description                        |
| ----------- | ------------ | ---------------------------------- |
| id          | BIGINT (PK)  | Primary key                        |
| ltid        | VARCHAR(20)  | Unique lead task identifier        |
| lead_id     | BIGINT (FK)  | Reference to leads                 |
| workflow_id | BIGINT       | Reference to a workflow            |
| tasktype    | VARCHAR(50)  | Type of task                       |
| task_name   | VARCHAR(200) | Task name                          |
| due_date    | DATETIME     | Task due date                      |
| priority    | VARCHAR(50)  | Task priority level                |
| status      | VARCHAR(50)  | Task status (default: active)      |
| assigned_to | BIGINT (FK)  | Reference to users (assignee)      |
| created_at  | TIMESTAMP    | Record created time                |
| updated_at  | TIMESTAMP    | Record updated time                |
| deleted_at  | TIMESTAMP    | Soft delete column                 |

---

📋 Table: `master_documents`

| Column Name     | Type          | Description                        |
| --------------- | ------------- | ---------------------------------- |
| id              | BIGINT (PK)   | Primary key                        |
| original_name   | VARCHAR(255)  | Original file name                 |
| file_name       | VARCHAR(255)  | Stored file name                   |
| file_path       | VARCHAR(1000) | File storage path                  |
| file_type       | VARCHAR(100)  | File MIME type                     |
| file_size_bytes | INT           | File size in bytes                 |
| status          | ENUM          | Status: active or inactive         |
| uploaded_by     | BIGINT (FK)   | Reference to users (uploader)      |
| created_at      | TIMESTAMP     | Record created time                |
| updated_at      | TIMESTAMP     | Record updated time                |
| deleted_at      | TIMESTAMP     | Soft delete column                 |

---

📋 Table: `notifications`

| Column Name | Type         | Description                        |
| ----------- | ------------ | ---------------------------------- |
| id          | BIGINT (PK)  | Primary key                        |
| event_id    | BIGINT (FK)  | Reference to events                |
| type        | VARCHAR(255) | Notification type                  |
| title       | VARCHAR(255) | Notification title                 |
| come_from   | VARCHAR(255) | Origin/source of notification      |
| status      | ENUM         | Status: active or inactive         |
| created_at  | TIMESTAMP    | Record created time                |
| updated_at  | TIMESTAMP    | Record updated time                |
| deleted_at  | TIMESTAMP    | Soft delete column                 |

---

📋 Table: `owner_properties`

| Column Name  | Type        | Description                        |
| ------------ | ----------- | ---------------------------------- |
| id           | BIGINT (PK) | Primary key                        |
| user_id      | BIGINT (FK) | Reference to users (owner)         |
| property_id  | BIGINT (FK) | Reference to properties            |
| generated_at | DATETIME    | Date/time record was generated     |
| created_at   | TIMESTAMP   | Record created time                |
| updated_at   | TIMESTAMP   | Record updated time                |

---

📋 Table: `pipelines`

| Column Name     | Type         | Description                        |
| --------------- | ------------ | ---------------------------------- |
| id              | BIGINT (PK)  | Primary key                        |
| pid             | VARCHAR(20)  | Unique pipeline identifier         |
| pipeline_name   | VARCHAR(150) | Pipeline name                      |
| type            | ENUM         | Pipeline type: owner or lease      |
| overview_title  | VARCHAR(255) | Overview section title             |
| overview_content| TEXT         | Overview section content           |
| singular_name   | VARCHAR(255) | Singular display name              |
| status          | TINYINT      | Pipeline status (default: 1)       |
| created_by      | BIGINT (FK)  | Reference to users (creator)       |
| created_at      | TIMESTAMP    | Record created time                |
| updated_at      | TIMESTAMP    | Record updated time                |
| deleted_at      | TIMESTAMP    | Soft delete column                 |

---

📋 Table: `pipeline_communication_templates`

| Column Name | Type         | Description                        |
| ----------- | ------------ | ---------------------------------- |
| id          | BIGINT (PK)  | Primary key                        |
| name        | VARCHAR(255) | Template name                      |
| type        | VARCHAR(100) | Template type (default: email)     |
| pipeline_id | BIGINT (FK)  | Reference to pipelines             |
| to_address  | VARCHAR(255) | Main recipient email               |
| cc_address  | VARCHAR(255) | CC email addresses                 |
| bcc_address | VARCHAR(255) | BCC email addresses                |
| subject     | VARCHAR(255) | Message subject                    |
| message     | TEXT         | Template content/message           |
| is_active   | BOOLEAN      | Status (1 = active, 0 = inactive)  |
| created_by  | BIGINT (FK)  | Reference to users (creator)       |
| created_at  | TIMESTAMP    | Record created time                |
| updated_at  | TIMESTAMP    | Record updated time                |
| deleted_at  | TIMESTAMP    | Soft delete column                 |

---

📋 Table: `pipeline_processes`

| Column Name | Type         | Description                        |
| ----------- | ------------ | ---------------------------------- |
| id          | BIGINT (PK)  | Primary key                        |
| pipeline_id | BIGINT (FK)  | Reference to pipelines             |
| process_id  | BIGINT       | Reference to a process             |
| name        | VARCHAR(150) | Process name                       |
| description | TEXT         | Process description                |
| type        | VARCHAR(50)  | Process type                       |
| status      | VARCHAR(50)  | Process status (default: active)   |
| start_date  | DATE         | Process start date                 |
| close_date  | DATE         | Process close date                 |
| due_date    | DATE         | Process due date                   |
| assignee_id | BIGINT (FK)  | Reference to users (assignee)      |
| stage_id    | BIGINT (FK)  | Reference to stages                |
| created_at  | TIMESTAMP    | Record created time                |
| updated_at  | TIMESTAMP    | Record updated time                |
| deleted_at  | TIMESTAMP    | Soft delete column                 |

---

📋 Table: `pipeline_stages`

| Column Name   | Type         | Description                                 |
| ------------- | ------------ | ------------------------------------------- |
| id            | BIGINT (PK)  | Primary key                                 |
| psid          | VARCHAR(20)  | Unique pipeline stage identifier            |
| pipeline_id   | BIGINT (FK)  | Reference to pipelines                      |
| stage_id      | BIGINT (FK)  | Reference to stages                         |
| created_by    | BIGINT (FK)  | Reference to users (creator)                |
| name          | VARCHAR(150) | Stage name                                  |
| description   | TEXT         | Stage description                           |
| type          | VARCHAR(50)  | Stage type (default: owner)                 |
| status        | VARCHAR(50)  | Stage status (default: active)              |
| stage_order   | INT          | Display order of the stage                  |
| color         | VARCHAR(30)  | Custom color (null = use default_color)     |
| default_color | VARCHAR(30)  | Fallback color assigned at seeding time     |
| created_at    | TIMESTAMP    | Record created time                         |
| updated_at    | TIMESTAMP    | Record updated time                         |
| deleted_at    | TIMESTAMP    | Soft delete column                          |

---

📋 Table: `pipeline_stage_steps`

| Column Name                   | Type         | Description                          |
| ----------------------------- | ------------ | ------------------------------------ |
| id                            | BIGINT (PK)  | Primary key                          |
| pipeline_stage_id             | BIGINT (FK)  | Reference to pipeline_stages         |
| pipeline_stage_step_master_id | BIGINT (FK)  | Reference to global_steps            |
| name                          | VARCHAR(255) | Step name                            |
| step_type                     | VARCHAR(50)  | Type of step                         |
| timing                        | VARCHAR(100) | Timing info (e.g. after, before)     |
| delay                         | INT          | Delay value                          |
| unit                          | VARCHAR(50)  | Delay unit: minutes, hours, days     |
| day                           | INT          | Day value                            |
| sort_order                    | SMALLINT     | Display sort order                   |
| is_active                     | BOOLEAN      | Status (1 = active, 0 = inactive)    |
| created_at                    | TIMESTAMP    | Record created time                  |
| updated_at                    | TIMESTAMP    | Record updated time                  |
| deleted_at                    | TIMESTAMP    | Soft delete column                   |

---

📋 Table: `pipeline_stage_step_assign_staff`

| Column Name            | Type        | Description                        |
| ---------------------- | ----------- | ---------------------------------- |
| id                     | BIGINT (PK) | Primary key                        |
| pipeline_stage_step_id | BIGINT (FK) | Reference to pipeline_stage_steps  |
| role_id                | BIGINT      | Reference to roles                 |
| user_id                | BIGINT      | Reference to users                 |
| created_at             | TIMESTAMP   | Record created time                |
| updated_at             | TIMESTAMP   | Record updated time                |

---

📋 Table: `pipeline_stage_step_connect_params`

| Column Name            | Type        | Description                        |
| ---------------------- | ----------- | ---------------------------------- |
| id                     | BIGINT (PK) | Primary key                        |
| pipeline_stage_step_id | BIGINT (FK) | Reference to pipeline_stage_steps  |
| global_step_param_id   | BIGINT (FK) | Reference to global_step_params    |
| created_at             | TIMESTAMP   | Record created time                |
| updated_at             | TIMESTAMP   | Record updated time                |

---

📋 Table: `pipeline_stage_step_display_conditions`

| Column Name            | Type         | Description                                        |
| ---------------------- | ------------ | -------------------------------------------------- |
| id                     | BIGINT (PK)  | Primary key                                        |
| pipeline_stage_step_id | BIGINT (FK)  | Reference to pipeline_stage_steps                  |
| display_condition      | VARCHAR(255) | Condition field name                               |
| field_type             | VARCHAR(255) | Type of the field                                  |
| operator               | ENUM         | Operator: is, isnot, contain, doesnotcontain       |
| value                  | VARCHAR(255) | Condition value                                    |
| created_at             | TIMESTAMP    | Record created time                                |
| updated_at             | TIMESTAMP    | Record updated time                                |

---

📋 Table: `pipeline_stage_step_escalation_rules`

| Column Name            | Type        | Description                        |
| ---------------------- | ----------- | ---------------------------------- |
| id                     | BIGINT (PK) | Primary key                        |
| pipeline_stage_step_id | BIGINT (FK) | Reference to pipeline_stage_steps  |
| time                   | INT         | Time value for escalation          |
| time_unit              | ENUM        | Time unit: hours or days           |
| escalate_to_role_id    | BIGINT      | Reference to roles (escalation)    |
| created_at             | TIMESTAMP   | Record created time                |
| updated_at             | TIMESTAMP   | Record updated time                |

---

📋 Table: `pipeline_stage_step_instructions`

| Column Name            | Type        | Description                        |
| ---------------------- | ----------- | ---------------------------------- |
| id                     | BIGINT (PK) | Primary key                        |
| pipeline_stage_step_id | BIGINT (FK) | Reference to pipeline_stage_steps  |
| instruction_message    | TEXT        | Instruction content                |
| created_at             | TIMESTAMP   | Record created time                |
| updated_at             | TIMESTAMP   | Record updated time                |

---

📋 Table: `pipeline_stage_step_template_links`

| Column Name            | Type        | Description                        |
| ---------------------- | ----------- | ---------------------------------- |
| id                     | BIGINT (PK) | Primary key                        |
| pipeline_stage_step_id | BIGINT (FK) | Reference to pipeline_stage_steps  |
| template_id            | BIGINT (FK) | Reference to templates             |
| created_at             | TIMESTAMP   | Record created time                |
| updated_at             | TIMESTAMP   | Record updated time                |

---

📋 Table: `pipeline_stage_step_time_limits`

| Column Name            | Type        | Description                        |
| ---------------------- | ----------- | ---------------------------------- |
| id                     | BIGINT (PK) | Primary key                        |
| pipeline_stage_step_id | BIGINT (FK) | Reference to pipeline_stage_steps  |
| time                   | BIGINT      | Time limit value                   |
| created_at             | TIMESTAMP   | Record created time                |
| updated_at             | TIMESTAMP   | Record updated time                |

---

📋 Table: `properties`

| Column Name           | Type         | Description                        |
| --------------------- | ------------ | ---------------------------------- |
| id                    | BIGINT (PK)  | Primary key                        |
| llc_id                | BIGINT (FK)  | Reference to properties_category   |
| property_type_id      | BIGINT (FK)  | Reference to property_types        |
| property_name         | VARCHAR(255) | Property name                      |
| address               | TEXT         | Property street address            |
| city                  | VARCHAR(255) | City                               |
| state                 | VARCHAR(100) | State                              |
| zip_code              | VARCHAR(20)  | ZIP code                           |
| description           | TEXT         | Property description               |
| tax_authority         | VARCHAR(255) | Tax authority name                 |
| year_build            | INT          | Year the property was built        |
| management_start_date | DATE         | Management start date              |
| status                | ENUM         | Status: active or inactive         |
| is_pma                | BOOLEAN      | PMA flag (0 = no, 1 = yes)         |
| created_by            | BIGINT (FK)  | Reference to users (creator)       |
| created_at            | TIMESTAMP    | Record created time                |
| updated_at            | TIMESTAMP    | Record updated time                |
| deleted_at            | TIMESTAMP    | Soft delete column                 |

---

📋 Table: `properties_category`

| Column Name | Type         | Description                        |
| ----------- | ------------ | ---------------------------------- |
| id          | BIGINT (PK)  | Primary key                        |
| name        | VARCHAR(255) | Category name                      |
| description | TEXT         | Category description               |
| is_active   | BOOLEAN      | Status (1 = active, 0 = inactive)  |
| created_at  | TIMESTAMP    | Record created time                |
| updated_at  | TIMESTAMP    | Record updated time                |

---

📋 Table: `property_assigners`

| Column Name  | Type         | Description                        |
| ------------ | ------------ | ---------------------------------- |
| id           | BIGINT (PK)  | Primary key                        |
| property_id  | BIGINT (FK)  | Reference to properties            |
| title        | VARCHAR(20)  | Title/salutation                   |
| first_name   | VARCHAR(255) | First name                         |
| last_name    | VARCHAR(255) | Last name                          |
| phone_number | VARCHAR(30)  | Phone number                       |
| created_at   | TIMESTAMP    | Record created time                |
| updated_at   | TIMESTAMP    | Record updated time                |
| deleted_at   | TIMESTAMP    | Soft delete column                 |

---

📋 Table: `property_bank_accounts`

| Column Name      | Type         | Description                        |
| ---------------- | ------------ | ---------------------------------- |
| id               | BIGINT (PK)  | Primary key                        |
| property_id      | BIGINT (FK)  | Reference to properties            |
| gl_code          | VARCHAR(20)  | General ledger code                |
| gl_level         | VARCHAR(100) | General ledger level               |
| account_level    | VARCHAR(150) | Account level description          |
| bank_account_name| VARCHAR(200) | Bank account name                  |
| bank_account_id  | BIGINT       | Reference to bank account          |
| created_at       | TIMESTAMP    | Record created time                |
| updated_at       | TIMESTAMP    | Record updated time                |

---

📋 Table: `property_documents`

| Column Name        | Type        | Description                        |
| ------------------ | ----------- | ---------------------------------- |
| id                 | BIGINT (PK) | Primary key                        |
| property_id        | BIGINT (FK) | Reference to properties            |
| image_type         | VARCHAR(50) | Image/document type                |
| master_document_id | BIGINT (FK) | Reference to master_documents      |
| type               | VARCHAR(50) | Additional type classification     |
| upload_status      | VARCHAR(50) | Upload status (default: pending)   |
| created_at         | TIMESTAMP   | Record created time                |
| updated_at         | TIMESTAMP   | Record updated time                |

---

📋 Table: `property_groups`

| Column Name | Type         | Description                        |
| ----------- | ------------ | ---------------------------------- |
| id          | BIGINT (PK)  | Primary key                        |
| name        | VARCHAR(255) | Group name                         |
| description | TEXT         | Group description                  |
| is_active   | BOOLEAN      | Status (1 = active, 0 = inactive)  |
| created_at  | TIMESTAMP    | Record created time                |
| updated_at  | TIMESTAMP    | Record updated time                |

---

📋 Table: `property_groups_included`

| Column Name       | Type        | Description                        |
| ----------------- | ----------- | ---------------------------------- |
| id                | BIGINT (PK) | Primary key                        |
| property_id       | BIGINT (FK) | Reference to properties            |
| property_group_id | BIGINT (FK) | Reference to property_groups       |

---

📋 Table: `property_types`

| Column Name | Type         | Description                        |
| ----------- | ------------ | ---------------------------------- |
| id          | BIGINT (PK)  | Primary key                        |
| name        | VARCHAR(255) | Unique property type name          |
| description | TEXT         | Type description                   |
| is_active   | BOOLEAN      | Status (1 = active, 0 = inactive)  |
| created_at  | TIMESTAMP    | Record created time                |
| updated_at  | TIMESTAMP    | Record updated time                |

---

📋 Table: `property_units`

| Column Name    | Type           | Description                        |
| -------------- | -------------- | ---------------------------------- |
| id             | BIGINT (PK)    | Primary key                        |
| property_id    | BIGINT (FK)    | Reference to properties            |
| unit_number    | VARCHAR(50)    | Unit number/identifier             |
| status         | ENUM           | Status: Occupied or Vacant         |
| bedrooms       | INT            | Number of bedrooms                 |
| bathrooms      | DECIMAL(4,1)   | Number of bathrooms                |
| square_footage | DECIMAL(10,2)  | Square footage of unit             |
| monthly_rent   | DECIMAL(10,2)  | Monthly rent amount                |
| start_date     | DATE           | Lease or availability start date   |
| created_at     | TIMESTAMP      | Record created time                |
| updated_at     | TIMESTAMP      | Record updated time                |
| deleted_at     | TIMESTAMP      | Soft delete column                 |

---

📋 Table: `property_unit_amenities`

| Column Name | Type        | Description                        |
| ----------- | ----------- | ---------------------------------- |
| id          | BIGINT (PK) | Primary key                        |
| unit_id     | BIGINT (FK) | Reference to property_units        |
| property_id | BIGINT (FK) | Reference to properties            |
| amenity_id  | BIGINT (FK) | Reference to amenities             |
| created_at  | TIMESTAMP   | Record created time                |
| updated_at  | TIMESTAMP   | Record updated time                |

---

📋 Table: `property_unit_documents`

| Column Name        | Type         | Description                        |
| ------------------ | ------------ | ---------------------------------- |
| id                 | BIGINT (PK)  | Primary key                        |
| unit_id            | BIGINT (FK)  | Reference to property_units        |
| master_document_id | BIGINT (FK)  | Reference to master_documents      |
| document_name      | VARCHAR(255) | Document name                      |
| document_type      | VARCHAR(100) | Type of document                   |
| file_type          | VARCHAR(50)  | File type/extension                |
| upload_status      | VARCHAR(50)  | Upload status (default: uploaded)  |
| created_at         | TIMESTAMP    | Record created time                |
| updated_at         | TIMESTAMP    | Record updated time                |

---

📋 Table: `property_unit_fees`

| Column Name    | Type          | Description                        |
| -------------- | ------------- | ---------------------------------- |
| id             | BIGINT (PK)   | Primary key                        |
| unit_id        | BIGINT (FK)   | Reference to property_units        |
| nsf_fees       | DECIMAL(10,2) | NSF fee amount                     |
| fees_type      | VARCHAR(50)   | Fee type                           |
| fees           | DECIMAL(10,2) | Fee amount                         |
| min_fees       | DECIMAL(10,2) | Minimum fee amount                 |
| max_fees       | DECIMAL(10,2) | Maximum fee amount                 |
| late_fee_type  | VARCHAR(50)   | Late fee calculation type          |
| base_late_fee  | DECIMAL(10,2) | Base late fee amount               |
| grace_period   | INT           | Grace period in days               |
| created_at     | TIMESTAMP     | Record created time                |
| updated_at     | TIMESTAMP     | Record updated time                |

---

📋 Table: `property_unit_lease_settings`

| Column Name           | Type          | Description                        |
| --------------------- | ------------- | ---------------------------------- |
| id                    | BIGINT (PK)   | Primary key                        |
| unit_id               | BIGINT (FK)   | Reference to property_units        |
| lease_template        | VARCHAR(255)  | Lease template name                |
| default_method        | VARCHAR(100)  | Default payment method             |
| lease_fee_type        | VARCHAR(50)   | Lease fee type                     |
| lease_fee_percentage  | DECIMAL(8,4)  | Lease fee percentage               |
| renewal_fee_type      | VARCHAR(50)   | Renewal fee type                   |
| renewal_fee_percentage| DECIMAL(8,4)  | Renewal fee percentage             |
| created_at            | TIMESTAMP     | Record created time                |
| updated_at            | TIMESTAMP     | Record updated time                |

---

📋 Table: `property_unit_maintenance`

| Column Name         | Type        | Description                        |
| ------------------- | ----------- | ---------------------------------- |
| id                  | BIGINT (PK) | Primary key                        |
| unit_id             | BIGINT (FK) | Reference to property_units        |
| covered_by_warranty | BOOLEAN     | Warranty coverage flag             |
| entry_pre_authorized| BOOLEAN     | Pre-authorized entry flag          |
| notes               | TEXT        | Maintenance notes                  |
| instructions        | TEXT        | Entry/maintenance instructions     |
| created_at          | TIMESTAMP   | Record created time                |
| updated_at          | TIMESTAMP   | Record updated time                |

---

📋 Table: `property_unit_settings`

| Column Name         | Type        | Description                        |
| ------------------- | ----------- | ---------------------------------- |
| id                  | BIGINT (PK) | Primary key                        |
| unit_id             | BIGINT (FK) | Reference to property_units        |
| waive_fees_when_vacant | BOOLEAN  | Waive fees when unit is vacant     |
| created_at          | TIMESTAMP   | Record created time                |
| updated_at          | TIMESTAMP   | Record updated time                |

---

📋 Table: `roles`

| Column Name | Type         | Description                        |
| ----------- | ------------ | ---------------------------------- |
| id          | BIGINT (PK)  | Primary key                        |
| name        | VARCHAR(255) | Role name                          |
| guard_name  | VARCHAR(255) | Guard name for this role           |
| created_at  | TIMESTAMP    | Record created time                |
| updated_at  | TIMESTAMP    | Record updated time                |

---

📋 Table: `stages`

| Column Name | Type         | Description                        |
| ----------- | ------------ | ---------------------------------- |
| id          | BIGINT (PK)  | Primary key                        |
| name        | VARCHAR(150) | Stage name                         |
| description | TEXT         | Stage description                  |
| type        | VARCHAR(50)  | Stage type                         |
| created_by  | BIGINT (FK)  | Reference to users (creator)       |
| stage_order | INT          | Display sort order                 |
| created_at  | TIMESTAMP    | Record created time                |
| updated_at  | TIMESTAMP    | Record updated time                |
| deleted_at  | TIMESTAMP    | Soft delete column                 |

---

📋 Table: `templates`

| Column Name  | Type         | Description                        |
| ------------ | ------------ | ---------------------------------- |
| id           | BIGINT (PK)  | Primary key                        |
| name         | VARCHAR(255) | Template name                      |
| type         | ENUM         | Template type: email or sms        |
| to_addresses | TEXT         | To addresses (override)            |
| cc_addresses | TEXT         | CC email addresses                 |
| bcc_addresses| TEXT         | BCC email addresses                |
| subject      | VARCHAR(255) | Email subject (required for email) |
| message      | LONGTEXT     | Template message content           |
| is_active    | BOOLEAN      | Status (1 = active, 0 = inactive)  |
| created_at   | TIMESTAMP    | Record created time                |
| updated_at   | TIMESTAMP    | Record updated time                |
| deleted_at   | TIMESTAMP    | Soft delete column                 |

---

📋 Table: `tenants`

| Column Name | Type         | Description                        |
| ----------- | ------------ | ---------------------------------- |
| id          | VARCHAR(255) | Primary key (string ID)            |
| created_at  | TIMESTAMP    | Record created time                |
| updated_at  | TIMESTAMP    | Record updated time                |
| data        | JSON         | Tenant data payload                |

---

📋 Table: `users`

| Column Name       | Type         | Description                        |
| ----------------- | ------------ | ---------------------------------- |
| id                | BIGINT (PK)  | Primary key                        |
| name              | VARCHAR(255) | Full name                          |
| email             | VARCHAR(255) | Unique email address               |
| type              | VARCHAR(50)  | User type (default: staff)         |
| email_verified_at | TIMESTAMP    | Email verification timestamp       |
| password          | VARCHAR(255) | Hashed password                    |
| remember_token    | VARCHAR(100) | Remember me token                  |
| created_at        | TIMESTAMP    | Record created time                |
| updated_at        | TIMESTAMP    | Record updated time                |

---

📋 Table: `user_availabilities`

| Column Name | Type        | Description                                    |
| ----------- | ----------- | ---------------------------------------------- |
| id          | BIGINT (PK) | Primary key                                    |
| user_id     | BIGINT (FK) | Reference to users                             |
| day_of_week | TINYINT     | Day of week (0=Sun, 1=Mon, ... 6=Sat)          |
| created_at  | TIMESTAMP   | Record created time                            |
| updated_at  | TIMESTAMP   | Record updated time                            |

---

📋 Table: `user_metadata`

| Column Name      | Type        | Description                        |
| ---------------- | ----------- | ---------------------------------- |
| id               | BIGINT (PK) | Primary key                        |
| user_id          | BIGINT (FK) | Reference to users                 |
| phone_number     | VARCHAR(50) | Phone number                       |
| department_id    | BIGINT (FK) | Reference to departments           |
| profile_photo_id | BIGINT (FK) | Reference to master_documents      |
| uploaded_status  | VARCHAR(50) | Upload status of profile photo     |
| created_at       | TIMESTAMP   | Record created time                |
| updated_at       | TIMESTAMP   | Record updated time                |

---

📋 Table: `zip_codes`

| Column Name | Type        | Description                        |
| ----------- | ----------- | ---------------------------------- |
| id          | BIGINT (PK) | Primary key                        |
| zipcode     | VARCHAR(5)  | ZIP code                           |
| city        | VARCHAR(50) | City name                          |
| state       | VARCHAR(50) | State name                         |
| state_abbr  | VARCHAR(2)  | State abbreviation                 |
| county_area | VARCHAR(50) | County or area name                |
| code        | VARCHAR(3)  | Additional code                    |
| latitude    | DOUBLE      | Latitude coordinate                |
| longitude   | DOUBLE      | Longitude coordinate               |
| some_field  | TINYINT     | Miscellaneous field                |
| created_at  | TIMESTAMP   | Record created time                |
| updated_at  | TIMESTAMP   | Record updated time                |
