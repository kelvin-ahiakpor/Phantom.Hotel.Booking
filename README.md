
# Phantom.Hotel.Booking

Phantom.Hotel.Booking is a data-driven hotel management system designed to minimize inaccuracies in manual booking processes, reduce front desk congestion, and streamline hotel operations. This project aims to provide a seamless and efficient solution for hotel booking and management.

## Project Links

- [CMS Prototype](https://bryanblue11.wixsite.com/phantom)
- [Sprint 1](https://drive.google.com/file/d/1HEMZDA5RUlegXFFKDAxxi-PAeoba3GGY/view?usp=share_link)
- [Sprint 2](https://drive.google.com/file/d/11NBVUe-_soCnR4NUqYEtQCUjZsDXSlLU/view?usp=share_link)
- [Sprint 3](https://drive.google.com/file/d/1J6d2fH5wgOeJdzv_f7lmeIb43IAXQ7TE/view?usp=share_link)
- [Current Project Site](http://169.239.251.102:3341/~kelvin.ahiakpor/PHANTOM_HOTEL_BOOKING/)

---

## Features

- **Hotel Booking**: Users can browse available hotels and book rooms directly.
- **Hotel Management**: Admins can create, edit, and manage hotels and rooms.
- **Booking Management**: Admins can view and handle customer bookings.
- **User Authentication**: Login and sign-up functionality for both users and administrators.
- **Dynamic Content**: Displays hotel and booking data dynamically.

---

## Technologies Used

- **Frontend**: HTML, CSS, Bootstrap
- **Backend**: PHP
- **Database**: MySQL
- **Server Requirements**: Apache Server (e.g., XAMPP)

---

## Flow Diagram

Here is a flow diagram for the website

![Phantom Flow Diagram](https://github.com/kelvin-ahiakpor/kelvin-ahiakpor.github.io/blob/main/images/phantomflow.png)

---

## File Structure

Here is the hierarchical structure of the project:

```plaintext
Project File Structure:
-----------------------
.
├── assets
│   ├── css
│   ├── images
│   └── js
├── actions
├── db
├── functions
├── middleware
├── view
│   ├── admin
│   ├── owner
│   ├── user
├── .env
├── .gitignore
├── composer.json
├── composer.lock
├── index.php
├── LICENSE
├── README.md
```

### Description of Key Files

| File Name          | Description                                      |
|--------------------|--------------------------------------------------|
| `index.php`        | Landing page of the website.                    |
| `browse_hotels.php`| Displays a list of hotels for users to browse.  |
| `hotel_feed.php`   | Fetches hotel data dynamically.                 |
| `login.php`        | Login page for users and admins.                |
| `signup.php`       | Sign-up page for new users.                     |
| `dashboard.php`    | Admin dashboard for managing hotels and rooms.  |
| `create_hotel.php` | Allows admins to add new hotels.                |
| `edit_hotel.php`   | Enables admins to edit hotel details.           |
| `manage_hotel.php` | Page to manage hotel details.                   |
| `manage_room.php`  | Page to manage room details within a hotel.     |
| `booking_form.php` | Booking form for customers.                     |
| `bookings.php`     | Handles booking data.                           |
| `new_booking.php`  | Allows users to make new bookings.              |
| `view_bookings.php`| Admin page for viewing bookings.                |
| `preview.php`      | Previews booking and hotel information.         |

---

## Instructions for Setup

To run this project locally, follow these steps:

### Requirements

- Apache Server (e.g., XAMPP or WAMP)
- PHP (minimum version 7.4)
- MySQL Database

### Steps

1. **Clone the Repository**: Clone this repository to your local machine.

   ```bash
   git clone <repository_link>
   ```

2. **Set Up Apache and MySQL**:
   - Ensure Apache and MySQL are running on your local server.
   - Create a new database for the project using your preferred database management tool (e.g., phpMyAdmin).
3. **Import Database**:
   - Import the provided SQL file into the database. Use the following command in your terminal:

     ```bash
     mysql -u [username] -p [database_name] < phantom_hotel.sql
     ```

4. **Update Database Configurations**:
   - Open the `config.php` file and update the database credentials:

     ```php
     $dbHost = 'localhost';
     $dbUser = 'root';
     $dbPass = 'your_password';
     $dbName = 'phantom_hotel';
     ```

5. **Place Files**:
   - Place the project files in the appropriate directory of your Apache server (e.g., `htdocs` for XAMPP).
6. **Access the Website**:
   - Open your web browser and navigate to `http://localhost/[project_folder]/index.php`.

---

## Sprint Deliverables

As part of the sprint process, the project is kept in a working state to allow demonstrations to the teaching team and class. The following criteria ensure readiness:

1. **GitHub Repository**: Ensure all project files are checked into GitHub.
2. **Setup Instructions**: Include sufficient details for faculty to build and run the website.
3. **Dependencies**: The project must run on a server with:
   - Apache
   - MySQL
   - PHP

---

## Contributors

- **Vera Anthonio**
- **Bryan Hans-Ampiah**
- **Denis Aggyratus**
- **Kelvin Ahiakpor**

---

Feel free to suggest any changes or reach out for assistance with the setup.
