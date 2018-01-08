# Underdog_Data

### Let's revolutionize local politics. Or at least build a cool site.

TODO:

- Bootstrap everything 
    - Skipped:
        > Make list since I still have to add search by survey responses. 
        > Import list since I might change it to use AJAX
    - Done: 
        > Index 
        > Login
        > New Campaign
        > New User 
        > Forgot Password
        > Reset Password
        > Choose Campaign
        > Quick Search
        > Create Questions
        > Add and remove users
        > Manage users
        > Settings

- Search based on survey responses
    - When we move to AWS, will be easy to include past exported lists into possible search criteria

- Help section 

- Meta tags

- Delete test accounts (or change passwords)

- Fix buttons on index when not in full screen

- Nav text is bigger in the login page than the home page

- In list_results.php, POST input is obtained without filtering input. There should be filter input arrays, or at least some array validation.

- Add sample voter file and deploy 

- Add more search features 
    - Voter history
    
- Filter out multiple voter responses on the survey response summary.
WITH temp AS 
(SELECT voter_id, response FROM responses
where question = ?
group by voter_id
order by date desc)

- Integrate canvassing application

- Add submenu in nav bar

- Give option for two-factor authentication in login
