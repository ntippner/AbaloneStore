# Insert Category Data
INSERT INTO `Category`
VALUES (-1, 'ROOT', NULL);

INSERT INTO `Category`
VALUES (1, 'HOME', -1);

INSERT INTO `Category`
VALUES (2, 'MEDIA', -1);

INSERT INTO `Category`
VALUES (3, 'BOOK', 2);

INSERT INTO `Category`
VALUES (4, 'DVD', 2);

INSERT INTO `Category`
VALUES (5, 'CD', 2);

INSERT INTO `Category`
VALUES (6, 'GARDEN', 1);

INSERT INTO `Category`
VALUES (7, 'FURNITURE', 1);

INSERT INTO `Category`
VALUES (8, 'GAME', 2);

# Insert Vendor Data
INSERT INTO `VENDOR`
VALUES (NULL, 'Pearson', 'www.pearson.com', '555-555-1234', 'Smith', 'Tom', 'tsmith@mail.com');

INSERT INTO `VENDOR`
VALUES (NULL, 'Thomasville', 'www.thomasville.com', '555-555-7878', 'Carter', 'Thomas', 'tcarter@hotmail.com');

INSERT INTO `VENDOR`
VALUES (NULL, 'Legion Records', 'www.legion.com', '555-444-1000', 'Doe', 'John', 'jdoe@hotmail.com');

INSERT INTO `VENDOR`
VALUES (NULL, 'PIXAR', 'www.pixar.com', '555-888-2000', 'Jobs', 'Steve', 'sjobs@mail.com');

INSERT INTO `VENDOR`
VALUES (NULL, 'Nintendo', 'www.nintendo.com', '145-897-4689', 'House', 'Theresa', 'thouse@noa.com');

INSERT INTO `VENDOR`
VALUES (NULL, 'Penguin Books', 'www.penguin.com', '458-985-1648', 'Bird', 'Larry', 'lbird@penguin.com');

INSERT INTO `VENDOR`
VALUES (NULL, 'Disney', 'www.disney.com', '423-468-7264', 'May', 'Lynn', 'lynn.may@disney.com');

# Insert Product Data
INSERT INTO `PRODUCT`
VALUES (NULL, 1, 'Computer Architecture', 'All information about computer architecture.', 99.78, 25, CURDATE(), 3);

INSERT INTO `PRODUCT`
VALUES (NULL, 1, 'C++ Programming', 'Learn about C++.', 135.33, 10, CURDATE(), 3);

INSERT INTO `PRODUCT`
VALUES (NULL, 2, 'King Bedroom Set', 'Includes bed, 2 nightstands, dresser, and mirror', 2499.95, 3, CURDATE(), 7);

INSERT INTO `PRODUCT`
VALUES (NULL, 2, 'Computer Desk', 'Oak computer desk', 499.50, 5, CURDATE(), 7);

INSERT INTO `PRODUCT`
VALUES (NULL, 1, 'JAVA Programming', 'Learn about JAVA programming', 149.95, 0, CURDATE(), 3);

INSERT INTO `PRODUCT`
VALUES (NULL, 5, 'Legend of Zelda: Breath of the Wild', 'Forget everything you know about The Legend of Zelda games. Step into a world of discovery, exploration and adventure in The Legend of Zelda: Breath of the Wild, a boundary-breaking new game in the acclaimed series. Travel across fields, through forests and up mountain peaks as you discover what has become of the ruined kingdom of Hyrule in this stunning open-air adventure.', 59.99, 40, CURDATE(), 8);

INSERT INTO `PRODUCT`
VALUES (NULL, 6, 'Dante: A Life', 'Acclaimed biog rap her R.W.B. Lewis traces the life and complex development? emotional, artistic, philosophical?of this supreme poet-historian. Here we meet the boy who first encounters the mythic Beatrice, the lyric poet obsessed with love and death, the grand master of dramatic narrative and allegory, and his monumental search for ultimate truth in The Divine Comedy. It is in this masterpiece of self-discovery and redemption that Lewis finds Dante?s own autobiography?and the sum of all his shifting passions and epiphanies.', 9.99, 3, CURDATE(), 3);

INSERT INTO `PRODUCT`
VALUES (NULL, 6, 'The Divine Comedy', 'Robert Pinsky’s new verse translation of the Inferno makes it clear to the contemporary listener, as no other in English has done, why Dante is universally considered a poet of great power, intensity, and strength. This critically acclaimed translation was awarded the Los Angeles Times Book Prize for Poetry and the Harold Morton Landon Translation Award given by the Academy of American Poets. Well versed, rapid, and various in style, the Inferno is narrated by Pinsky and three other leading poets: Seamus Heaney, Frank Bidart, and Louise Glück.', 39.99, 12, CURDATE(), 3);

INSERT INTO `PRODUCT`
VALUES (NULL, 7, 'Finding Nemo', 'Nemo, a young clownfish is captured and taken to a dentist\'s office aquarium. It\'s up to Marlin, his father, and Dory, a friendly but forgetful regal blue tang fish, to make the epic journey to bring Nemo home from Australia\'s Great Barrier Reef.', 12.99, 8, CURDATE(), 4);

INSERT INTO `PRODUCT`
VALUES (NULL, 4, 'Toy Story', 'A story from the perspective of the toys of a child. Watch as they argue and fight to be the favorite toy and attempt to return to their owner after some misunderstandings.', 20.99, 15, CURDATE(), 4);

INSERT INTO `PRODUCT`
VALUES (NULL, 5, 'Super Mario Galaxy', 'Join Mario in his galactic adventure across multiple planets in order to collect the galaxy stars and rescue Princess Peach.', 35.99, 20, CURDATE(), 8);

INSERT INTO `PRODUCT`
VALUES (NULL, 5, 'Super Smash Bros', 'All your favorite Nintendo characters are back and ready to fight. Choose your favorite and fight your way to the end and fight the final boss, Master Hand.', 39.99, 10, CURDATE(), 8);

INSERT INTO `PRODUCT`
VALUES (NULL, 5, 'Metroid Prime Trilogy', 'A collection of the Metroid Prime games. The first games for the Metroid series in first person perspective', 39.00, 5, CURDATE(), 8);

INSERT INTO `PRODUCT`
VALUES (NULL, 5, 'Star Fox Zero', 'Jump into the Arwing and experence Star Fox 64 all over again, complete with new levels, ships, abilities and story.', 35.00, 5, CURDATE(), 8);