# Campus Admin Connect - Testing Guide

## Complete Setup & Testing Instructions

### Phase 1: Backend Setup

#### 1.1 Database Setup
```bash
# Navigate to Laravel directory
cd laravel

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=campus_admin_connect
DB_USERNAME=root
DB_PASSWORD=

# Create database
mysql -u root -p
CREATE DATABASE campus_admin_connect;
EXIT;

# Run migrations
php artisan migrate

# Seed demo data
php artisan db:seed
```

#### 1.2 Start Laravel Server
```bash
# Start Laravel development server
php artisan serve

# Server will run on http://localhost:8000
```

### Phase 2: Frontend Setup

#### 2.1 Install Dependencies
```bash
# Navigate to React directory
cd react

# Install dependencies (if not already installed)
npm install

# Install axios for API calls
npm install axios
```

#### 2.2 Environment Configuration
```bash
# Verify .env file exists with API URL
VITE_API_URL=http://localhost:8000/api
```

#### 2.3 Start Frontend Server
```bash
# Start React development server
npm run dev

# Frontend will run on http://localhost:5173
```

### Phase 3: Testing Scenarios

#### 3.1 Admin Authentication Testing
1. **Navigate to**: `http://localhost:5173/admin/login`
2. **Test credentials**:
   - Username: `admin`
   - Password: `admin123`
3. **Expected**: Successful login and redirect to admin dashboard

#### 3.2 Student Request Testing
1. **Navigate to**: `http://localhost:5173/demande`
2. **Test student credentials**:
   - Email: `student@universite.ma`
   - Apogee: `12345678`
   - CIN: `AB123456`
3. **Test document requests**:
   - Attestation de scolarité
   - Attestation de réussite
   - Relevé de notes
   - Convention de stage
4. **Expected**: Successful submission with request number

#### 3.3 Reclamation Testing
1. **Select "Passer une réclamation"**
2. **Fill reclamation form**:
   - Request number: Use a valid number from previous submissions
   - Type: Select any reclamation type
   - Description: Enter detailed description
   - Attachment: Upload optional file (PDF/JPG/PNG)
3. **Expected**: Successful reclamation submission

#### 3.4 Admin Dashboard Testing
1. **Login as admin** (see 3.1)
2. **Navigate to dashboard**: `http://localhost:5173/admin`
3. **Verify statistics display**
4. **Expected**: Real-time data from database

#### 3.5 Admin Request Management Testing
1. **Navigate to**: `http://localhost:5173/admin/demandes`
2. **Test features**:
   - View all requests
   - Search by student name/apogee
   - Filter by status and document type
   - View request details
   - Validate requests
   - Refuse requests with reasons
3. **Expected**: All operations work correctly with real data

### Phase 4: Advanced Testing

#### 4.1 API Testing (Optional)
```bash
# Test API endpoints directly
curl -X POST http://localhost:8000/api/admin/login \
  -H "Content-Type: application/json" \
  -d '{"identifiant":"admin","password":"admin123"}'

# Test student request
curl -X POST http://localhost:8000/api/demandes \
  -H "Content-Type: application/json" \
  -d '{"email":"student@universite.ma","apogee":"12345678","cin":"AB123456","type_document":"attestation_scolaire","niveau":"S5","filiere":"Informatique","annee_universitaire":"2024-2025"}'
```

#### 4.2 Database Verification
```sql
-- Check database tables
USE campus_admin_connect;

-- Verify students
SELECT * FROM etudiants;

-- Verify requests
SELECT * FROM demandes;

-- Verify reclamations
SELECT * FROM reclamations;

-- Verify admin users
SELECT * FROM admins;
```

### Phase 5: Troubleshooting

#### Common Issues & Solutions

1. **CORS Errors**
   - Ensure Laravel CORS is configured
   - Check `config/cors.php` settings

2. **Database Connection**
   - Verify MySQL is running
   - Check database credentials in `.env`
   - Ensure database exists

3. **Authentication Issues**
   - Check Sanctum tokens
   - Verify admin credentials in database

4. **File Upload Issues**
   - Ensure `storage/app/public` directory exists
   - Run `php artisan storage:link`
   - Check file permissions

5. **Frontend Build Issues**
   - Clear node_modules and reinstall
   - Check TypeScript errors
   - Verify API URL in .env

### Phase 6: Production Deployment Notes

#### Backend (Laravel)
```bash
# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Set up queue workers if needed
php artisan queue:work
```

#### Frontend (React)
```bash
# Build for production
npm run build

# Serve static files
# Use nginx, Apache, or similar web server
```

### Testing Checklist

- [ ] Database setup and migrations completed
- [ ] Seed data populated
- [ ] Laravel server running on port 8000
- [ ] React app running on port 5173
- [ ] Admin login working
- [ ] Student identification working
- [ ] Document requests submitting successfully
- [ ] Reclamations submitting with file uploads
- [ ] Admin dashboard showing real statistics
- [ ] Admin request management working
- [ ] Search and filtering working
- [ ] Request validation/refusal working
- [ ] File uploads storing correctly
- [ ] No CORS errors in browser console
- [ ] All API endpoints responding correctly

### Demo Credentials

**Admin Login:**
- Username: `admin`
- Password: `admin123`

**Student Accounts:**
- Email: `student@universite.ma`, Apogee: `12345678`, CIN: `AB123456`
- Email: `etudiant@universite.ma`, Apogee: `87654321`, CIN: `CD789012`

### Expected URLs After Setup

- Frontend: `http://localhost:5173`
- Backend API: `http://localhost:8000/api`
- Admin Login: `http://localhost:5173/admin/login`
- Student Request: `http://localhost:5173/demande`
- Admin Dashboard: `http://localhost:5173/admin`

This guide provides complete end-to-end testing from database setup to full application functionality verification.
