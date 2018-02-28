# Underdog_Data

### Let's revolutionize local politics. Or at least build a cool site.

TODO:

- In list_results.php, POST input is obtained without filtering input. There should be filter input arrays, or at least some array validation.

- Landing security flaw (must be fixed before actual data deployed)

- Add more search features 
    - Voter history
    
- Filter out multiple voter responses on the survey response summary.
WITH temp AS 
(SELECT voter_id, response FROM responses
where question = ?
group by voter_id
order by date desc)

- The reset button in the survey results page doesn't work ¯\_(ツ)_/¯

- Integrate canvassing application

- Add submenu in nav bar
    - Change nav bar so elements are all to the left/ right with small padding

- Search based on survey responses
    - When we move to AWS, will be easy to include past exported lists into possible search criteria

- Give option for two-factor authentication in login
