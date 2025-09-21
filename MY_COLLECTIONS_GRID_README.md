# My Collections Grid Component

## Overview
The My Collections Grid component displays a user's comic collection with advanced search, filtering, and statistics features. It groups comics by collection/subcollection and provides a comprehensive view of the user's library.

## Features Implemented

### ✅ Search and Filtering
- **Keyword search**: Search by book titles and collection names
- **Publisher filter**: Filter by maison d'édition
- **Collection filter**: Filter by specific collections
- **Artist filter**: Filter by artists from the creative team
- **Date filter**: Filter by publication date
- **View filters**: Show all, unread only, or loaned books only

### ✅ Statistics Display
- Total comics owned
- Number of unread comics
- Number of loaned comics (placeholder for future implementation)

### ✅ Collections Grouping
- Groups comics by sous-collection (when available)
- Falls back to collection name if sous-collection is empty
- Sorts collections alphabetically

### ✅ Collection Information
- Collection/subcollection name with link to collection page
- Total number of albums in the series
- Number of read albums
- Number of loaned albums (when applicable)
- Collection status icons (ongoing, finished, abandoned)

### ✅ Book Display
- Book cover image
- Book title with link to book page
- Volume number (tome)
- Status icons for owned, read, and loaned status

### ✅ Responsive Design
- Mobile-friendly layout
- Adaptive grid system
- Touch-friendly interface

## Files Created/Modified

### New Files
1. `components/my-collections-grid.php` - Main component template
2. `assets/css/ContentElements/ce-my-collections-grid.css` - Component styles
3. `assets/js/block-my-collections-grid.js` - JavaScript functionality
4. `acf-json/group_my_collections_grid.json` - ACF field configuration

### Modified Files
1. `inc/user-books-management.php` - Added enqueue functions for new assets

## ACF Configuration

The component includes the following configurable options:
- **Title**: Custom title for the section
- **Description**: Optional description text
- **Show Statistics**: Toggle statistics display
- **Show Search**: Toggle search and filter section
- **Books per Row**: Configure grid layout (2-6 books per row)
- **Show Collection Logos**: Toggle collection logo display
- **Show Status Icons**: Toggle book status icons

## Database Structure

The component uses the existing `wp_user_books` table with the following list types:
- `wishlist`: Books the user wants to read
- `read`: Books the user has read
- `collection_wishlist`: Collections the user wants to follow
- `missing_albums`: Missing albums in collections

## Required ACF Fields

### For Livres (Books)
- `photo_devant` - Front cover image
- `titre_livre` - Book title
- `collection` - Related collection (post object)
- `sous_collection` - Sub-collection name (text field)
- `maison_d'edition` - Publisher (post object)
- `n_sortie` - Volume number
- `date_sortie_livre` - Publication date
- `equipe_creative` - Creative team (repeater with artist post objects)

### For Collections
- `nom_collection` - Collection name
- `logo_collection` - Collection logo
- `etat_collection` - Collection status (En cours, Terminée, Abandonné)

## Missing Features (TODO)

### 1. Sous-collection Field
**Status**: ✅ Implemented
**Location**: Added to `acf-json/group_68adfe2985287.json`
**Field Key**: `field_68ae030028403`

### 2. Loaned Books Functionality
**Status**: Placeholder implemented
**Required**: 
- Add `loaned` list type to user books management
- Create loan tracking system
- Add loan management interface

### 3. Enhanced Search
**Status**: Basic implementation
**Potential Improvements**:
- AJAX-powered search
- Search suggestions
- Advanced filters (price, condition, etc.)

## Usage

### As a Gutenberg Block
1. Add the "My Collections Grid" block to any page/post
2. Configure the block settings in the sidebar
3. The component will automatically display the logged-in user's collections

### As a Shortcode
```php
[my_collections_grid title="My Library" show_stats="true" books_per_row="4"]
```

### Direct PHP Usage
```php
// Include the component
get_template_part('components/my-collections-grid');
```

## Styling Customization

The component uses CSS custom properties and can be easily customized:

```css
.my-collections-grid {
    --primary-color: #3b82f6;
    --secondary-color: #6b7280;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --error-color: #dc2626;
}
```

## Browser Support

- Modern browsers (Chrome, Firefox, Safari, Edge)
- IE11+ (with polyfills for CSS Grid)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Performance Considerations

- Images are lazy-loaded
- Search is debounced (300ms)
- Minimal DOM manipulation
- Efficient filtering algorithms

## Security

- All user inputs are sanitized
- Nonce verification for AJAX requests
- User permission checks
- SQL injection prevention through prepared statements
