<p align="center"><a href="https://laravel.com" target="_blank"><img src="http://shift-erp.maqware.com/static/media/logo.1bd0d31518f12fbdde0a.png" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
</p>

 ## About The Project  ( SHIFT-ERP )

Shift-ERP is the project where super admin can add multiple ERP companies then admins will receive an email along with the link of system dashboard and crendentials access.By using these credentials company owners can login to their dashboards and system requirements.Admin can manage facilities , Users with Facility-admin, Company Admin and Facility-users. Shift-erp system is based on api’s where front-end can use these api’s to run the system.

## Modules
Shift-ERP is the project where super admin can have access have following Modules :

1.	**Manage Subscriptions**

2.	**Users**
    - Company Administrator
    - Facility Admin
    - Facility Users

3.	**Customers**

4.	**Inventory Modules**
    - Products
    - Kits
    - Locations
    - Reconcile

5.	**Supply Chain**
    - Carriers
    - Vendors
    - ShipTo
    - Needs Reports
    - Invoices
 
6.	**Smart Schedule** 
    - Shipping Order
    - Production Order
    - Blend Order
    - Receiving Order
    - Order management in Smart Schedule Calendar

7.	**Accounting Module** 

    - Pricing
    - Production Extras
    - Revenue
    - Expenses
    - Purchase Orders Management

8.	**Reporting**
9.	**Administration** 

## Built With
Shift-ERP project is developed in Laravel Framework which contains Build in MVC model for creating Api’s . It also contains React Js Framework for front-end development . ERP Api’s with a multi-tenancy system are developed in this project.

## Prerequisites
- **[Node.js](https://nodejs.org/)**
- **[Composer](https://github.com/composer/composer)**
- **Laravel-8**
- **NPM**
- **Xampp-with-PHP-7.4**


## Base dependencies

- **[Google-auth](https://g.co/kgs/2wQpqE)** used for google authentication during Login.
- **[Twilio-sms](https://www.twilio.com/docs/sms)** used for sending SMS to login users.
- **[Tenancy for laravel](https://tenancyforlaravel.com/)** used to create multiple databases and multiple systems.
- **[Laravel-uuid](https://github.com/webpatser/laravel-uuid)** used to securely transfer data.
- **[Guzzle](https://github.com/guzzle/guzzle)** used for interacting with API’s.
- **[Sanctum](https://github.com/laravel/sanctum)** used for secure login with Token.
- **[Google2fa](http://pragmarx/google2fa)** used for two factor authentication .
- **[PHP Unit](https://laravel.com/docs/9.x/http-tests)** used for testing.

### Usage

# Folder structure

    This template follows a very simple project structure:
- **APP**: This folder is the main content of all the code inside your application .
- **Http** This folder contains the following important folders.
    -	controllers: used to control the functionality in the project.
    -	Resources: used for creating resources to send specific data.
    -	Middleware : used for permission and authentication. 
    -	Requests : used for managing validations in laravel.

-	**Models**: This Folder contains Blueprints of Eloquent models (DB Structure)
-	**Helpers**: Folder to store helping functions in projects.
-	**Mail**: Folder to save email functionality in shift-erp .
-	**Jobs**: Folder to store Jobs in project.
-	**Traits**: Folder to store traits for use in models and controllers.
-	**Services**: Folder to store extra functionality of controller Functions.
-	**Notifications**: To send live notification of specific events..
-	**policies**: Folder to Store make Policies and permissions
-   **Config**:	Folder to store all the configurations of Project including Dependencies configurations.
-	**Database**: Folder contains factories and seeders to create dummy data in the  database.
-   **Migration** : folder in the database contains all the database blueprints. 
-	**Public**:	Folder to store images , assets , css and js files 
-	**Resources**: Folder to store views ( page designs ) in view folder.
-	**Routes**:	Folder to store Routes to hit specific URL or create Api in api folder.
-	**Tests**:	Folder to create test cases.
## Setup environments
	This section contains proper setup of shift-erp from github. These are the steps to run a Project in Local server.
1.	Clone Project from github link.
2.	Run command composer Install
3.	Run php artisan key:generate
4.	Run php artisan migrate
5.	Run php artisan db:seed to run seeders

After Following the steps , run command **“ php artisan serve “** and also run xampp server to integrate with the database for migration and data transfer.
 	
# Using scripts from console

The template already has scripts to execute the project calling a specific environment defined into the package.json file. Keep in mind that if you are going to create new envs you have to define the script to build the project properly.
To define which env you want to use, just keep the structure artisan [platform]: [environment]

DEV: **php artisan migrate:fresh –seed**
Also, you can also run queues restart  php artisan queue:start
Modify the environment variables files in root folder (.env.example)

## Generate production version
testerp.co is the Development Version of Shift-erp Api’s . 

## How to use it

The idea of this section is to explain how the template composition is the best and easiest to use when you try to use well-formed architectures. Each time a provision account is created , It will create a new database blueprint for the provision company.
The template follows a simple and convenient exporting pattern. The folder App , Database and Resources contains the main folders for MVC framework.
With that in mind, we are going to look at each folder to explain how to use it.

## Routes

Components are the basic blocks of a Laravel project where we can find the route name and URL to hit from the postman or any other side. The targeted folders are Tenant and API . Where all the folders and component wise Api’s are developed.

## Folder Management :

-   **App**
	 Here you can store all the functionality based work. We can manage controllers , models , resources and requests , to perform functions and blue print of databases .

-	**Models** 
	This folder contains the factories and seeder to save dummy records in specified tables . Also contain migration files to create a database through eloquent.
-	**Resources** 
	Here you can create views in the specified folder according to tenant modules. As each module has its own folder , we can create front-end pages . We are not using views because we are using api’s.  
-	**Views** 
	Here you can define all the styles that you use on different screens. To make easier the interaction of the application with device options for example you can create here assets as light and dark color palette

## Usage
This is the platform that helps companies to automate their systems at enterprise level to manage customers , users , products and departments and many more.

## Screens: 
Shift-erp api’s contains backend development for all the clients. api’s are developed to trigger from front-end and managed by admin of companies who purchased Shift-erp api’s. All front-end code and Screens are in React Js.
