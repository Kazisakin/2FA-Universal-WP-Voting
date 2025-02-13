🎨 Hello Elementor Theme - Custom Enhancements
🔧 Extending Hello Elementor with Secure Voting Access

WordPress
Fluent Forms
PHP
MySQL

🚀 Overview
This project enhances the Hello Elementor WordPress theme by introducing:
✅ One-time secure voting access links 🛡️
✅ Email-based authentication system ✉️
✅ Custom database tables for tracking user requests 📊
✅ Elementor, WooCommerce, and Gutenberg support 🏗️
✅ Debug logging for tracking issues 🔍
✅ Spam prevention using request limits 🚫

🔹 Why This Is Useful?
This enhancement is ideal for secure voting systems, limited-access forms, or exclusive event registrations where authentication and security are critical.

🎯 Features
📌 Custom Database Table (wp_form_access)
A dedicated WordPress database table is created to store user access details securely.

Column	Type	Description
id	INT (Primary Key)	Unique ID for each request
email	VARCHAR(255)	Stores user email
token	VARCHAR(255)	One-time authentication token
password	VARCHAR(255)	Hashed password for security
request_count	INT	Limits spam attempts
last_request_time	DATETIME	Tracks last request timestamp
is_used	TINYINT(1)	Marks if the link was used
📩 Installation & Setup
1️⃣ Prerequisites
Ensure you have:
✅ WordPress with the Hello Elementor theme 🖼️
✅ Elementor & Fluent Forms plugins 📝
✅ PHP & MySQL database access ⚡

WordPress Setup

2️⃣ Add Custom Functions to Your Theme
🛠️ Create the Database Table
This function creates the wp_form_access table if it does not already exist. It is executed when the theme is initialized.

Add this to your functions.php file:

function create_form_access_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'form_access'; 
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        email varchar(255) NOT NULL,
        token varchar(255) NOT NULL,
        password varchar(255) NOT NULL,
        request_count int(11) DEFAULT 0 NOT NULL,
        last_request_time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        is_used tinyint(1) DEFAULT 0 NOT NULL,
        PRIMARY KEY  (id),
        UNIQUE KEY email (email)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action('after_setup_theme', 'create_form_access_table');
🔑 How It Works
This function creates a new database table to store user authentication details.
email is used to track authorized users.
token stores the one-time secure access link.
password is securely hashed before being stored.
request_count prevents spam by limiting requests.
is_used ensures each link can only be used once.
🔒 Security Considerations
✅ Secure Password Storage
All passwords are hashed before being stored, preventing plaintext leaks.

✅ Cryptographically Secure Tokens
Tokens are generated using random_bytes() to ensure strong security and avoid predictable patterns.

✅ Whitelisted Emails Only
Only approved email addresses can request access, ensuring exclusivity.

✅ One-Time Expiry Links
Each link expires after first use, preventing unauthorized reuse.
If a user loses access, they must request a new one-time access link.
✅ Rate-Limiting to Prevent Abuse
Users can only request a voting link twice within 30 minutes.
This prevents spam attacks and ensures fair access.
📝 Debugging & Logging
🔹 Enable WordPress Debugging
To track issues, enable WordPress debugging by adding this to wp-config.php:

php
Copy
Edit
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
📂 Debug logs can be found in /wp-content/debug.log

📜 License
This project is licensed under the MIT License.
