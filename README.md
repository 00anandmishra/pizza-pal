# PizzaPal - Content Management System (CMS) Project 🚀

This project is a PHP-based Content Management System (CMS) designed to facilitate the creation, management, and navigation of content by users and administrators. It was initially developed as a college project and has since been deployed for public use.

## Features 📋

- **User Module**:
  - Users can add, edit, or delete pages/recipes.
  - Utilizes a WYSIWYG editor for easy content creation and editing.
  - Allows users to view all available recipes, sort them, and search them.
  - Users cannot access admin pages to maintain security.

- **Admin Module**:
  - Beautifully designed admin pages.
  - Administrators can add, edit, or delete users, recipes, and categories.
  - Authentication mechanism ensures only authorized personnel can access admin functionalities.
  - Usernames and passwords are securely stored in a users table with CRUD admin access.

## Functionality 🛠️

- **Navigation**:
  - Users and admins can navigate through available pages and recipes.
  - Recipes can be accessed by their associated categories for easy browsing.

- **Search**:
  - Users can search for specific pages by keywords, with the option to limit results to a specific category.
  - Search results are paginated for improved user experience.

- **Image Management**:
  - Users can add images to pages via a form upload feature.
  - Automatically resized images upon upload to optimize storage and loading times.
  - Images associated with pages are viewable during navigation for enhanced content presentation.

## Deployment 🚀

To deploy this CMS project, follow these steps:

1. Clone the repository to your local machine.
2. Ensure your server environment meets the PHP version requirements.
3. Import the database schema provided in the project to set up the necessary tables.
4. Configure authentication settings and database connection details as per your environment.
5. Deploy the project to your web server.

## Demo Credentials 🔑

For demonstration purposes, you can use the following credentials to navigate the website:

- **Admin Credentials**:
  - Username: admin@admin.com
  - Password: admin

- **User Credentials**:
  - Username: user@user.com
  - Password: user

Feel free to contribute, report issues, or suggest enhancements to make this CMS project even better! 🌟
