# Backend Implementation for Laundry Website

## Database Setup
- [x] Create MySQL database schema (laundry_db.sql) with tables: users, transactions, services, machines, notifications, admin_settings
- [x] Create db_config.php for database connection

## API Endpoints (PHP)
- [x] api/login.php - Handle user login
- [x] api/register.php - Handle user registration
- [x] api/logout.php - Handle logout and session destroy
- [x] api/get_user.php - Get current user data
- [x] api/update_user.php - Update user profile
- [x] api/get_transactions.php - Get user transactions
- [x] api/create_transaction.php - Create new transaction
- [x] api/get_services.php - Get available services
- [x] api/get_machines.php - Get machine statuses
- [x] api/update_machine.php - Update machine status (admin)
- [x] api/get_notifications.php - Get user notifications
- [x] api/admin/get_all_users.php - Get all users (admin)
- [x] api/admin/get_all_transactions.php - Get all transactions (admin)

## Frontend Updates (Replace localStorage with API calls)
- [x] Update auth.html JS for login/register using fetch()
- [ ] Update home.html JS for user data and transactions
- [ ] Update services.html JS for service selection
- [ ] Update transactions.html JS for transaction list
- [ ] Update admin.html JS for admin panel
- [ ] Update machines.html JS for machine status
- [ ] Update settings.html JS for profile updates
- [ ] Update other relevant files (receipt.html, notifications.html, etc.)

## Session Management
- [x] Implement PHP sessions for authentication across all endpoints
- [x] Add session checks in protected endpoints

## Testing and Verification
- [x] Test database setup and connections
- [x] Test API endpoints individually
- [x] Test frontend-backend integration
- [x] Verify authentication flow
- [x] Verify admin features
