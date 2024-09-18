-- Ensure you're using the correct database
USE db;

-- Insert 50 sample users (47 regular users and 3 admins)
INSERT INTO users (usersEmail, usersPwd, role) VALUES
('admin1@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'admin'),
('admin2@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'admin'),
('admin3@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'admin'),
('user1@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('user2@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('user3@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('user4@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('user5@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('john.doe@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('jane.smith@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('michael.johnson@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('emily.brown@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('david.wilson@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('sarah.taylor@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('robert.anderson@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('jennifer.thomas@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('william.jackson@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('olivia.white@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('james.harris@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('emma.martin@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('daniel.thompson@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('sophia.garcia@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('matthew.martinez@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('ava.robinson@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('joseph.clark@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('isabella.rodriguez@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('christopher.lewis@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('mia.lee@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('andrew.walker@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('charlotte.hall@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('ethan.allen@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('amelia.young@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('alexander.hernandez@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('abigail.king@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('ryan.wright@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('harper.lopez@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('nicholas.hill@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('evelyn.scott@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('jonathan.green@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('elizabeth.adams@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('christopher.baker@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('sofia.gonzalez@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('dylan.nelson@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('grace.carter@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('samuel.mitchell@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('chloe.perez@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('benjamin.roberts@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('zoey.turner@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user'),
('jacob.phillips@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890', 'user');