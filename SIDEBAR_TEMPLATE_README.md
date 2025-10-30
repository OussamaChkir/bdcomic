# Page with Sidebar Template

This template provides a flexible page layout with a configurable sidebar menu that can be managed from the WordPress backoffice.

## Features

- **Configurable Sidebar Menu**: Add, remove, and customize menu items through ACF fields
- **Predefined Menu Types**: Quick setup for common menu items (Ma Bibliothèque, Ma Collection, Mes Souhaits, Mes Albums Manquants)
- **Custom Menu Items**: Add custom links or page links with custom icons
- **User Statistics**: Display user book statistics in the sidebar (for logged-in users)
- **Responsive Design**: Mobile-friendly layout that adapts to different screen sizes
- **Dark Mode Support**: Automatic dark mode styling

## How to Use

### 1. Create a New Page
1. Go to **Pages > Add New** in WordPress admin
2. Set the page title
3. In the **Page Attributes** box, select **"Page with Sidebar"** as the template
4. Publish the page

### 2. Configure the Sidebar Menu
After creating the page, you'll see a new **"Sidebar Menu Configuration"** section with the following options:

#### Menu Items
- **Add Menu Item**: Click to add new menu items
- **Menu Type**: Choose from:
  - **Ma Bibliothèque**: Links to the user's library page
  - **Ma Collection**: Links to the user's collection page  
  - **Mes Souhaits**: Links to the user's wishlist page
  - **Mes Albums Manquants**: Links to missing albums page
  - **Page Link**: Link to any WordPress page
  - **Custom Link**: Link to any URL

#### For Each Menu Item:
- **Custom Title**: Override the default title (optional)
- **Page**: Select a page (for Page Link type)
- **Custom URL**: Enter a custom URL (for Custom Link type)
- **Custom Icon**: Enter a Dashicons class name (e.g., `dashicons-admin-home`)

### 3. Page Subtitle
- **Page Subtitle**: Add an optional subtitle that appears below the page title

## Default Menu Items

If no custom menu items are configured, the template will display these default items:
- Ma Bibliothèque
- Ma Collection  
- Mes Souhaits
- Mes Albums Manquants

## User Statistics

For logged-in users, the sidebar automatically displays:
- Number of books in wishlist
- Number of books marked as read
- Number of collections in wishlist
- Number of missing albums

## Styling

The template includes comprehensive CSS styling with:
- Clean, modern design
- Hover effects and active states
- Responsive grid layout
- Dark mode support
- Bootstrap integration

## File Structure

- `page-with-sidebar.php` - Main template file
- `acf-json/group_sidebar_menu.json` - ACF field configuration
- `assets/css/Globals/page-with-sidebar.css` - Template styles
- Updated `functions.php` - Enqueues the CSS file

## Customization

### Adding New Menu Types
To add new predefined menu types, edit the `page-with-sidebar.php` file and add new cases to the switch statement in the menu item loop.

### Styling Modifications
Edit `assets/css/Globals/page-with-sidebar.css` to customize the appearance.

### ACF Field Modifications
Edit `acf-json/group_sidebar_menu.json` to add or modify fields in the backoffice interface.

## Requirements

- WordPress with ACF (Advanced Custom Fields) plugin
- Theme with Bootstrap support
- User books management system (for statistics)

