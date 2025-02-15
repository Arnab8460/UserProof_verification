# Laravel User Proof Verification System  

This is a Laravel-based User Proof Verification System designed for CRM platforms. The system allows admins to review and approve/reject uploaded user proof documents using AJAX-based interactions.  

## Features  
✅ Proof document upload  
✅ Proof approval/rejection with AJAX  
✅ Role-based access control  
✅ Sorting and filtering of users  
## Prerequisites  
Ensure you have the following installed:  
- PHP 8.x  
- Laravel 11.x  
- MySQL 5.7+  
- Composer  
## Installation Steps  
1. **Clone the Repository**  
   git clone https://github.com/Arnab8460/UserProof_verification.git
   cd UserProof_verification
## Copy .env.example to .env
    cp .env.example .env
## Database Credential
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=arnab
    DB_USERNAME=root
    DB_PASSWORD=

## Method	Route	            Controller      Method	    Description
GET	        /users	        UserProofController      @index	    Lists all users and their proof statuses
POST	/reupload/{id}	    UserProofController     @reupload	    Uploads user proof documents
POST	/approve/{id}/{type}  UserProofController   @approve	Approves a proof (AJAX)
POST	/reject/{id}/{type}	   UserProofController  @reject	    Rejects a proof (AJAX)
POST	/proofs/reupload/{id}	UserProofController @reupload	Allows users to reupload a rejected proof




