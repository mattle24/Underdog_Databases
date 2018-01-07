# Underdog_Data

### Let's revolutionize local politics. Or at least build a cool site.

TODO:

- Search based on survey responses
    - When we move to AWS, will be easy to include past exported lists into possible search criteria

- Help section 

- Meta tags

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
