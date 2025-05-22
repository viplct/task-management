# 📝 Task Management Platform

---

## 🚀 Setup Instructions

### Run with Docker (Recommended)

1. **Clone the repo**:

   ```bash
   git clone https://github.com/viplct/task-management.git
   cd task-management
   ```

2. **Copy environment files**:

   ```bash
   cd backend
   cp .env.example .env
   ```

3. **Start containers**:

   ```bash
   docker-compose up --build -d
   ```

4. **Install PHP dependencies** (inside the container):

   ```bash
   docker-compose exec app composer install
   docker-compose exec php artisan key:generate (if missing APP_KEY)
   ```
5. **Frontend** should be available at: `http://localhost:5173`

6. **API** should be available at: `http://localhost:8000`

---

## 🧠 Design Decisions

### ✅ Architecture

* **Modular Vue Components**: Pages (`ProjectIndex.vue`, `TaskIndex.vue`) and shared layouts (`DashboardLayout.vue`, `AuthLayout.vue`) are separated for clarity and reusability.
* **Laravel API-first** backend with resourceful routes and model observers for business logic like task ordering. Sanctum for authentication, FormRequest and Policy for validation. 

### ✅ Task Ordering (Drag & Drop)

* Each task has a numeric `order` field.
* Tasks can be reordered using `vue-draggable-next`.
* New tasks are automatically assigned the next highest `order` using a Laravel **Model Observer**.

### ✅ Filtering

* Tasks can be filtered by `status` and `priority`.

### ✅ Security

* Backend routes are protected with `auth:sanctum`.
* Input validation is handled via Laravel FormRequest and Policy.
* Use Resources for Shape API responses, Encapsulate logic for presentation, Prevent over-exposing sensitive data.

### ✅ Testing

* Frontend unit/component tests are written using **Vitest + Vue Test Utils**.
```bash
npx vitest
```
* Backend **Feature Tests** and **Unit Tests**.
```bash
docker-compose exec app php artisan test
```

---

## 🐞 Known Limitations / TODOs

* ❌ No real-time updates (e.g., Laravel Echo + Pusher).
* ❌ No soft deletes or activity log/audit trail yet.
* ❌ No Activity Log (Audit Trail).
* ❌ Drag & drop is disabled when filters are applied (to prevent order corruption).
* 📱 UI responsiveness is minimal; could be improved for mobile UX.
* Validate payload in the FE, confirm delete modal, validate for form fields.
* Need more comment in the code.
* Add repository, more services.
* Upgrade to Typescript

---

