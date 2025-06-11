-- Create the database
CREATE DATABASE IF NOT EXISTS todoDB;

-- Use the database
USE todoDB;

-- Create the todoList table
CREATE TABLE IF NOT EXISTS todoList (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(250) NOT NULL,
    content TEXT
);

-- Insert some custom entries
INSERT INTO todoList (title, content) VALUES
('Buy groceries', 'Milk, eggs, bread, and fruits.'),
('Finish project report', 'Complete the financial summary and submit by Friday.'),
('Call plumber', 'Fix the leaking faucet in the kitchen.'),
('Read a book', 'Start reading “Atomic Habits” by James Clear.'),
('Workout', 'Gym session: legs and core, 45 minutes.');