# CSE330
### Group members:
- 457001
- 475337

### Creative Portion:
1. Calendar shows **today's date** with a red circle around the date number.
2. Added **event types**  with select options. Users can add different types of events, and they will be displayed with various background color to distinguish different types of events. Users can also check what type of event they want to show on the calendar table.
3. Added **Back to current month button** . Clicking the button will lead the user back the the current month.
4. Added **group event** feature where users can create a group event and add other users to an event. After adding the event only the host(the creator of the group event) can delete or modify the event. Host cannot create a group event with a non-existent user.

### Link: 
http://ec2-3-136-116-147.us-east-2.compute.amazonaws.com/~ChenyeQi/module5-group/login.html

Users: ha, he, hi (Passwords are the same as usernames)

### Group Event Design: 
Any registered user can add group event after they click the add group event button and add the username of other registered users. <br />
If they only added wrong usernames, the group event will not be successfully added. <br />
If they added some existing users and some wrong usernames, they group event will be added but only to the existing users. <br />
Only the host can modify or delete the group event. <br />
Members can see who the host is for the group event, while host can see information of members as well. <br />

Example of group event shown on host: **study session 10:00 Host: ha Members: he, ha [modify button] [delete button]** <br />
Example of group event shown on members(not host): **study session 10:00 Host: ha**