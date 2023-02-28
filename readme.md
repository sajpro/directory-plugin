Create a simple and well structured directory plugin where a logged in user will submit listings through a frontend submission form. And submitted listings will be shown to the visitor using 4x3 grid on the frontend. We just need to store the following data (you can use more but not less):

    1. Title
    2. Content
    3. Owner/Author
    4. Status
    5. Submission date
    6. Preview image

Technical requirements:

    1. Use REST API custom endpoint for listings submission and listings query.
    2. Use custom table to store data.
    3. Use Ajax in submission form and pagination.
    4. Display submitted listings on the backend using WP_List_Table.
    5. Use block (Gutenberg block) to display submission form and listings gird.


### DB Table: 
Stored the same input in a custom table mentioned in the assignmet sheet. Nothing more nothing less. This table is getting creating during the plugin activation.

### Screen Option:
WP List Table: This table is showing stored data similar to WP default post pages. (1) It has pagination  (2) bulk delete option (3) dropdown filter (4) link filter (all | active | inactive) with item qty. Search bar also dynamic, its searching listings based on title & content. Search results for: "" also displayed when the form submited with values.

### Screen Option:
 Here added post per page options that control the list table items (how many items will be displayed), and list table column enable/disable checkboxes. So that users can show/hide columns as per their need.

### Gutenberg Block:
 Dynamic block is used since current logged in user id intended to submit with the form and used serverside render at the backend so that dynamic data gets visible at the backend with immediate attributes changes. In the backend, you will get few control settings. Instead of doing the same control i tried to add different type of controls so that you can estimate my capability. Though I am able to make the control panle looks similar to Elementor controll panel.

### Frontend: 
Please consider the desing part. I just focusd on the backend and functional stuff. If you activate this theme with "hello elementor" theme it wont make you unhappy with the design. you will get a nice look of the design. Paginaton & Form submission feature implemented with Ajax. Where only logged in users can submit the form. If not logged-in user want to submit the form they should get a popup message with the login link. Uploaded Image using wp_ajax_ hook (because using wp/v2/media need another plugin for authentication purpose, so i avoide it.) but submitted the content using API. 

### FIRST TIME i did: 
This is the first time I made the ajax pagination. I am not fully satisfied with it. Proper thinking will help to more optimize this script. But I am out of time now :)


