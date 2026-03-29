# Online Bike Rental System (Royal Enfield Edition) 🏍️

A specialized web-based management system designed to streamline the rental process for Royal Enfield motorcycles. This project replaces manual record-keeping with an automated, logic-driven booking engine.

Link : https://rerent.page.gd

## 🚀 Key Features
* **Fleet Management:** Real-time tracking of bike availability and rental status.
* **Booking Engine:** Users can select dates, view specific Royal Enfield models, and dynamically calculate costs.
* **Admin Dashboard:** A centralized control panel for adding new bikes and accessing customer history.

## 🧠 System Logic & Workflow
The system follows a classic **Model-View-Controller (MVC)** pattern using PHP:

1.  **User Request:** The user selects a bike and dates on the UI.
2.  **Logic Check:** PHP scripts validate the date range and check the `tbl_bookings` database for conflicts.
3.  **Calculation:** The backend calculates the total cost: `(Daily_Rate * Rental_Days)`.
4.  **Processing:** Upon confirmation, the system updates the bike status and generates a booking ID.

## 🛠️ Tech Stack
* **Frontend:** HTML5, CSS3, JavaScript (Bootstrap for responsive UI)
* **Backend:** PHP (Server-side logic)
* **Database:** MySQL (Relational data management)
* **Server:** XAMPP / Apache

## 💻 How to Run the Project

1.  Make sure **XAMPP** is installed on your system.
2.  Copy the project folder to the XAMPP `htdocs` directory.
    * *Example path:* `C:\xampp\htdocs\`
3.  **Database Setup:**
    * Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
    * Create a new database named **`re_rent_system`**.
    * Import the SQL file provided in the project folder into this database.
4.  Start **Apache** and **MySQL** from the XAMPP Control Panel.
5.  Open your browser and type the project URL:
    * `http://localhost/Your-Project-Folder-Name/`

*(Note: If you change the folder name, update the URL accordingly.)*

## 📸 UI Preview
<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/742dbe17-4e84-42a1-977c-9508cad1e77a" />


