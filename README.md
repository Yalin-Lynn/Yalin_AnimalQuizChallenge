# Animal Quiz Challenge

## Project Overview

Animal Quiz Challenge is a web-based quiz game developed for CSC 6370 Web Programming at Georgia State University in Fall 2026.

The project was created by Yalin Zhang and Yunxue Wu (Group GRAD-P01).

The game allows users to register, log in, and answer animal trivia questions. Each game contains 10 questions, and players earn 10 points for each correct answer. The difficulty changes based on the player's performance. After finishing the quiz, players can view their final scores and check the leaderboard.

## Technologies Used

- PHP
- HTML5
- CSS3
- PHP Sessions
- File-based storage
- Git and GitHub

## Main Features

### User Registration and Login

Users can create an account with a username and password. Passwords are hashed before being stored. PHP sessions are used to manage login status and protect pages that require authentication.

### Quiz Game

Each game includes 10 animal questions. A correct answer earns 10 points, with a maximum possible score of 100.

The game has three difficulty levels: Easy, Medium, and Hard.

- Two correct answers in a row increase the difficulty.
- Two incorrect answers in a row decrease the difficulty.
- Questions are not repeated within the same game.

### Results and Leaderboard

After completing the quiz, users can see their final scores. Scores are saved on the server, and the leaderboard displays the top 10 results.

Users can also start a new game.

## Team Contributions

### Yalin Zhang

- Developed user registration and login.
- Implemented session authentication and logout.
- Created the CSS styling for login and registration pages.
- Helped integrate the homepage and update the website design.
- Tested registration, login, and session functionality.

### Yunxue Wu

- Created the animal question bank.
- Developed the quiz gameplay and scoring system.
- Implemented adaptive difficulty.
- Developed the results page and leaderboard.
- Integrated the final homepage.
- Tested gameplay, score calculation, and leaderboard functionality.

Both team members worked together on integration, debugging, and final testing.

## Project Files

- `index.php` - Homepage
- `register.php` - User registration
- `login.php` - User login
- `auth.php` - Session authentication
- `logout.php` - Logout
- `game.php` - Quiz gameplay
- `game_functions.php` - Game logic and score handling
- `questions.php` - Animal questions
- `results.php` - Final score page
- `leaderboard.php` - Top 10 leaderboard
- `style.css` - Website styling
- `team.html` - Team introduction and member responsibilities
- `README.md` - Project documentation

## Testing

We tested the integrated project on the GSU CODD server.

The main tests included:

- Registering a new user
- Logging in with a registered account
- Accessing protected pages
- Logging out
- Completing a 10-question quiz
- Checking score calculation
- Saving scores
- Displaying leaderboard results
- Starting a new game

We also fixed file permission issues that initially prevented scores from being saved.

## GitHub Collaboration

We used separate GitHub branches to work on different parts of the project.

Yalin worked on the `yalin-feature` branch, mainly focusing on user authentication and styling.

Yunxue worked on the `yunxue-feature` branch, focusing on the quiz game, questions, results, and leaderboard.

Each member committed and pushed their own work. We then used Pull Requests to merge both branches into `main`.

## How to Run

1. Download or clone the repository.
2. Place the project files on a PHP-enabled web server.
3. Set up private storage for user accounts and scores.
4. Update the storage paths if using a different server.
5. Open `register.php` to create an account.
6. Log in and start the quiz.

The project was developed and tested on the Georgia State University CODD server.

## GitHub Repository

https://github.com/Yalin-Lynn/Project2_AnimalQuizChallenge

## Course Information

Course: CSC 6370 Web Programming

Semester: Fall 2026

University: Georgia State University

Team: GRAD-P01

Members: Yalin Zhang and Yunxue Wu
