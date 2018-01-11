# Underdog_Data

### Let's revolutionize local politics. Or at least build a cool site.

TODO:

- Bootstrap forms 
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
        > Survey Results
        > Import list 
        > Make list

- Search based on survey responses
    - When we move to AWS, will be easy to include past exported lists into possible search criteria

- Help section 

- Delete test accounts (or change passwords)

- Add sample voter file and deploy 

- In list_results.php, POST input is obtained without filtering input. There should be filter input arrays, or at least some array validation.

- Landing security flaw (must be fixed before actual data deployed)

- Meta tags

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
    - Change nav bar so elements are all to the left/ right with small padding

- Give option for two-factor authentication in login
