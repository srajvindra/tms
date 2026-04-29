-- Laravel TMS Database Initialization Script
-- This script sets up the initial database configuration

-- Ensure the database exists
CREATE DATABASE IF NOT EXISTS `tms_database` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Grant privileges to the application user
GRANT ALL PRIVILEGES ON `tms_database`.* TO 'tms_user'@'%';

-- Create a dedicated user for read-only operations (optional)
CREATE USER IF NOT EXISTS 'tms_reader'@'%' IDENTIFIED BY 'reader_password';
GRANT SELECT ON `tms_database`.* TO 'tms_reader'@'%';

-- Flush privileges to ensure changes take effect
FLUSH PRIVILEGES;

-- Set some MySQL settings for better performance
SET GLOBAL innodb_buffer_pool_size = 268435456; -- 256MB
SET GLOBAL innodb_log_file_size = 67108864; -- 64MB
SET GLOBAL innodb_flush_log_at_trx_commit = 2;
SET GLOBAL innodb_flush_method = 'O_DIRECT';
SET GLOBAL max_connections = 200;
SET GLOBAL thread_cache_size = 16;
SET GLOBAL query_cache_size = 67108864; -- 64MB
SET GLOBAL query_cache_type = 1;

-- Enable slow query log for performance monitoring
SET GLOBAL slow_query_log = 'ON';
SET GLOBAL long_query_time = 2;
SET GLOBAL log_queries_not_using_indexes = 'ON';

-- Use the application database
USE `tms_database`;