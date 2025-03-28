# 🛩️AuroraSkyways (BACKEND)

This is my final project. It's based on a flight management application where I implement user management using JSON Web Tokens (JWT).


This project is fully API-based, so features like login, booking, and planes views aren't available from the front end, but you can check the flights view. (If you change the middleware, you'll be able to see the views and test them. 😉)

## Overview👁️

I'm going to show you the differences between the way I thought about the web design in figma and the final design.

### Before:
Home page:


![image](https://res.cloudinary.com/dzfqdntdw/image/upload/v1743195101/imagen_2025-03-28_215139655_ucmgtr.png)

Flights View:


![image](https://res.cloudinary.com/dzfqdntdw/image/upload/v1743195295/imagen_2025-03-28_215454449_zzvbno.png)

Bookings View:


![image](https://res.cloudinary.com/dzfqdntdw/image/upload/v1743195489/imagen_2025-03-28_215807430_iuhxau.png)

Planes View: 


![image](https://res.cloudinary.com/dzfqdntdw/image/upload/v1743195514/imagen_2025-03-28_215832172_hu3iay.png)

LogIn Button & Register Button:


![image](https://res.cloudinary.com/dzfqdntdw/image/upload/v1743195539/imagen_2025-03-28_215849899_js6fkx.png)

LogOut Button:


![image](https://res.cloudinary.com/dzfqdntdw/image/upload/v1743195557/imagen_2025-03-28_215900401_ktxbhi.png)

### After:
Home page:


![image](https://res.cloudinary.com/dzfqdntdw/image/upload/v1743194920/imagen_2025-03-28_214837717_pysro9.png)

Flights View:


![image](https://res.cloudinary.com/dzfqdntdw/image/upload/v1743195313/imagen_2025-03-28_215512403_itmndi.png)

Bookings View:


![image](https://res.cloudinary.com/dzfqdntdw/image/upload/v1743195389/imagen_2025-03-28_215627892_l5lsfb.png)

Planes View: 


![image](https://res.cloudinary.com/dzfqdntdw/image/upload/v1743195455/imagen_2025-03-28_215733572_frn0te.png)

LogIn Button & Register Button:


![image](https://res.cloudinary.com/dzfqdntdw/image/upload/v1743195578/imagen_2025-03-28_215936668_mwmhv8.png)

LogOut Button:


![image](https://res.cloudinary.com/dzfqdntdw/image/upload/v1743195560/imagen_2025-03-28_215918849_x6hria.png)

## 🛠️🚀 Tech Stack

### **Languages**:
- HTML
- Blade (Laravel template engine)

### **Frameworks**:
- Laravel
- TailwindCSS

### **Server**:
- XAMPP
- Apache
- Node.js

### **Database**:
- MySQL

### **Tools & Others**:
- Composer
- Postman

## 📊📁 DB Diagram
![image](https://res.cloudinary.com/dzfqdntdw/image/upload/v1743196246/imagen_2025-03-28_221043834_chxex8.png)


## 🔧⚙️ Installation

Follow these steps to install and set up the project:

- Clone the repository
```
https://github.com/Kalixto73a/AuroraSkyways
```

- Install Composer dependencies

```
composer install
```
- Install Node.js dependencies

```
npm install
```
- Run the following command to create the secret JWT key in your .env file
```
php artisan jwt:secret
```
- Duplicate .env.example file and rename to .env
- In this new .env, change the variables you need, but it is very important to uncomment the database connection lines that are these:
 
In DB_CONNECTION will come MySQL, change it to the bd you use

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=santaclaus
DB_USERNAME=root
DB_PASSWORD=
```
 - Generate an App Key with this command 
```
php artisan key:generate 
```

- Execute migrations  
```
php artisan migrate
```
## ▶️💻 Run Locally
- How to run the Laravel server  
```
php artisan serve
```

- If you want to run all this in development environment run the following command  
```
npm run dev
```

- For production you should run the following command 
```
npm run build
```
## 🏃‍♂️🧪 Running Tests

To run test you should uncomment the following lines on the phpunit.xml file.

![image](https://res.cloudinary.com/dierpqujk/image/upload/v1733829455/imagen_2024-12-10_121742908_b3mfqm.png)


With the following command we run the tests and we will also generate a coverage report

```bash
  php artisan test --coverage-html=coverage-report
```

If everything is configured correctly, tests should pass, and the coverage report will show `100%` coverage.

#### Test Summary:
![image](https://res.cloudinary.com/dzfqdntdw/image/upload/v1743196957/imagen_2025-03-28_222235684_gkhhmy.png)


#### Coverage Folder:
![image](https://res.cloudinary.com/dzfqdntdw/image/upload/v1743196869/imagen_2025-03-28_222107240_mznhls.png)

## 📡🌐 Christmas Toy Factory API

This API allows you to manage Christmas gift entries and provides CRUD (Create, Read, Update, Delete) operations for them.

### JTW Token

#### 1 Create User

```http
POST /api/auth/register
```
#### Response: 
- **Status Code:** 201, 400

#### Body: 

| Parameter | Type     | Description                    |
| :-------- | :------- | :-------------------------     |
| `name`    | `string` | **Required  & Unique**. Name of the user   |
| `email` | `string` |  **Required & Unique**. Email of the user |
| `role` | `enum` |  **Required**. Role of the user (Admin or User) |
| `password` | `string` |  **Required**. Password of the user (min: 8) |
| `password_confirmation` | `string` |  **Required**. Same password as the one above |

##### 2 Login User

```http
POST /api/auth/login
```

#### Response: 
- **Status Code:** 201, 401

#### Body: 

| Parameter | Type     | Description                    |
| :-------- | :------- | :-------------------------     |
| `email` | `string` |  **Required & Unique**. Email of the user |
| `password` | `string` |  **Required**. Password of the user (min: 8) |

#### 3 User Info 

```http
POST /api/auth/me
```
#### Authorization:

- **Authorization, Bearer Token:** Your token

#### Headers: 

- **Content Type:** application/json

#### Response:

- **Status Code:** 200

#### 4 User Logout

```http
POST /api/auth/logout
```

#### Authorization:

- **Authorization, Bearer Token:** Your token

#### Headers: 

- **Content Type:** application/json
#### Response:

- **Status Code:** 200

#### 5 Token Refresh

```http
POST /api/auth/refresh
```
#### Authorization:

- **Authorization, Bearer Token:** Your token

#### Headers: 

- **Content Type:** application/json

#### Response:

- **Status Code:** 200

### Planes

#### 1 Get all planes (Admin)

```http
GET /api/planes
```
#### Authorization:

- **Authorization, Bearer Token:** Your Admin token

#### Headers: 

- **Content Type:** application/json

#### Response:

- **Status Code:** 200

#### 2 Get a plane by ID (Admin)

```http
GET /api/plane/id
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `id`      | `int` | **Required**. Plane ID     |

#### Authorization:

- **Authorization, Bearer Token:** Your Admin token

#### Headers: 

- **Content Type:** application/json

#### Response:

- **Status Code:** 200,404

#### 3 Create a new plane (Admin)

```http
POST /api/planes
```

#### Authorization:

- **Authorization, Bearer Token:** Your Admin token 

#### Headers: 

- **Content Type:** application/json

#### Response:

- **Status Code:** 201


#### Body: 

| Parameter | Type     | Description                    |
| :-------- | :------- | :-------------------------     |
| `name`    | `string` | **Required**. Name of the plane   |
| `max_seats` | `int` |  **Required**. Total seats of the plain |

#### 4 Update an existing plane by ID (Admin)

```http
PUT /api/plane/id
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `id`      | `int` | **Required**. Plane ID     |

#### Authorization:

- **Authorization, Bearer Token:** Your Admin token

#### Headers: 

- **Content Type:** application/json

#### Response:

- **Status Code:** 200,404

#### Body: 

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `name`    | `string` | **Required**. Name of the plane    |
| `max_seats` | `int` | **Required**. Max seats of the plane (min 1) |

#### 5 Delete a plane by ID (Admin)

```http
DELETE /api/plane/id
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `id`      | `int` | **Required**. Plane ID     |

#### Authorization:

- **Authorization, Bearer Token:** Your Admin token

#### Headers: 

- **Content Type:** application/json

#### Response:

- **Status Code:** 204, 404

### Flight:

#### 1 Get all flights (Anyone)

```http
GET /api/flights
```

#### Headers: 

- **Content Type:** application/json

#### Response:

- **Status Code:** 200

#### 2 Get a flight by ID (Anyone)

```http
GET /api/flight/id
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `id`      | `int` | **Required**. Flight ID     |

#### Headers: 

- **Content Type:** application/json

#### Response:

- **Status Code:** 200,404

#### 3 Create a new flight (Admin)

```http
POST /api/flights
```
#### Authorization:

- **Authorization, Bearer Token:** Your Admin token

#### Headers: 

- **Content Type:** application/json

#### Response:

- **Status Code:** 201, 422

#### Body: 

| Parameter | Type     | Description                    |
| :-------- | :------- | :-------------------------     |
| `departure_date`    | `date` | **Required**. Departure date of the flight   |
| `arrival_date` | `date` |  **Required**. Arrival date of the flight |
| `origin` | `string` |  **Required**. Origin departure place |
| `destination` | `string` |  **Required**. Destination arrival plave |
| `plane_id` | `int` |  **Required**. Id of the plain that will be used for the flight |
| `available` | `boolean` |  **Required**. If the flight is available or not (must be on 1)|

#### 4 Update a flight (Admin)

```http
PUT /api/flight/id
```

#### Authorization:

- **Authorization, Bearer Token:** Your Admin token

#### Headers: 

- **Content Type:** application/json

#### Response:

- **Status Code:** 200, 404

#### Body: 

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `id`      | `int` | **Required**. Flight ID     |

#### 5 Delete a flight by ID (Admin)

```http
DELETE /api/flight/id
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `id`      | `int` | **Required**. Flight ID     |

#### Authorization:

- **Authorization, Bearer Token:** Your Admin token

#### Headers: 

- **Content Type:** application/json

#### Response:

- **Status Code:** 204, 404

### Bookings:

#### 1 Get all bookings from a User (User)

```http
GET /api/bookings
```

#### Authorization:

- **Authorization, Bearer Token:** Your User token

#### Headers: 

- **Content Type:** application/json

#### Response:

- **Status Code:** 200, 404

#### 2 Get a booking of a User by ID (User)

```http
GET /api/booking/id
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `id`      | `int` | **Required**. Booking ID     |

#### Authorization:

- **Authorization, Bearer Token:** Your User token

#### Headers: 

- **Content Type:** application/json

#### Response:

- **Status Code:** 200, 403

#### 3 Create a new booking by User (User)

```http
POST /api/bookings
```

#### Authorization:

- **Authorization, Bearer Token:** Your User token

#### Headers: 

- **Content Type:** application/json

#### Response:

- **Status Code:** 201, 400

#### Body: 

| Parameter | Type     | Description                    |
| :-------- | :------- | :-------------------------     |
| `flight_id`    | `int` | **Required**. The ID of the flight     |
| `seat_number` | `string` |  **Required**. The seat number on the plain of the flight     |
| `status` | `enum` |  **Required**. Status of the booking (must be Activo) |

#### 4 Update a booking (User)

```http
PUT /api/booking/id
```

#### Authorization:

- **Authorization, Bearer Token:** Your User token

#### Headers: 

- **Content Type:** application/json

#### Response:

- **Status Code:** 200, 403

#### Body: 

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `id`      | `int` | **Required**. Booking ID     |

#### 5 Delete a flight by ID (User)

```http
DELETE /api/booking/id
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `id`      | `int` | **Required**. Booking ID     |

#### Authorization:

- **Authorization, Bearer Token:** Your User token

#### Headers: 

- **Content Type:** application/json

#### Response:

- **Status Code:** 204, 403

## ✍️🙍 Authors

- **Álvaro Cervera:**  [![GitHub](https://img.shields.io/badge/GitHub-Perfil-black?style=flat-square&logo=github)](https://github.com/Kalixto73a)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-Perfil-blue?style=flat-square&logo=linkedin)](https://www.linkedin.com/in/álvaro-cervera-vigara-745576337/)
[![Correo](https://img.shields.io/badge/Email-Contacto-red?style=flat-square&logo=gmail)](mailto:Kalixto75@gmail.com)